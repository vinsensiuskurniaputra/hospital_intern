<?php

namespace Database\Seeders;

use App\Models\InternshipClass;
use App\Models\Presence;
use App\Models\PresenceSession;
use App\Models\Schedule;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CustomUserPresenceSeeder extends Seeder
{
    public function run(): void
    {
        // Konfigurasi untuk seeder
        $studentNim = '123456789'; // Ganti dengan NIM yang diinginkan
        $periodInDays = 365; // Jumlah hari ke belakang untuk generate presensi
        $defaultTimeForNonPresent = '00:00:00';
        echo "Using default time for non-present status: " . $defaultTimeForNonPresent . "\n";
        
        // Cari student berdasarkan NIM
        $student = Student::where('nim', $studentNim)->first();
        if (!$student) {
            echo "Student with NIM: $studentNim not found.\n";
            return;
        }
        
        echo "Generating presences for student: $studentNim (ID: {$student->id})\n";
        
        // Hapus presensi yang ada untuk memulai dari awal
        Presence::where('student_id', $student->id)->delete();
        echo "Deleted existing presence records for this student.\n";
        
        // Dapatkan kelas internship dari mahasiswa
        $internshipClass = InternshipClass::find($student->internship_class_id);
        if (!$internshipClass) {
            echo "Internship class not found for this student.\n";
            return;
        }
        
        // Dapatkan jadwal yang terkait dengan kelas internship
        $schedules = Schedule::whereHas('internshipClass', function($query) use ($internshipClass) {
            $query->where('id', $internshipClass->id);
        })->get();
        
        if ($schedules->isEmpty()) {
            echo "No schedules found for this internship class.\n";
            return;
        }
        
        // Generate presensi untuk periode yang ditentukan
        echo "Generating presence sessions and records for the past year...\n";
        $now = Carbon::now();
        $startDate = $now->copy()->subDays($periodInDays);
        $currentDate = $startDate->copy();
        
        // Asumsikan distribusi status: 80% hadir, 10% sakit, 10% alpha
        $statusDistribution = ['present' => 80, 'sick' => 10, 'absent' => 10];
        
        // Time slots untuk variasi waktu presensi
        $morningSlots = [
            ['checkin' => '07:30', 'checkout' => '09:30'],
            ['checkin' => '08:00', 'checkout' => '10:00'],
            ['checkin' => '08:30', 'checkout' => '10:30'],
            ['checkin' => '09:00', 'checkout' => '11:00']
        ];
        
        $afternoonSlots = [
            ['checkin' => '12:30', 'checkout' => '14:30'],
            ['checkin' => '13:00', 'checkout' => '15:00'],
            ['checkin' => '13:30', 'checkout' => '15:30'],
            ['checkin' => '14:00', 'checkout' => '16:00']
        ];
        
        // Untuk setiap hari dalam periode
        while ($currentDate <= $now) {
            // Skip hari Minggu
            if ($currentDate->dayOfWeek == Carbon::SUNDAY) {
                $currentDate->addDay();
                continue;
            }
            
            // Pilih jadwal secara acak
            $schedule = $schedules->random();
            
            // Tentukan time slot berdasarkan pagi/siang
            $timeSlots = $currentDate->hour < 12 ? $morningSlots : $afternoonSlots;
            $slot = $timeSlots[array_rand($timeSlots)];
            
            // Generate token untuk sesi presensi
            $tokenPrefix = $currentDate->hour < 12 ? 'AM' : 'PM';
            $token = $tokenPrefix . strtoupper(substr(md5(rand()), 0, 6));
            
            // Buat sesi presensi
            $session = PresenceSession::create([
                'schedule_id' => $schedule->id,
                'token' => $token,
                'date' => $currentDate->format('Y-m-d'),
                'expiration_time' => $currentDate->copy()->endOfDay(), // Gunakan expiration_time
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ]);
            
            // Tentukan status presensi berdasarkan distribusi
            $rand = rand(1, 100);
            $cumulativeProb = 0;
            $status = 'absent'; // Default
            
            foreach ($statusDistribution as $stat => $prob) {
                $cumulativeProb += $prob;
                if ($rand <= $cumulativeProb) {
                    $status = $stat;
                    break;
                }
            }
            
            // Tentukan waktu check-in dan check-out berdasarkan status
            $checkIn = ($status === 'present') ? $slot['checkin'] . ':00' : $defaultTimeForNonPresent;
            $checkOut = ($status === 'present') ? $slot['checkout'] . ':00' : $defaultTimeForNonPresent;
            
            // Generate lokasi random untuk presensi (dalam radius Semarang)
            $baseLatitude = -6.9932; // Sekitar Semarang
            $baseLongitude = 110.4203;
            
            // Random offset dalam radius 0.01 derajat (sekitar 1km)
            $latOffset = (rand(-100, 100) / 10000);
            $lonOffset = (rand(-100, 100) / 10000);
            
            $latitude = $baseLatitude + $latOffset;
            $longitude = $baseLongitude + $lonOffset;
            
            // Catat presensi
            Presence::create([
                'student_id' => $student->id,
                'presence_sessions_id' => $session->id,
                'date_entry' => $currentDate->format('Y-m-d'),
                'check_in' => $checkIn,
                'check_out' => $checkOut,
                'status' => $status,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ]);
            
            // Maju ke hari berikutnya
            $currentDate->addDay();
        }
        
        echo "Generated " . Presence::where('student_id', $student->id)->count() . " presence records successfully.\n";
    }
}