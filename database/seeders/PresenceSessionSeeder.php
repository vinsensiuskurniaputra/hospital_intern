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
            // Menggunakan tanggal dari schedule
            $scheduleDate = $schedule->date_schedule ?? Carbon::now()->addDays(rand(0, 60))->format('Y-m-d');
            
            // Buat token dan atur expiration_time ke akhir hari
            PresenceSession::create([
                'schedule_id' => $schedule->id,
                'token' => strtoupper(Str::random(6)),
                'date' => $scheduleDate,
                'expiration_time' => Carbon::parse($scheduleDate)->endOfDay()
            ]);
        }
    }
}