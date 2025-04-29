<?php

// filepath: d:\Folder Utama\Documents\Kuliah\Semester 4\PBL\hospital_intern\database\seeders\PresenceSeeder.php
namespace Database\Seeders;

use App\Models\Presence;
use App\Models\PresenceSession;
use App\Models\Student;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PresenceSeeder extends Seeder
{
    public function run(): void
    {
        $presenceSessions = PresenceSession::all();
        $students = Student::all();

        if ($presenceSessions->isEmpty() || $students->isEmpty()) {
            echo "Warning: No presence sessions or students found. Run PresenceSessionSeeder first.\n";
            return;
        }

        foreach ($presenceSessions as $session) {
            // Tanggal dan waktu session
            $sessionDate = $session->date;
            $sessionStartTime = Carbon::parse($session->start_time);
            $sessionEndTime = Carbon::parse($session->end_time);
            
            foreach ($students as $student) {
                // 85% chance of being present
                $status = rand(0, 100) > 15 ? 'present' : (rand(0, 1) ? 'sick' : 'absent');
                
                // Random check-in time (slightly before or after session start)
                $checkInVariance = rand(-10, 15);
                $checkIn = $sessionStartTime->copy()->addMinutes($checkInVariance)->format('H:i:s');
                
                // Random check-out time (slightly before or after session end)
                $checkOutVariance = rand(-10, 15);
                $checkOut = $sessionEndTime->copy()->addMinutes($checkOutVariance)->format('H:i:s');
                
                Presence::create([
                    'student_id' => $student->id,
                    'presence_sessions_id' => $session->id,
                    'date_entry' => $sessionDate,
                    'check_in' => $checkIn,
                    'check_out' => $checkOut,
                    'status' => $status,
                ]);
            }
        }
    }
}