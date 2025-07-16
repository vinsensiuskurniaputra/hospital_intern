<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Models\PresenceSession;
use App\Models\Presence;
use App\Models\Student;
use App\Models\Certificate;
use App\Models\Stase;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class StudentAttendanceController extends Controller
{
    public function index(Request $request)
    {
        $student = Auth::user()->student;
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        $presences = Presence::where('student_id', $student->id)
            ->whereYear('date_entry', $year)
            ->whereMonth('date_entry', $month)
            ->with(['presenceSession.schedule.stase', 'presenceSession.schedule.internshipClass'])
            ->get();

        // Calculate attendance stats
        $total = $presences->count();
        $present = $presences->where('status', 'present')->count();
        $sick = $presences->where('status', 'sick')->count();
        $absent = $presences->where('status', 'absent')->count();

        // Create attendanceStats array that matches what the view expects
        $attendanceStats = [
            'total' => $total,
            'present' => [
                'count' => $present,
                'percent' => $total > 0 ? round(($present / $total) * 100, 1) : 0
            ],
            'sick' => [
                'count' => $sick,
                'percent' => $total > 0 ? round(($sick / $total) * 100, 1) : 0
            ],
            'absent' => [
                'count' => $absent,
                'percent' => $total > 0 ? round(($absent / $total) * 100, 1) : 0
            ]
        ];

        // Get stase attendance data from database
        $stases = $this->getStaseAttendanceData($student->id);

        // Check if certificate is available
        $certificate = Certificate::where('student_id', $student->id)->first();
        $allStagesCompleted = $student->is_finished; // atau field kelulusan yang benar

        return view('pages.student.attendance.index', compact(
            'presences',
            'total',
            'present',
            'sick',
            'absent',
            'attendanceStats',
            'stases',
            'allStagesCompleted',
            'certificate'
        ));

    }

    private function getStaseAttendanceData($studentId)
    {
        $student = \App\Models\Student::with('internshipClass.schedules.stase')->findOrFail($studentId);

        // Ambil semua jadwal stase dari kelas magang mahasiswa
        $schedules = $student->internshipClass
            ? $student->internshipClass->schedules()->with('stase')->get()
            : collect();

        $stases = [];

        foreach ($schedules as $schedule) {
            $stase = $schedule->stase;
            if (!$stase) continue;

            // Ambil semua presensi mahasiswa di stase ini (berdasarkan schedule_id)
            $presences = \App\Models\Presence::where('student_id', $studentId)
                ->whereHas('presenceSession.schedule', function($q) use ($schedule) {
                    $q->where('stase_id', $schedule->stase_id);
                })
                ->get();

            $totalAttendances = $presences->count();
            $presentCount = $presences->where('status', 'present')->count();
            $sickCount = $presences->where('status', 'sick')->count();
            $absentCount = $presences->where('status', 'absent')->count();

            $percentage = $totalAttendances > 0 ? round(($presentCount / $totalAttendances) * 100, 1) : 0;

            // Ambil tanggal rotasi stase dari schedule
            $dateRange = $schedule->start_date && $schedule->end_date
                ? \Carbon\Carbon::parse($schedule->start_date)->format('d M') . ' - ' . \Carbon\Carbon::parse($schedule->end_date)->format('d M Y')
                : '-';

            $stases[$stase->id] = [
                'name' => $stase->name,
                'department' => 'Departemen ' . $stase->name,
                'date' => $dateRange,
                'start_date' => $schedule->start_date,
                'end_date' => $schedule->end_date,
                'percentage' => $percentage,
                'attendance' => [
                    'present' => $totalAttendances > 0 ? round(($presentCount / $totalAttendances) * 100, 1) : 0,
                    'sick' => $totalAttendances > 0 ? round(($sickCount / $totalAttendances) * 100, 1) : 0,
                    'absent' => $totalAttendances > 0 ? round(($absentCount / $totalAttendances) * 100, 1) : 0
                ]
            ];
        }

        // Jika ada stase yang sama di beberapa jadwal, hanya tampilkan satu (pakai key $stase->id)
        return array_values($stases);
    }
    
    public function submitToken(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required|string|size:6',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'device_info' => 'nullable|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        // Cari token di database
        $token = strtoupper($request->token);
        $now = Carbon::now();
        $today = $now->format('Y-m-d');
        
        $session = PresenceSession::where('token', $token)
            ->whereDate('date', $today)
            ->where(function($query) use ($now) {
                $query->whereNull('expiration_time')
                    ->orWhere('expiration_time', '>', $now);
            })
            ->first();
            
        if (!$session) {
            return response()->json([
                'status' => false,
                'message' => 'Token tidak valid atau sudah kadaluarsa'
            ], 404);
        }
        
        // Cek apakah mahasiswa berada dalam radius yang diizinkan
        $distance = $this->calculateDistance(
            $request->latitude,
            $request->longitude,
            $session->latitude,
            $session->longitude
        );
        
        if ($distance > $session->radius) {
            return response()->json([
                'status' => false,
                'message' => 'Anda berada di luar area yang diizinkan',
                'data' => [
                    'distance' => round($distance),
                    'max_radius' => $session->radius
                ]
            ], 403);
        }
        
        // Dapatkan data mahasiswa
        $student = Student::where('user_id', Auth::id())->first();
        
        if (!$student) {
            return response()->json([
                'status' => false,
                'message' => 'Data mahasiswa tidak ditemukan'
            ], 404);
        }
        
        // Cek apakah mahasiswa terdaftar di kelas tersebut
        $schedule = $session->schedule;
        
        // Contoh validasi apakah mahasiswa terdaftar di jadwal ini
        // Karena kita menggunakan relasi hasManyThrough antara Student dan Schedule melalui InternshipClass
        $isRegistered = $student->schedules()
            ->where('schedules.id', $schedule->id)
            ->exists();
            
        if (!$isRegistered) {
            return response()->json([
                'status' => false,
                'message' => 'Anda tidak terdaftar dalam jadwal ini'
            ], 403);
        }
        
        // Cek apakah student sudah presensi hari ini
        $existingPresence = Presence::where('student_id', $student->id)
            ->where('presence_sessions_id', $session->id)
            ->whereDate('date_entry', $today)
            ->first();
            
        if ($existingPresence) {
            return response()->json([
                'status' => false,
                'message' => 'Anda sudah melakukan presensi untuk jadwal ini hari ini'
            ], 409);
        }
        
        // Catat presensi dengan check_out null saat pertama kali check-in
        $presence = Presence::create([
            'student_id' => $student->id,
            'presence_sessions_id' => $session->id,
            'date_entry' => $today,
            'check_in' => $now->format('H:i:s'),
            'check_out' => null, // Tidak ada check-out awal, akan diisi saat mahasiswa checkout
            'status' => 'present',
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'device_info' => $request->device_info ?? 'Web Browser'
        ]);
        
        return response()->json([
            'status' => true,
            'message' => 'Presensi berhasil',
            'data' => [
                'presence_id' => $presence->id,
                'check_in_time' => $presence->check_in,
                'schedule' => [
                    'class' => $schedule->internshipClass->name ?? 'Kelas',
                    'stase' => $schedule->stase->name ?? 'Stase'
                ]
            ]
        ]);
    }
    
    public function checkOut(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'device_info' => 'nullable|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        // Dapatkan data mahasiswa
        $student = Student::where('user_id', Auth::id())->first();
        if (!$student) {
            return response()->json([
                'status' => false,
                'message' => 'Data mahasiswa tidak ditemukan'
            ], 404);
        }
        
        $today = Carbon::now()->format('Y-m-d');
        $now = Carbon::now();
        
        // Cari presensi yang belum check-out
        $presence = Presence::where('student_id', $student->id)
            ->whereDate('date_entry', $today)
            ->whereNull('check_out')
            ->first();
            
        if (!$presence) {
            return response()->json([
                'status' => false,
                'message' => 'Tidak ada presensi yang perlu di-checkout'
            ], 404);
        }
        
        // Validasi lokasi checkout (harus dalam radius 12m dari rumah sakit)
        $hospitalLatitude = config('location.hospital.latitude');
        $hospitalLongitude = config('location.hospital.longitude');
        
        $distance = $this->calculateDistance(
            $request->latitude, 
            $request->longitude, 
            $hospitalLatitude, 
            $hospitalLongitude
        );
        
        if ($distance > 500) { // Batasan radius 500 meter
            return response()->json([
                'status' => false,
                'message' => 'Anda berada di luar radius presensi yang diizinkan',
                'data' => [
                    'distance' => round($distance),
                    'max_radius' => 500
                ]
            ]);
        }
        
        // Update check-out time
        $presence->update([
            'check_out' => $now->format('H:i:s'),
            'latitude_checkout' => $request->latitude,
            'longitude_checkout' => $request->longitude
        ]);
        
        return response()->json([
            'status' => true,
            'message' => 'Check-out berhasil',
            'data' => [
                'presence_id' => $presence->id,
                'date' => $today,
                'check_in' => $presence->check_in,
                'check_out' => $presence->check_out
            ]
        ]);
    }
    
    public function downloadCertificate($id)
    {
        $student = \App\Models\Student::findOrFail($id);

        // Cek kelulusan dan sertifikat
        if (!$student->is_finished) {
            abort(403, 'Sertifikat hanya bisa diunduh jika sudah lulus magang.');
        }

        $certificate = Certificate::where('student_id', $student->id)->first();

        if (!$certificate) {
            abort(403, 'Sertifikat belum tersedia.');
        }

        $data = [
            'nama' => $student->user->name,
            'kode' => $certificate->kode,
            'periode' => $certificate->periode ?? null,
        ];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('components.admin.certificate.template', $data);
        $pdf->setPaper('A4', 'portrait');
        $filename = "{$certificate->kode}.pdf";

        return $pdf->download($filename);
    }
    
    public function viewCertificate($id)
    {
        // Cari student berdasarkan id
        $student = \App\Models\Student::findOrFail($id);
        
        // Cari certificate yang terhubung dengan student_id tersebut
        $certificate = Certificate::where('student_id', $student->id)->first();

        if (!$certificate) {
            abort(404, 'Sertifikat tidak ditemukan untuk mahasiswa ini.');
        }

        $data = [
            'nama' => $student->user->name,
            'kode' => $certificate->kode,
            'periode' => $certificate->periode ?? null,
        ];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('components.admin.certificate.template', $data);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream("Sertifikat_{$certificate->kode}.pdf");
    }
    
    /**
     * Calculate distance between two coordinates using Haversine formula
     * 
     * @param float $lat1 Latitude of point 1
     * @param float $lon1 Longitude of point 1
     * @param float $lat2 Latitude of point 2
     * @param float $lon2 Longitude of point 2
     * @return float Distance in meters
     */
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        // Convert degrees to radians
        $lat1 = deg2rad($lat1);
        $lon1 = deg2rad($lon1);
        $lat2 = deg2rad($lat2);
        $lon2 = deg2rad($lon2);
        
        // Haversine formula
        $dlat = $lat2 - $lat1;
        $dlon = $lon2 - $lon1;
        $a = sin($dlat/2) * sin($dlat/2) + cos($lat1) * cos($lat2) * sin($dlon/2) * sin($dlon/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        
        // Earth's radius in meters
        $radius = 6371000;
        
        // Distance in meters
        return $radius * $c;
    }

    public function getPresences(Request $request)
    {
        $student = Auth::user()->student;
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);
        
        $presences = Presence::where('student_id', $student->id)
            ->whereYear('date_entry', $year)
            ->whereMonth('date_entry', $month)
            ->get();
        
        // Pastikan mengembalikan format yang sesuai dengan DataTables
        return response()->json([
            'data' => $presences,
            'draw' => $request->get('draw'),
            'recordsTotal' => $presences->count(),
            'recordsFiltered' => $presences->count()
        ]);
    }

    public function getPresenceDetail($date)
    {
        $student = Auth::user()->student;
        
        $presence = Presence::where('student_id', $student->id)
            ->whereDate('date_entry', $date)
            ->with(['presenceSession.schedule.stase', 'presenceSession.schedule.internshipClass'])
            ->first();
        
        return response()->json([
            'success' => true,
            'presence' => $presence
        ]);
    }
}
