<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Student;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            // Academic structure
            ClassYearSeeder::class,
            CampusAndStudyProgramSeeder::class,
            
            // Base structure
            RoleSeeder::class,
            UserSeeder::class,
            MenuSeeder::class,
            
            
            // Hospital structure
            DepartementSeeder::class,
            ResponsibleUserSeeder::class,
            StaseSeeder::class,
            
            // Classes and components
            GradeComponentSeeder::class,
            InternshipClassSeeder::class,
            ScheduleSeeder::class,
        ]);

        // Create students
        $students = Student::factory()->count(10)->create();

        $studentRole = Role::where('name', 'student')->first();

        foreach ($students as $student) {
            $student->user->roles()->attach($studentRole);
        }

        // Seed data that depends on students
        $this->call([
            PresenceSessionSeeder::class,
            PresenceSeeder::class,
            AttendanceExcuseSeeder::class,
            StudentGradeSeeder::class,
            CertificateSeeder::class,
        ]);
    }
}
