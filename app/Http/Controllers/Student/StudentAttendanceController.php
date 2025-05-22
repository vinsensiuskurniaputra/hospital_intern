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
    public function index()
    {
        $student = Auth::user()->student;
        
        try {
            // Get attendance data from database
            $presences = Presence::where('student_id', $student->id)
                ->whereMonth('date_entry', Carbon::now()->month)
                ->whereYear('date_entry', Carbon::now()->year)
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
            $allStagesCompleted = $certificate ? true : false;

            return view('pages.student.attendance.index', compact(
                'presences',
                'total',
                'present',
                'sick',
                'absent',
                'attendanceStats',
                'stases',
                'allStagesCompleted'
            ));

        } catch (\Exception $e) {
            Log::error('Error loading attendance stats: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memuat data kehadiran');
        }
    }

    private function getStaseAttendanceData($studentId)
    {
        // Get all stases that the student has attended
        $staseAttendance = Presence::where('student_id', $studentId)
            ->with(['presenceSession.schedule.stase'])
            ->get()
            ->groupBy(function($presence) {
                return $presence->presenceSession->schedule->stase->id ?? 'unknown';
            });

        $stases = [];
        
        foreach ($staseAttendance as $staseId => $attendances) {
            if ($staseId === 'unknown') continue;
            
            $stase = $attendances->first()->presenceSession->schedule->stase;
            $totalAttendances = $attendances->count();
            $presentCount = $attendances->where('status', 'present')->count();
            $sickCount = $attendances->where('status', 'sick')->count();
            $absentCount = $attendances->where('status', 'absent')->count();
            
            // Calculate percentage (present attendance rate)
            $percentage = $totalAttendances > 0 ? round(($presentCount / $totalAttendances) * 100, 1) : 0;
            
            // Get date range for this stase
            $firstDate = $attendances->min('date_entry');
            $lastDate = $attendances->max('date_entry');
            $dateRange = Carbon::parse($firstDate)->format('d M') . ' - ' . Carbon::parse($lastDate)->format('d M Y');
            
            $stases[] = [
                'name' => $stase->name,
                'department' => 'Departemen ' . $stase->name,
                'date' => $dateRange,
                'percentage' => $percentage,
                'attendance' => [
                    'present' => $totalAttendances > 0 ? round(($presentCount / $totalAttendances) * 100, 1) : 0,
                    'sick' => $totalAttendances > 0 ? round(($sickCount / $totalAttendances) * 100, 1) : 0,
                    'absent' => $totalAttendances > 0 ? round(($absentCount / $totalAttendances) * 100, 1) : 0
                ]
            ];
        }
        
        return $stases;
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
    
    public function downloadCertificate()
    {
        $student = Auth::user()->student;
        
        // Check if certificate exists
        $certificate = Certificate::where('student_id', $student->id)->first();
        
        if (!$certificate) {
            return response()->json([
                'status' => false,
                'message' => 'Sertifikat belum tersedia. Pastikan Anda telah menyelesaikan semua stase.'
            ], 404);
        }
        
        try {
            // Generate PDF certificate
            $data = [
                'student' => $student,
                'certificate' => $certificate,
                'issue_date' => Carbon::parse($certificate->issue_date)->format('d F Y'),
                'completion_date' => Carbon::parse($certificate->completion_date)->format('d F Y')
            ];
            
            $pdf = Pdf::loadView('certificates.template', $data);
            $pdf->setPaper('A4', 'landscape');
            
            $filename = 'Sertifikat_' . str_replace(' ', '_', $student->name) . '_' . Carbon::now()->format('Y-m-d') . '.pdf';
            
            return $pdf->download($filename);
            
        } catch (\Exception $e) {
            Log::error('Error generating certificate: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan saat mengunduh sertifikat.'
            ], 500);
        }
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
        $month = $request->month;
        $year = $request->year;
        
        $presences = Presence::where('student_id', $student->id)
            ->whereYear('date_entry', $year)
            ->whereMonth('date_entry', $month)
            ->get()
            ->pluck('status', 'date_entry')
            ->toArray();
        
        return response()->json($presences);
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