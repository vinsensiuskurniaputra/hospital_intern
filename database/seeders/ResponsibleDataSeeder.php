<?php

namespace Database\Seeders;

use App\Models\Schedule;
use App\Models\Student;
use App\Models\Stase;
use App\Models\Notification;
use App\Models\Presence;
use App\Models\PresenceSession;
use App\Models\InternshipClass;
use App\Models\Departement;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ResponsibleDataSeeder extends Seeder
{
    public function run(): void
    {
        // Cek apakah user dengan ID 3 ada
        $responsibleUser = DB::table('users')->find(3);
        
        if (!$responsibleUser) {
            $this->command->error('User dengan ID 3 tidak ditemukan. Seeder dibatalkan.');
            return;
        }
        
        $this->command->info('Membuat data dummy untuk user ID 3 (PIC/Responsible)');
        
        // 1. Pastikan user memiliki data responsible_user
        $responsibleData = DB::table('responsible_users')->where('user_id', 3)->first();
        
        if (!$responsibleData) {
            $this->command->info('Membuat data responsible_user untuk user ID 3');
            DB::table('responsible_users')->insert([
                'user_id' => 3,
                'telp' => '08' . rand(1000000000, 9999999999),
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
        
        // 2. Buat atau pastikan stase untuk responsible user ini
        $responsibleUser = \App\Models\ResponsibleUser::where('user_id', 3)->first();
        $stase = null;

        if ($responsibleUser) {
            // Coba ambil stase yang sudah terkait dengan responsible user
            $stase = $responsibleUser->stases()->first();
        }

        if (!$stase) {
            $this->command->info('Membuat stase untuk user ID 3');
            
            // Cek departemen
            $departmentId = Departement::first()?->id;
            
            if (!$departmentId) {
                $departmentId = DB::table('departements')->insertGetId([
                    'name' => 'Departemen Neurologi',
                    'description' => 'Departemen spesialisasi neurologi',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
            
            $stase = Stase::create([
                'name' => 'Stase Neurologi',
                'departement_id' => $departmentId,
                'detail' => 'Stase untuk praktik neurologi'
            ]);
            
            // Hubungkan stase dengan responsible user melalui pivot table
            if ($responsibleUser) {
                $responsibleUser->stases()->attach($stase->id);
            } else {
                // Jika responsible user belum ada, buat dulu
                $responsibleId = DB::table('responsible_users')->insertGetId([
                    'user_id' => 3,
                    'telp' => '08' . rand(1000000000, 9999999999),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                
                DB::table('responsible_stase')->insert([
                    'responsible_user_id' => $responsibleId,
                    'stase_id' => $stase->id,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }

        $this->command->info('Menggunakan stase: ' . $stase->name);

        // 3. Buat notifikasi baru untuk user 3
        $this->command->info('Membuat notifikasi untuk user ID 3');
        $notifications = [
            [
                'user_id' => 3,
                'title' => 'Perubahan Jadwal Praktikum',
                'message' => 'Jadwal praktikum minggu depan diubah menjadi pukul 09:00 WIB karena ada kegiatan rumah sakit',
                'type' => 'warning',
                'is_read' => false,
                'icon' => 'bi bi-calendar-event',
                'created_at' => now()->subHours(2),
                'updated_at' => now()->subHours(2)
            ],
            [
                'user_id' => 3,
                'title' => 'Reminder Penilaian Mahasiswa',
                'message' => 'Ada 5 mahasiswa yang belum dinilai untuk stase ' . $stase->name . '. Mohon segera dinilai.',
                'type' => 'info',
                'is_read' => true,
                'icon' => 'bi bi-award',
                'created_at' => now()->subDays(1),
                'updated_at' => now()->subDays(1)
            ],
            [
                'user_id' => 3,
                'title' => 'Rapat Evaluasi Akhir Stase',
                'message' => 'Rapat evaluasi akan diadakan hari Jumat, 15 Mei 2025 pukul 13:00 WIB di Ruang Rapat Lt.3',
                'type' => 'info',
                'is_read' => false,
                'icon' => 'bi bi-people',
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2)
            ],
            [
                'user_id' => 3,
                'title' => 'Pengajuan Izin Mahasiswa',
                'message' => 'Mahasiswa Budi Santoso mengajukan izin tidak hadir pada tanggal 12 Mei 2025 karena sakit. Mohon verifikasi.',
                'type' => 'warning',
                'is_read' => false,
                'icon' => 'bi bi-envelope-exclamation',
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subDays(3)
            ]
        ];

        // Masukkan notifikasi
        foreach ($notifications as $notification) {
            Notification::create($notification);
        }
        
        // 4. Buat atau dapatkan kelas internship
        $internshipClass = InternshipClass::first();
        
        if (!$internshipClass) {
            $this->command->info('Membuat kelas internship baru');
            // Cek campus
            $campusId = DB::table('campuses')->first()?->id ?? 1;
            $classYearId = DB::table('class_years')->first()?->id ?? 1;
            
            // Buat jika belum ada
            if (!$campusId) {
                $campusId = DB::table('campuses')->insertGetId([
                    'name' => 'RS Pendidikan Utama',
                    'detail' => 'Rumah Sakit Pendidikan',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
            
            if (!$classYearId) {
                $classYearId = DB::table('class_years')->insertGetId([
                    'class_year' => '2024/2025',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
            
            $internshipClass = InternshipClass::create([
                'campus_id' => $campusId,
                'class_year_id' => $classYearId,
                'name' => 'FK-A1',
                'description' => 'Kelas Internship FK Batch A1'
            ]);
        }

        // 5. Buat jadwal dengan tanggal hari ini
        $today = Carbon::now();
        $tomorrow = $today->copy()->addDay();
        $dayAfterTomorrow = $today->copy()->addDays(2);
        $nextWeek = $today->copy()->addWeek();
        
        $this->command->info('Membuat jadwal untuk hari ini dan beberapa hari ke depan');
        
        // Buat jadwal hari ini (single day)
        $todaySchedule = $this->createScheduleIfNotOverlap(
            $stase, 
            $internshipClass,
            $today->format('Y-m-d'),
            $today->format('Y-m-d')
        );

        if ($todaySchedule) {
            // Buat sesi presensi untuk jadwal hari ini
            $this->createPresenceSessions($todaySchedule, $today, [
                ['start' => '08:00:00', 'end' => '10:30:00'],
                ['start' => '10:45:00', 'end' => '12:30:00'],
            ]);
        }
        
        // Buat jadwal besok (single day)
        $tomorrowSchedule = $this->createScheduleIfNotOverlap(
            $stase, 
            $internshipClass,
            $tomorrow->format('Y-m-d'),
            $tomorrow->format('Y-m-d')
        );

        if ($tomorrowSchedule) {
            // Buat sesi presensi untuk jadwal besok
            $this->createPresenceSessions($tomorrowSchedule, $tomorrow, [
                ['start' => '07:30:00', 'end' => '10:00:00'],
                ['start' => '10:15:00', 'end' => '12:00:00'],
                ['start' => '13:00:00', 'end' => '15:00:00'],
            ]);
        }
        
        // Lusa (single day)
        $dayAfterSchedule = $this->createScheduleIfNotOverlap(
            $stase, 
            $internshipClass,
            $dayAfterTomorrow->format('Y-m-d'),
            $dayAfterTomorrow->format('Y-m-d')
        );

        if ($dayAfterSchedule) {
            $this->createPresenceSessions($dayAfterSchedule, $dayAfterTomorrow, [
                ['start' => '08:30:00', 'end' => '11:00:00'],
                ['start' => '13:30:00', 'end' => '16:00:00'],
            ]);
        }
        
        // Buat jadwal minggu depan (range 5 hari)
        $weekRangeStart = $nextWeek->copy();
        $weekRangeEnd = $nextWeek->copy()->addDays(4); // 5 hari (Senin-Jumat)

        $weekSchedule = $this->createScheduleIfNotOverlap(
            $stase, 
            $internshipClass,
            $weekRangeStart->format('Y-m-d'),
            $weekRangeEnd->format('Y-m-d')
        );
        
        // 6. Buat atau ambil mahasiswa untuk kehadiran dan penilaian
        $students = Student::take(10)->get();
        
        if ($students->isEmpty()) {
            $this->command->info('Tidak menemukan mahasiswa. Membuat mahasiswa baru.');
            // Buat mahasiswa dummy jika belum ada
            for ($i = 1; $i <= 10; $i++) {
                Student::create([
                    'user_id' => $i + 10, // Untuk menghindari konflik dengan user ID yang sudah ada
                    'mentorship_class_id' => 1,
                    'internship_class_id' => $internshipClass->id,
                    'study_program_id' => 1,
                    'nim' => 'S' . date('y') . str_pad($i, 6, '0', STR_PAD_LEFT),
                    'age' => rand(19, 25),
                    'status' => 'active'
                ]);
            }
            
            $students = Student::take(10)->get();
        }
        
        // 7. Buat data kehadiran untuk chart
        $this->command->info('Membuat data kehadiran untuk 7 bulan terakhir');
        $this->generateAttendanceData($stase, $students);
        
        $this->command->info('Data dummy untuk user ID 3 berhasil dibuat.');
    }
    
    private function createPresenceSessions($schedule, $date, $timeSlots)
    {
        foreach ($timeSlots as $index => $timeSlot) {
            PresenceSession::create([
                'schedule_id' => $schedule->id,
                'token' => 'TOKEN' . strtoupper(Str::random(6)),
                'date' => $date->toDateString(),
                'expiration_time' => $date->copy()->endOfDay(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
    
    /**
     * Generate attendance data for historical reporting
     */
    private function generateAttendanceData($stase, $students)
    {
        // Generate data untuk 7 bulan terakhir
        for ($month = 6; $month >= 0; $month--) {
            $currentMonth = Carbon::now()->subMonths($month);
            $totalDaysInMonth = $currentMonth->daysInMonth;
            
            $startDate = $currentMonth->copy()->startOfMonth();
            $endDate = $currentMonth->copy()->endOfMonth();
            
            // PERUBAHAN: Buat sesi kehadiran dan jadwal untuk bulan ini jika belum ada
            // Cari berdasarkan start_date dan end_date, bukan date_schedule
            $monthlySchedule = Schedule::where('stase_id', $stase->id)
                ->where('start_date', '<=', $startDate->toDateString())
                ->where('end_date', '>=', $startDate->toDateString())
                ->first();
                
            if (!$monthlySchedule) {
                $monthlySchedule = Schedule::create([
                    'internship_class_id' => InternshipClass::first()->id,
                    'stase_id' => $stase->id,
                    // HAPUS: 'date_schedule' => $startDate->toDateString(),
                    'start_date' => $startDate->toDateString(),
                    'end_date' => $endDate->toDateString(),
                    'created_at' => $startDate,
                    'updated_at' => $startDate,
                ]);
            }
            
            // Untuk setiap bulan, buat data kehadiran berdasarkan pola
            $daysToCreate = min(20, $totalDaysInMonth); // Maksimal 20 hari/bulan
            $presencePattern = $this->getMonthlyPresencePattern($month);
            
            for ($day = 1; $day <= $daysToCreate; $day++) {
                $currentDate = $startDate->copy()->addDays($day - 1);
                
                // Lewati akhir pekan
                if ($currentDate->isWeekend()) {
                    continue;
                }
                
                // Buat sesi untuk hari ini
                $session = PresenceSession::where('date', $currentDate->toDateString())
                    ->whereHas('schedule', function($query) use ($stase) {
                        $query->where('stase_id', $stase->id);
                    })
                    ->first();
                    
                if (!$session) {
                    $session = PresenceSession::create([
                        'schedule_id' => $monthlySchedule->id,
                        'token' => 'HIST' . strtoupper(Str::random(6)),
                        'date' => $currentDate->toDateString(),
                        'expiration_time' => $currentDate->copy()->endOfDay(),
                        'created_at' => $currentDate,
                        'updated_at' => $currentDate,
                    ]);
                }
                
                // Buat kehadiran untuk mahasiswa berdasarkan pola
                foreach ($students as $student) {
                    // Cek apakah sudah ada kehadiran untuk mahasiswa ini di sesi ini
                    $existingPresence = Presence::where('student_id', $student->id)
                        ->where('presence_sessions_id', $session->id)
                        ->exists();
                        
                    if (!$existingPresence) {
                        $randomStatus = $this->getRandomStatus($presencePattern);
                        
                        Presence::create([
                            'student_id' => $student->id,
                            'presence_sessions_id' => $session->id,
                            'date_entry' => $currentDate->toDateString(),
                            'check_in' => $randomStatus == 'present' ? '08:' . rand(0, 30) . ':00' : '00:00:00',
                            'check_out' => $randomStatus == 'present' ? '16:' . rand(0, 30) . ':00' : '00:00:00',
                            'status' => $randomStatus,
                            'created_at' => $currentDate,
                            'updated_at' => $currentDate
                        ]);
                    }
                }
            }
        }
    }
    
    private function getMonthlyPresencePattern($monthsAgo)
    {
        // Pola kehadiran berbeda tiap bulan untuk membuat grafik yang menarik
        switch ($monthsAgo) {
            case 0: // Bulan ini
                return ['present' => 90, 'sick' => 5, 'absent' => 5]; // 90% hadir
            case 1: // Bulan lalu
                return ['present' => 80, 'sick' => 10, 'absent' => 10]; // 80% hadir
            case 2: // 2 bulan lalu
                return ['present' => 75, 'sick' => 15, 'absent' => 10]; // 75% hadir
            case 3: // 3 bulan lalu
                return ['present' => 85, 'sick' => 10, 'absent' => 5]; // 85% hadir
            case 4: // 4 bulan lalu
                return ['present' => 70, 'sick' => 20, 'absent' => 10]; // 70% hadir
            case 5: // 5 bulan lalu
                return ['present' => 60, 'sick' => 25, 'absent' => 15]; // 60% hadir
            case 6: // 6 bulan lalu
                return ['present' => 50, 'sick' => 30, 'absent' => 20]; // 50% hadir
            default:
                return ['present' => 80, 'sick' => 10, 'absent' => 10];
        }
    }
    
    private function getRandomStatus($pattern)
    {
        $random = rand(1, 100);
        $presentThreshold = $pattern['present'];
        $sickThreshold = $presentThreshold + $pattern['sick'];
        
        if ($random <= $presentThreshold) {
            return 'present';
        } elseif ($random <= $sickThreshold) {
            return 'sick';
        } else {
            return 'absent';
        }
    }
    
    private function createScheduleIfNotOverlap($stase, $internshipClass, $startDate, $endDate) 
    {
        // Cek apakah sudah ada jadwal yang overlap dengan tanggal ini
        $existingOverlap = Schedule::where('stase_id', $stase->id)
            ->where('internship_class_id', $internshipClass->id)
            ->where(function($query) use ($startDate, $endDate) {
                // Cek overlap
                $query->where(function($q) use ($startDate, $endDate) {
                    // Start date di antara range yang ada
                    $q->where('start_date', '<=', $startDate)
                      ->where('end_date', '>=', $startDate);
                })->orWhere(function($q) use ($startDate, $endDate) {
                    // End date di antara range yang ada
                    $q->where('start_date', '<=', $endDate)
                      ->where('end_date', '>=', $endDate);
                })->orWhere(function($q) use ($startDate, $endDate) {
                    // Range baru sepenuhnya mencakup range yang ada
                    $q->where('start_date', '>=', $startDate)
                      ->where('end_date', '<=', $endDate);
                });
            })
            ->exists();
            
        if (!$existingOverlap) {
            return Schedule::create([
                'internship_class_id' => $internshipClass->id,
                'stase_id' => $stase->id,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        
        return null;
    }
}