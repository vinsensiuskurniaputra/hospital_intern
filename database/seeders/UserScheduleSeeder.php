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
    /**
     * Run the database seeds.
     */
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
        
        // PERUBAHAN: Gunakan start_date dan end_date untuk menentukan existing dates
        $existingSchedules = Schedule::where('internship_class_id', $internshipClassId)
            ->select('start_date', 'end_date')
            ->get();
            
        $existingDates = [];
        foreach ($existingSchedules as $schedule) {
            $start = Carbon::parse($schedule->start_date);
            $end = Carbon::parse($schedule->end_date);
            $current = $start->copy();
            
            // Tambahkan semua tanggal dalam rentang ke array
            while ($current->lte($end)) {
                $existingDates[] = $current->format('Y-m-d');
                $current->addDay();
            }
        }
        $existingDates = array_unique($existingDates);
        
        $this->command->info('Ditemukan ' . count($existingDates) . ' tanggal jadwal yang sudah ada');
        
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
                $stase = Stase::create([
                    'name' => $name,
                    'departement_id' => $departmentId,
                    'detail' => 'Stase ' . $name,
                ]);
                
                // Tambahkan relasi dengan responsible user
                DB::table('responsible_stase')->insert([
                    'responsible_user_id' => 1, // Sesuaikan dengan ID dari responsible_users, bukan users
                    'stase_id' => $stase->id,
                    'created_at' => now(),
                    'updated_at' => now()
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
        $addedScheduleCount = 0;
        
        // Jadwal untuk periode saat ini
        $currentStaseId = $stases[($staseIndex++) % $staseCount]->id;
        if (!$this->checkScheduleOverlap($currentStaseId, $internshipClassId, $currentMonth->format('Y-m-d'), $endOfCurrentMonth->format('Y-m-d'))) {
            Schedule::create([
                'internship_class_id' => $internshipClassId,
                'stase_id' => $currentStaseId,
                'start_date' => $currentMonth->format('Y-m-d'),
                'end_date' => $endOfCurrentMonth->format('Y-m-d'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $addedScheduleCount++;
        }
        
        // Jadwal untuk bulan depan
        $nextMonthStaseId = $stases[($staseIndex++) % $staseCount]->id;
        if (!$this->checkScheduleOverlap($nextMonthStaseId, $internshipClassId, $nextMonth->format('Y-m-d'), $endOfNextMonth->format('Y-m-d'))) {
            Schedule::create([
                'internship_class_id' => $internshipClassId,
                'stase_id' => $nextMonthStaseId,
                'start_date' => $nextMonth->format('Y-m-d'),
                'end_date' => $endOfNextMonth->format('Y-m-d'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $addedScheduleCount++;
        }
        
        // Jadwal untuk dua bulan dari sekarang
        $twoMonthStaseId = $stases[($staseIndex++) % $staseCount]->id;
        if (!$this->checkScheduleOverlap($twoMonthStaseId, $internshipClassId, $twoMonthsLater->format('Y-m-d'), $endOfTwoMonths->format('Y-m-d'))) {
            Schedule::create([
                'internship_class_id' => $internshipClassId,
                'stase_id' => $twoMonthStaseId,
                'start_date' => $twoMonthsLater->format('Y-m-d'),
                'end_date' => $endOfTwoMonths->format('Y-m-d'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $addedScheduleCount++;
        }
        
        // Informasi hasil
        $this->command->info('Berhasil membuat ' . $addedScheduleCount . ' jadwal baru untuk user ID 2 (tanpa duplikasi).');
        
        // 6. Tambahkan beberapa jadwal per minggu untuk bulan ini (lebih detail)
        $weekStart = $today->copy()->startOfWeek();
        $weekEnd = $today->copy()->endOfWeek();
        
        // Jadwal minggu ini
        if (!in_array($weekStart->format('Y-m-d'), $existingDates)) {
            $scheduleData[] = [
                'internship_class_id' => $internshipClassId,
                'stase_id' => $stases[($staseIndex++) % $staseCount]->id,
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
            // Gunakan checkScheduleOverlap untuk memastikan tidak ada duplikasi sebelum menambahkan
            if (!$this->checkScheduleOverlap($schedule['stase_id'], $schedule['internship_class_id'], 
                                            $schedule['start_date'], $schedule['end_date'])) {
                Schedule::create($schedule);
                $added++;
            }
        }
        
        $this->command->info('Berhasil membuat ' . $added . ' jadwal baru untuk user ID 2 (tanpa menghapus jadwal lama).');
    }
    
    private function checkScheduleOverlap($staseId, $internshipClassId, $startDate, $endDate)
    {
        return Schedule::where('stase_id', $staseId)
            ->where('internship_class_id', $internshipClassId)
            ->where(function($query) use ($startDate, $endDate) {
                // Cek overlap dengan range yang sudah ada
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
    }
}