<?php

namespace Database\Seeders;

use App\Models\Schedule;
use App\Models\Student;
use App\Models\Stase;
use App\Models\InternshipClass;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserScheduleSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Cek apakah user ID 2 memiliki student
        $student = Student::where('user_id', 2)->first();

        if (!$student) {
            $this->command->info('User ID 2 tidak memiliki data student. Jadwal tidak dibuat.');
            return;
        }

        $this->command->info('Membuat jadwal untuk student: ' . $student->nim);
        
        // 2. Ambil kelas internship dari student
        $internshipClassId = $student->internship_class_id;
        
        if (!$internshipClassId) {
            $this->command->info('Student tidak memiliki kelas. Menggunakan kelas default.');
            $internshipClass = InternshipClass::first();
            
            if (!$internshipClass) {
                $this->command->info('Tidak ada kelas internship. Membuat kelas baru.');
                
                // Cek apakah ada campus
                $campusId = DB::table('campuses')->first()?->id ?? 1;
                $classYearId = DB::table('class_years')->first()?->id ?? 1;
                
                // Buat campus jika belum ada
                if (!$campusId) {
                    $campusId = DB::table('campuses')->insertGetId([
                        'name' => 'Rumah Sakit Pusat',
                        'detail' => 'Rumah Sakit Pendidikan',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
                
                // Buat class year jika belum ada
                if (!$classYearId) {
                    $classYearId = DB::table('class_years')->insertGetId([
                        'class_year' => '2024/2025',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
                
                $internshipClassId = DB::table('internship_classes')->insertGetId([
                    'campus_id' => $campusId,
                    'class_year_id' => $classYearId,
                    'name' => 'Kelas A',
                    'description' => 'Kelas Internship Default',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                $internshipClassId = $internshipClass->id;
            }
            
            // Update student dengan kelas yang baru
            $student->update(['internship_class_id' => $internshipClassId]);
        }
        
        // 3. PERBAIKAN: Buat jadwal baru tanpa menghapus jadwal yang ada
        // Cek jadwal yang sudah ada untuk mencegah duplikasi
        $this->command->info('Memeriksa jadwal yang sudah ada untuk kelas ' . $internshipClassId);
        $existingDates = Schedule::where('internship_class_id', $internshipClassId)
            ->pluck('date_schedule')
            ->map(function($date) {
                return Carbon::parse($date)->format('Y-m-d');
            })
            ->toArray();
        
        $this->command->info('Ditemukan ' . count($existingDates) . ' jadwal yang sudah ada');
        
        // 4. Dapatkan semua stase yang tersedia
        $stases = Stase::all();
        
        if ($stases->isEmpty()) {
            $this->command->info('Tidak ada stase yang tersedia. Membuat stase default.');
            
            // Cek apakah ada departemen
            $departmentId = DB::table('departements')->first()?->id;
            
            if (!$departmentId) {
                $departmentId = DB::table('departements')->insertGetId([
                    'name' => 'Departemen Medis Umum',
                    'description' => 'Departemen untuk Kedokteran Umum',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            
            // Buat beberapa stase default
            $staseNames = [
                'Penyakit Dalam',
                'Bedah',
                'Anak',
                'Kebidanan',
                'Neurologi',
                'Mata',
                'THT'
            ];
            
            foreach ($staseNames as $index => $name) {
                Stase::create([
                    'name' => $name,
                    'departement_id' => $departmentId,
                    'responsible_user_id' => 1, // Set ke user ID yang valid
                    'detail' => 'Stase ' . $name,
                ]);
            }
            
            $stases = Stase::all();
        }
        
        // 5. Buat jadwal untuk periode saat ini dan masa depan
        $today = Carbon::now();
        $scheduleData = [];
        
        // Periode 1: Bulan ini
        $currentMonth = $today->copy();
        $endOfCurrentMonth = $today->copy()->endOfMonth();
        
        // Periode 2: Bulan depan
        $nextMonth = $today->copy()->addMonth()->startOfMonth();
        $endOfNextMonth = $nextMonth->copy()->endOfMonth();
        
        // Periode 3: Dua bulan dari sekarang
        $twoMonthsLater = $today->copy()->addMonths(2)->startOfMonth();
        $endOfTwoMonths = $twoMonthsLater->copy()->endOfMonth();
        
        // Buat jadwal untuk 3 periode dengan stase yang berbeda
        $staseCount = $stases->count();
        $staseIndex = 0;
        
        // Jadwal untuk periode saat ini
        $scheduleForCurrentMonth = [
            'internship_class_id' => $internshipClassId,
            'stase_id' => $stases[($staseIndex++) % $staseCount]->id,
            'date_schedule' => $today->format('Y-m-d'), // Tanggal saat melakukan migrasi
            'start_date' => $today->format('Y-m-d'),
            'end_date' => $endOfCurrentMonth->format('Y-m-d'),
            'created_at' => now(),
            'updated_at' => now(),
        ];
        
        // Hanya tambahkan jika belum ada jadwal pada tanggal yang sama
        if (!in_array($today->format('Y-m-d'), $existingDates)) {
            $scheduleData[] = $scheduleForCurrentMonth;
        }
        
        // Jadwal untuk bulan depan
        if (!in_array($nextMonth->format('Y-m-d'), $existingDates)) {
            $scheduleData[] = [
                'internship_class_id' => $internshipClassId,
                'stase_id' => $stases[($staseIndex++) % $staseCount]->id,
                'date_schedule' => $nextMonth->format('Y-m-d'),
                'start_date' => $nextMonth->format('Y-m-d'),
                'end_date' => $endOfNextMonth->format('Y-m-d'),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        
        // Jadwal untuk dua bulan dari sekarang
        if (!in_array($twoMonthsLater->format('Y-m-d'), $existingDates)) {
            $scheduleData[] = [
                'internship_class_id' => $internshipClassId,
                'stase_id' => $stases[($staseIndex++) % $staseCount]->id,
                'date_schedule' => $twoMonthsLater->format('Y-m-d'),
                'start_date' => $twoMonthsLater->format('Y-m-d'),
                'end_date' => $endOfTwoMonths->format('Y-m-d'),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        
        // 6. Tambahkan beberapa jadwal per minggu untuk bulan ini (lebih detail)
        $weekStart = $today->copy()->startOfWeek();
        $weekEnd = $today->copy()->endOfWeek();
        
        // Jadwal minggu ini
        if (!in_array($weekStart->format('Y-m-d'), $existingDates)) {
            $scheduleData[] = [
                'internship_class_id' => $internshipClassId,
                'stase_id' => $stases[($staseIndex++) % $staseCount]->id,
                'date_schedule' => $weekStart->format('Y-m-d'),
                'start_date' => $weekStart->format('Y-m-d'),
                'end_date' => $weekEnd->format('Y-m-d'),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        
        // Jadwal minggu depan
        $nextWeekStart = $weekStart->copy()->addWeek();
        $nextWeekEnd = $weekEnd->copy()->addWeek();
        
        if (!in_array($nextWeekStart->format('Y-m-d'), $existingDates)) {
            $scheduleData[] = [
                'internship_class_id' => $internshipClassId,
                'stase_id' => $stases[($staseIndex++) % $staseCount]->id,
                'date_schedule' => $nextWeekStart->format('Y-m-d'),
                'start_date' => $nextWeekStart->format('Y-m-d'),
                'end_date' => $nextWeekEnd->format('Y-m-d'),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        
        // 7. Jadwal untuk beberapa hari tertentu (praktek harian)
        for ($i = 0; $i < 7; $i++) {
            $dayDate = $today->copy()->addDays($i);
            
            // Lewati hari Sabtu dan Minggu
            if ($dayDate->isWeekend()) {
                continue;
            }
            
            $dateStr = $dayDate->format('Y-m-d');
            if (!in_array($dateStr, $existingDates)) {
                $scheduleData[] = [
                    'internship_class_id' => $internshipClassId,
                    'stase_id' => $stases[rand(0, $staseCount - 1)]->id,
                    'date_schedule' => $dateStr,
                    'start_date' => $dateStr,
                    'end_date' => $dateStr, // Jadwal satu hari
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }
        
        // 8. Masukkan semua jadwal ke database
        $added = 0;
        foreach ($scheduleData as $schedule) {
            Schedule::create($schedule);
            $added++;
        }
        
        $this->command->info('Berhasil membuat ' . $added . ' jadwal baru untuk user ID 2 (tanpa menghapus jadwal lama).');
    }
}