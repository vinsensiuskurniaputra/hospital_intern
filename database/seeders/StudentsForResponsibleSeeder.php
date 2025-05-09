<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\User;
use App\Models\Stase;
use App\Models\Schedule;
use App\Models\StudentGrade;
use App\Models\InternshipClass;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class StudentsForResponsibleSeeder extends Seeder
{
    public function run(): void
    {
        // Mengambil stase milik user ID 3
        $stase = Stase::where('responsible_user_id', 3)->first();
        
        if (!$stase) {
            $this->command->error('Stase untuk user ID 3 tidak ditemukan.');
            return;
        }
        
        $this->command->info('Menambahkan mahasiswa yang dibimbing oleh PIC dengan user ID 3 (Stase: ' . $stase->name . ')');
        
        // Mendapatkan kelas internship yang sudah ada
        $internshipClass = InternshipClass::first();
        
        if (!$internshipClass) {
            $this->command->error('Kelas internship tidak ditemukan.');
            return;
        }
        
        // Periksa struktur tabel students untuk mengetahui kolom yang tersedia
        $columns = Schema::getColumnListing('students');
        $this->command->info('Kolom yang tersedia di tabel students: ' . implode(', ', $columns));
        
        // Membuat 25 mahasiswa yang dibimbing oleh PIC
        $existingStudentIds = [];
        $newStudentCount = 0;
        
        // 1. Buat jadwal untuk kelas internship dengan stase milik user ID 3
        $this->createScheduleIfNeeded($stase, $internshipClass);
        
        // 2. Tambahkan 20-25 mahasiswa ke kelas internship
        for ($i = 1; $i <= 25; $i++) {
            // Cek apakah sudah ada user dengan username tertentu
            $username = 'student_stase_' . $stase->id . '_' . $i;
            $existingUser = User::where('username', $username)->first();
            
            if (!$existingUser) {
                // Buat user baru
                $user = User::create([
                    'username' => $username,
                    'name' => 'Mahasiswa ' . $stase->name . ' ' . $i,
                    'email' => 'student' . $stase->id . '_' . $i . '@hospital.test',
                    'password' => Hash::make('password'),
                ]);
                
                // Tambahkan role student
                $studentRole = DB::table('roles')->where('name', 'student')->first();
                if ($studentRole) {
                    DB::table('user_role')->insert([
                        'user_id' => $user->id,
                        'role_id' => $studentRole->id
                    ]);
                }
                
                // Buat data mahasiswa sesuai dengan kolom yang tersedia
                $studentData = [
                    'user_id' => $user->id,
                    'study_program_id' => 1,
                    'internship_class_id' => $internshipClass->id,
                    'nim' => 'S' . str_pad($stase->id . $i, 8, '0', STR_PAD_LEFT),
                    'telp' => '08' . rand(1000000000, 9999999999),
                    'is_finished' => 0, // Gunakan is_finished daripada status
                ];
                
                $student = Student::create($studentData);
                
                $existingStudentIds[] = $student->id;
                $newStudentCount++;
            } else {
                // Jika user sudah ada, periksa apakah sudah ada data student
                $student = Student::where('user_id', $existingUser->id)->first();
                
                if ($student) {
                    // Update data student
                    $student->update([
                        'internship_class_id' => $internshipClass->id,
                    ]);
                    
                    $existingStudentIds[] = $student->id;
                } else {
                    // Buat data student baru sesuai dengan kolom yang tersedia
                    $studentData = [
                        'user_id' => $existingUser->id,
                        'study_program_id' => 1,
                        'internship_class_id' => $internshipClass->id,
                        'nim' => 'S' . str_pad($stase->id . $i, 8, '0', STR_PAD_LEFT),
                        'telp' => '08' . rand(1000000000, 9999999999),
                        'is_finished' => 0, // Gunakan is_finished daripada status
                    ];
                    
                    $student = Student::create($studentData);
                    
                    $existingStudentIds[] = $student->id;
                    $newStudentCount++;
                }
            }
        }
        
        $this->command->info('Berhasil menambahkan ' . $newStudentCount . ' data mahasiswa baru');
        
        // 3. Buat nilai untuk sebagian mahasiswa (agar sebagian belum dinilai)
        $this->createGradesForSomeStudents($stase, $existingStudentIds);
        
        $this->command->info('Selesai menambahkan data mahasiswa yang dibimbing');
    }
    
    private function createScheduleIfNeeded($stase, $internshipClass)
    {
        // Cek apakah sudah ada jadwal untuk stase dan kelas ini
        $schedule = Schedule::where('stase_id', $stase->id)
            ->where('internship_class_id', $internshipClass->id)
            ->first();
            
        if (!$schedule) {
            // Buat jadwal baru yang mencakup beberapa bulan
            $today = Carbon::now();
            $startDate = $today->copy()->subMonths(1);
            $endDate = $today->copy()->addMonths(2);
            
            Schedule::create([
                'stase_id' => $stase->id,
                'internship_class_id' => $internshipClass->id,
                'date_schedule' => $today->format('Y-m-d'),
                'start_date' => $startDate->format('Y-m-d'),
                'end_date' => $endDate->format('Y-m-d'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            $this->command->info('Jadwal baru dibuat untuk stase ' . $stase->name . ' dan kelas ' . $internshipClass->name);
        }
    }
    
    private function createGradesForSomeStudents($stase, $studentIds)
    {
        // Buat nilai untuk sekitar 60% mahasiswa saja (agar 40% belum dinilai)
        $studentsToGrade = array_slice($studentIds, 0, (int) (count($studentIds) * 0.6));
        
        $departmentId = $stase->departement_id;
        
        foreach ($studentsToGrade as $studentId) {
            // Periksa apakah sudah ada nilai untuk mahasiswa ini
            $existingGrade = StudentGrade::where('student_id', $studentId)
                ->where('stase_id', $stase->id)
                ->first();
                
            if (!$existingGrade) {
                StudentGrade::create([
                    'student_id' => $studentId,
                    'stase_id' => $stase->id,
                    'departement_id' => $departmentId,
                    'avg_grades' => rand(70, 95),
                ]);
            }
        }
        
        $this->command->info('Nilai telah dibuat untuk ' . count($studentsToGrade) . ' mahasiswa');
        $this->command->info((count($studentIds) - count($studentsToGrade)) . ' mahasiswa belum memiliki nilai');
    }
}