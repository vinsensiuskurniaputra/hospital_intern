<?php

namespace Database\Seeders;

use App\Models\Role;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\Student;
use Illuminate\Database\Seeder;
use Database\Seeders\MenuSeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\DepartementSeeder;
use Database\Seeders\StaseSeeder;
use Database\Seeders\InternshipClassSeeder;
use Database\Seeders\ResponsibleUserSeeder;
use Database\Seeders\ScheduleSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $this->call([
            RoleSeeder::class,
            UserSeeder::class, 
            MenuSeeder::class, 
            DepartementSeeder::class,
            StaseSeeder::class,
            InternshipClassSeeder::class,
            ResponsibleUserSeeder::class,
            ScheduleSeeder::class,
        ]);

        $students = Student::factory()->count(10)->create();

        $studentRole = Role::where('name', 'student')->first();

        foreach ($students as $student) {
            $student->user->roles()->attach($studentRole);
        }
    }
}
