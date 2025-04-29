<?php

namespace Database\Seeders;

use App\Models\PresenceSession;
use App\Models\Schedule;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PresenceSessionSeeder extends Seeder
{
    public function run(): void
    {
        $schedules = Schedule::all();

        if ($schedules->isEmpty()) {
            echo "Warning: No schedules found. Run ScheduleSeeder first.\n";
            return;
        }

        foreach ($schedules as $schedule) {
            // Untuk setiap jadwal, buat sesi kehadiran
            // Menggunakan tanggal dari schedule dan waktu mulai/selesai
            // Jika schedule tidak memiliki tanggal, gunakan tanggal acak dalam 60 hari ke depan
            $scheduleDate = $schedule->date_schedule ?? Carbon::now()->addDays(rand(0, 60))->format('Y-m-d');
            
            // Jika schedule tidak memiliki waktu mulai/selesai, buat waktu simulasi
            $startTime = $schedule->start_time ?? Carbon::createFromTime(8, 0, 0)->addHours(rand(0, 8))->format('H:i:s');
            $endTime = $schedule->end_time ?? Carbon::parse($startTime)->addHours(rand(1, 3))->format('H:i:s');
            
            PresenceSession::create([
                'schedule_id' => $schedule->id,
                'token' => strtoupper(Str::random(6)),
                'date' => $scheduleDate,
                'start_time' => $startTime,
                'end_time' => $endTime
            ]);
        }
    }
}