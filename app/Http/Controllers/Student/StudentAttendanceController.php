<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\PresenceSession;
use App\Models\Presence;
use App\Models\Student;
use Carbon\Carbon;

class StudentAttendanceController extends Controller
{
    public function index()
    {
        $attendanceStats = [
            'total' => 1000,
            'hadir' => 52.1,
            'izin' => 22.8,
            'alpha' => 15.9
        ];

        $stases = [
            [
                'name' => 'Stase Dokter Umum',
                'department' => 'Departemen Umum',
                'date' => '1 Jan - 31 Maret 2025',
                'percentage' => 80
            ],
            [
                'name' => 'Stase Dokter Umum',
                'department' => 'Departemen Umum',
                'date' => '1 Apr - 30 Juni 2025',
                'percentage' => 0
            ]
        ];

        return view('pages.student.attendance.index', compact('attendanceStats', 'stases'));
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
}