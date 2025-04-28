<?php

namespace Database\Seeders;

use App\Models\Schedule;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $schedules = [
            [
                'internship_class_id' => 1,
                'stase_id' => 1,
                'departement_id' => 1,
                'date_schedule' => now(),
                'start_date' => '2024-04-01',
                'end_date' => '2024-06-30',
                'start_time' => '07:00',
                'end_time' => '15:00',
            ],
            [
                'internship_class_id' => 2,
                'stase_id' => 2,
                'departement_id' => 2,
                'date_schedule' => now(),
                'start_date' => '2024-04-01',
                'end_date' => '2024-06-30',
                'start_time' => '08:00',
                'end_time' => '16:00',
            ],
            [
                'internship_class_id' => 3,
                'stase_id' => 3,
                'departement_id' => 3,
                'date_schedule' => now(),
                'start_date' => '2024-07-01',
                'end_date' => '2024-09-30',
                'start_time' => '07:00',
                'end_time' => '15:00',
            ],
        ];

        foreach ($schedules as $schedule) {
            Schedule::create($schedule);
        }
    }
}