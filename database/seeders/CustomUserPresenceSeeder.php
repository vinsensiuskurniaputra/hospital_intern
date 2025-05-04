<?php

namespace Database\Seeders;

use App\Models\Presence;
use App\Models\PresenceSession;
use App\Models\Schedule;
use App\Models\Student;
use App\Models\Stase;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CustomUserPresenceSeeder extends Seeder
{
    public function run(): void
    {
        // Gunakan pendekatan yang lebih sederhana dan kompatibel
        // Kita asumsikan check_in dan check_out tidak nullable, jadi selalu gunakan default time
        $defaultCheckTime = '00:00:00';
        
        echo "Using default time for non-present status: " . $defaultCheckTime . "\n";
                            
        // Cek apakah ada student dengan user_id = 2
        $student = Student::where('user_id', 2)->first();
        
        if (!$student) {
            echo "Student dengan user_id 2 tidak ditemukan. Membuat data student...\n";
            
            // Jika tidak ada, buat data student baru dengan user_id = 2
            $student = Student::create([
                'user_id' => 2,
                'mentorship_class_id' => 1, // Sesuaikan dengan ID yang ada
                'study_program_id' => 1, // Sesuaikan dengan ID yang ada
                'nim' => 'S02' . rand(100000, 999999),
                'age' => rand(19, 25),
                'status' => 'active'
            ]);
        }
        
        echo "Generating presences for student: " . $student->nim . " (ID: " . $student->id . ")\n";
        
        // Hapus presensi yang sudah ada untuk student ini (reset)
        Presence::where('student_id', $student->id)->delete();
        echo "Deleted existing presence records for this student.\n";
        
        // Generasi data untuk 12 bulan terakhir (1 tahun penuh)
        $startDate = Carbon::now()->subYear();
        $endDate = Carbon::now();
        
        // Membuat jadwal dummy untuk berbagai stase jika belum ada
        $schedules = $this->prepareSchedules();
        
        // Buat distribusi status kehadiran yang realistis
        $statuses = $this->getStatusDistribution();
        
        // Cari atau buat sesi presensi untuk 1 tahun terakhir
        echo "Generating presence sessions and records for the past year...\n";
        
        $totalCreated = 0;
        $currentDate = $startDate->copy();
        
        // Pembagian tahun akademik ke dalam beberapa periode stase
        $stasePeriods = $this->generateStasePeriods($startDate, $endDate, $schedules);
        
        while ($currentDate->lte($endDate)) {
            // Cek apakah hari ini termasuk dalam period stase yang aktif
            $activeStase = $this->getActiveStase($currentDate, $stasePeriods);
            
            if ($activeStase && $this->isWorkingDay($currentDate)) {
                $schedule = $activeStase['schedule'];
                
                // Cek apakah sudah ada sesi untuk tanggal ini
                $existingSessions = PresenceSession::whereDate('date', $currentDate->format('Y-m-d'))->get();
                
                // Jika tidak ada sesi, buat 3-4 sesi untuk hari ini (rotasi pagi, siang, kadang malam)
                $daySessions = [];
                if ($existingSessions->isEmpty()) {
                    $sessionCount = rand(3, 4); // Beberapa hari memiliki 4 sesi (tambahan sore/malam)
                    
                    // Pagi
                    $daySessions[] = PresenceSession::create([
                        'schedule_id' => $schedule->id,
                        'token' => 'AM' . strtoupper(substr(md5(rand()), 0, 6)),
                        'date' => $currentDate->format('Y-m-d'),
                        'start_time' => '07:30:00',
                        'end_time' => '10:30:00',
                        'created_at' => $currentDate,
                        'updated_at' => $currentDate,
                    ]);
                    
                    // Siang
                    $daySessions[] = PresenceSession::create([
                        'schedule_id' => $schedule->id,
                        'token' => 'PM1' . strtoupper(substr(md5(rand()), 0, 6)),
                        'date' => $currentDate->format('Y-m-d'),
                        'start_time' => '10:45:00',
                        'end_time' => '12:30:00',
                        'created_at' => $currentDate,
                        'updated_at' => $currentDate,
                    ]);
                    
                    // Siang 2
                    $daySessions[] = PresenceSession::create([
                        'schedule_id' => $schedule->id,
                        'token' => 'PM2' . strtoupper(substr(md5(rand()), 0, 6)),
                        'date' => $currentDate->format('Y-m-d'),
                        'start_time' => '13:30:00',
                        'end_time' => '15:30:00',
                        'created_at' => $currentDate,
                        'updated_at' => $currentDate,
                    ]);
                    
                    // Sore/Malam (opsional)
                    if ($sessionCount == 4) {
                        $daySessions[] = PresenceSession::create([
                            'schedule_id' => $schedule->id,
                            'token' => 'EVE' . strtoupper(substr(md5(rand()), 0, 6)),
                            'date' => $currentDate->format('Y-m-d'),
                            'start_time' => '16:00:00',
                            'end_time' => '18:00:00',
                            'created_at' => $currentDate,
                            'updated_at' => $currentDate,
                        ]);
                    }
                } else {
                    $daySessions = $existingSessions->toArray();
                }
                
                // Generate status untuk hari ini (sama untuk semua sesi dalam sehari)
                $dayStatus = $this->getDayStatus($currentDate, $student->id, $statuses);
                
                // Buat presensi untuk setiap sesi di hari ini
                foreach ($daySessions as $session) {
                    $sessionId = is_array($session) ? $session['id'] : $session->id;
                    $sessionDate = is_array($session) ? Carbon::parse($session['date']) : Carbon::parse($session->date);
                    $sessionStartTime = is_array($session) ? Carbon::parse($session['start_time']) : Carbon::parse($session->start_time);
                    $sessionEndTime = is_array($session) ? Carbon::parse($session['end_time']) : Carbon::parse($session->end_time);
                    
                    // Set check-in/check-out berdasarkan status
                    list($checkIn, $checkOut) = $this->generateAttendanceTimes($dayStatus, $sessionStartTime, $sessionEndTime, $defaultCheckTime);
                    
                    try {
                        // Buat record presensi
                        Presence::create([
                            'student_id' => $student->id,
                            'presence_sessions_id' => $sessionId,
                            'date_entry' => $sessionDate->format('Y-m-d'),
                            'check_in' => $checkIn,
                            'check_out' => $checkOut,
                            'status' => $dayStatus,
                            'created_at' => $sessionDate,
                            'updated_at' => $sessionDate
                        ]);
                        
                        $totalCreated++;
                    } catch (\Exception $e) {
                        echo "Error creating presence record: " . $e->getMessage() . "\n";
                    }
                }
            }
            
            $currentDate->addDay();
        }
        
        echo "Successfully created $totalCreated presence records for student with user_id = 2\n";
    }
    
    // Menentukan apakah suatu hari adalah hari kerja
    private function isWorkingDay(Carbon $date)
    {
        // Hari kerja: Senin-Jumat, kadang Sabtu (30% kemungkinan)
        if ($date->dayOfWeek >= Carbon::MONDAY && $date->dayOfWeek <= Carbon::FRIDAY) {
            return true;
        }
        
        // 30% kemungkinan ada kelas di Sabtu
        if ($date->dayOfWeek == Carbon::SATURDAY && rand(1, 100) <= 30) {
            return true;
        }
        
        return false;
    }
    
    // Menentukan status kehadiran untuk sehari penuh
    private function getDayStatus(Carbon $date, $studentId, $statusDistribution)
    {
        $dayOfWeek = $date->dayOfWeek;
        $weekOfMonth = ceil($date->day / 7);
        $month = $date->month;
        
        // Mempertimbangkan pola historis kehadiran
        $recentAbsences = Presence::where('student_id', $studentId)
            ->whereIn('status', ['sick', 'absent'])
            ->whereDate('date_entry', '>=', $date->copy()->subDays(14))
            ->whereDate('date_entry', '<', $date->format('Y-m-d'))
            ->count();
        
        // Jika baru saja banyak absen, kemungkinan besar hadir
        if ($recentAbsences >= 3) {
            return rand(1, 100) <= 90 ? 'present' : $statusDistribution[array_rand($statusDistribution)];
        }
        
        // Pola berdasarkan waktu dalam tahun akademik
        
        // Periode liburan (hampir tidak pernah masuk)
        if (($month == 12 && $date->day >= 24) || ($month == 1 && $date->day <= 5) || 
            ($month == 7 && $date->day >= 15 && $date->day <= 31)) {
            return rand(1, 100) <= 70 ? 'absent' : 'present';
        }
        
        // Periode ujian (hampir selalu masuk)
        if (($month == 6 && $date->day >= 10 && $date->day <= 25) || 
            ($month == 12 && $date->day >= 1 && $date->day <= 15)) {
            return rand(1, 100) <= 95 ? 'present' : 'sick';
        }
        
        // Minggu pertama bulan selalu rajin
        if ($weekOfMonth == 1) {
            return rand(1, 100) <= 90 ? 'present' : 'sick';
        }
        
        // Minggu terakhir kadang bolos, apalagi Jumat
        if ($weekOfMonth >= 3 && $dayOfWeek == Carbon::FRIDAY) {
            return rand(1, 100) > 65 ? 'present' : (rand(1, 100) > 50 ? 'absent' : 'sick');
        }
        
        // Kadang sakit beberapa hari berturut-turut
        $yesterdaySick = Presence::where('student_id', $studentId)
            ->whereDate('date_entry', $date->copy()->subDay()->format('Y-m-d'))
            ->where('status', 'sick')
            ->exists();
            
        if ($yesterdaySick) {
            return rand(1, 100) > 40 ? 'sick' : 'present';
        }
        
        // Standar distribusi biasa
        return $statusDistribution[array_rand($statusDistribution)];
    }
    
    // METODE INI DISEDERHANAKAN - tidak lagi menggunakan nullable check
    private function generateAttendanceTimes($status, $startTime, $endTime, $defaultTime)
    {
        if ($status == 'present') {
            // Variasi check-in
            $punctuality = rand(1, 100);
            
            if ($punctuality > 95) {
                // Sangat telat (15-25 menit)
                $checkInVariance = rand(15, 25);
            } else if ($punctuality > 80) {
                // Telat (5-15 menit)
                $checkInVariance = rand(5, 15);
            } else if ($punctuality > 50) {
                // Tepat waktu (-5 sampai +5 menit)
                $checkInVariance = rand(-5, 5);
            } else {
                // Datang awal (-15 sampai -5 menit)
                $checkInVariance = rand(-15, -5);
            }
            
            $checkIn = $startTime->copy()->addMinutes($checkInVariance)->format('H:i:s');
            
            // Variasi check-out
            $dedication = rand(1, 100);
            if ($dedication > 90) {
                // Lembur lama (15-30 menit)
                $checkOutVariance = rand(15, 30);
            } else if ($dedication > 75) {
                // Lembur sedang (5-15 menit)
                $checkOutVariance = rand(5, 15);
            } else if ($dedication > 40) {
                // Tepat waktu (-5 sampai +5 menit)
                $checkOutVariance = rand(-5, 5);
            } else if ($dedication > 20) {
                // Pulang cepat (-15 sampai -5 menit)
                $checkOutVariance = rand(-15, -5);
            } else {
                // Pulang sangat cepat (-25 sampai -15 menit)
                $checkOutVariance = rand(-25, -15);
            }
            
            $checkOut = $endTime->copy()->addMinutes($checkOutVariance)->format('H:i:s');
            
            return [$checkIn, $checkOut];
        }
        
        // Jika tidak hadir atau sakit, selalu gunakan waktu default
        return [$defaultTime, $defaultTime];
    }
    
    // Membuat atau mengambil jadwal yang tersedia
    private function prepareSchedules()
    {
        $existingSchedules = Schedule::count();
        $schedules = [];
        
        if ($existingSchedules == 0) {
            echo "Creating sample schedules for different stases...\n";
            
            // Buat beberapa stase terlebih dahulu jika tidak ada
            $stases = Stase::count();
            $staseIds = [];
            
            if ($stases == 0) {
                $staseNames = [
                    'Bedah Umum', 'Penyakit Dalam', 'Anak', 
                    'Obstetri & Ginekologi', 'Kardiologi', 'Neurologi',
                    'THT', 'Mata', 'Kulit & Kelamin'
                ];
                
                foreach ($staseNames as $name) {
                    $staseId = DB::table('stases')->insertGetId([
                        'name' => $name,
                        'departement_id' => 1,
                        'responsible_user_id' => 1,
                        'detail' => 'Detail untuk ' . $name,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    $staseIds[] = $staseId;
                }
            } else {
                $staseIds = Stase::pluck('id')->toArray();
            }
            
            // Buat jadwal untuk setiap stase
            foreach ($staseIds as $staseId) {
                $schedule = Schedule::create([
                    'stase_id' => $staseId,
                    'internship_class_id' => 1,
                    'date' => Carbon::now()->format('Y-m-d'),
                    'start_date' => Carbon::now()->subYear()->format('Y-m-d'),
                    'end_date' => Carbon::now()->addYear()->format('Y-m-d'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $schedules[] = $schedule;
            }
        } else {
            $schedules = Schedule::all()->toArray();
        }
        
        return $schedules;
    }
    
    // Membagi tahun dalam periode stase (rotasi klinik)
    private function generateStasePeriods($startDate, $endDate, $schedules)
    {
        $stasePeriods = [];
        $totalDays = $startDate->diffInDays($endDate);
        $scheduleCount = count($schedules);
        
        // Bagi tahun menjadi beberapa periode stase (rotasi)
        // Setiap stase berlangsung sekitar 4-10 minggu
        $currentDate = $startDate->copy();
        $scheduleIndex = 0;
        
        while ($currentDate->lte($endDate)) {
            $schedule = is_array($schedules[$scheduleIndex]) ? 
                Schedule::find($schedules[$scheduleIndex]['id']) : 
                $schedules[$scheduleIndex];
                
            // Periode stase antara 4-10 minggu
            $periodLength = rand(28, 70);
            $periodEnd = $currentDate->copy()->addDays($periodLength);
            
            // Jangan melewati tanggal akhir
            if ($periodEnd->gt($endDate)) {
                $periodEnd = $endDate->copy();
            }
            
            $stasePeriods[] = [
                'start' => $currentDate->copy(),
                'end' => $periodEnd->copy(),
                'schedule' => $schedule
            ];
            
            // Pindah ke tanggal dan stase berikutnya
            $currentDate = $periodEnd->copy()->addDays(1);
            $scheduleIndex = ($scheduleIndex + 1) % $scheduleCount;
        }
        
        return $stasePeriods;
    }
    
    // Mendapatkan stase aktif untuk suatu tanggal
    private function getActiveStase($date, $periods)
    {
        foreach ($periods as $period) {
            if ($date->greaterThanOrEqualTo($period['start']) && $date->lessThanOrEqualTo($period['end'])) {
                return $period;
            }
        }
        return null;
    }
    
    // Membuat distribusi status presensi dengan bias ke "hadir"
    private function getStatusDistribution()
    {
        return [
            'present', 'present', 'present', 'present', 'present', 
            'present', 'present', 'present', 'present', 'present', 
            'present', 'present', 'present', 'present', 'present', 
            'sick', 'sick', 'absent', 'absent', 'absent'
        ];
    }
}