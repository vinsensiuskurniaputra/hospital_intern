<?php

namespace Database\Seeders;

use App\Models\Schedule;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $today = now();
        
        // Helper function untuk mengecek overlap
        $checkOverlap = function($staseId, $internshipClassId, $startDate, $endDate) {
            return Schedule::where('stase_id', $staseId)
                ->where('internship_class_id', $internshipClassId)
                ->where(function($query) use ($startDate, $endDate) {
                    // Check for any overlap with existing schedules
                    $query->where(function($q) use ($startDate, $endDate) {
                        $q->where('start_date', '<=', $startDate)
                          ->where('end_date', '>=', $startDate);
                    })->orWhere(function($q) use ($startDate, $endDate) {
                        $q->where('start_date', '<=', $endDate)
                          ->where('end_date', '>=', $endDate);
                    })->orWhere(function($q) use ($startDate, $endDate) {
                        $q->where('start_date', '>=', $startDate)
                          ->where('end_date', '<=', $endDate);
                    });
                })
                ->exists();
        };
        
        $schedules = [
            [
                'internship_class_id' => 1,
                'stase_id' => 1,
                'start_date' => '2024-04-01',
                'end_date' => '2024-06-30',
            ],
            [
                'internship_class_id' => 2,
                'stase_id' => 2,
                'start_date' => '2024-04-01',
                'end_date' => '2024-06-30',
            ],
            [
                'internship_class_id' => 3,
                'stase_id' => 3,
                'start_date' => '2024-07-01',
                'end_date' => '2024-09-30',
            ],
        ];
        
        // Tambah jadwal lainnya...
        
        foreach ($schedules as $schedule) {
            // Cek overlap sebelum menambahkan jadwal
            if (!$checkOverlap(
                $schedule['stase_id'], 
                $schedule['internship_class_id'],
                $schedule['start_date'],
                $schedule['end_date']
            )) {
                Schedule::create($schedule);
            } else {
                $this->command->info(
                    "Skipping jadwal stase {$schedule['stase_id']} kelas {$schedule['internship_class_id']} " .
                    "dari {$schedule['start_date']} hingga {$schedule['end_date']} karena tumpang tindih."
                );
            }
        }
    }
}