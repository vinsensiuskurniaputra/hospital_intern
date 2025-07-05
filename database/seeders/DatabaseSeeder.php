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
            // ClassYearSeeder::class,
            // CampusAndStudyProgramSeeder::class,
            
            // Base structure
            RoleSeeder::class,
            UserSeeder::class,
            MenuSeeder::class,
            
            
            // Hospital structure
            // DepartementSeeder::class,
            // ResponsibleUserSeeder::class,
            // StaseSeeder::class,
            
            // Classes and components
            // GradeComponentSeeder::class,
            // InternshipClassSeeder::class,
            // ScheduleSeeder::class,
            
            // // Notifications
            // NotificationSeeder::class,
        ]);

        // Create students
        // $students = Student::factory()->count(10)->create();

        // $studentRole = Role::where('name', 'student')->first();

        // foreach ($students as $student) {
        //     $student->user->roles()->attach($studentRole);
        // }

        // // Seed data that depends on students
        // $this->call([
        //     PresenceSessionSeeder::class,
        //     PresenceSeeder::class,
        //     AttendanceExcuseSeeder::class,
        //     StudentGradeSeeder::class,
        //     CertificateSeeder::class,
        // ]);

        // // Tambahkan seeder custom untuk user_id = 2
        // $this->call(CustomUserPresenceSeeder::class);

        // // Tambahkan seeder jadwal untuk user ID 2
        // $this->call(UserScheduleSeeder::class);

        // // Seeder untuk data dummy user responsible dengan ID 3
        // $this->call(ResponsibleDataSeeder::class);

        // $this->call(StudentsForResponsibleSeeder::class);
    }
}
