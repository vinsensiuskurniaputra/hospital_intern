<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\Student; // Tambahkan import model Student jika diperlukan
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Membuat user admin
        $admin = User::create([
            'username' => 'admin',
            'name' => 'Administrator',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'), // Enkripsi password
            'photo_profile_url' => 'https://ui-avatars.com/api/?name=Admin',
        ]);

        // Ambil ID Role Admin
        $adminRole = Role::where('name', 'admin')->first();

        // Tambahkan role admin ke user
        $admin->roles()->attach($adminRole);

        // Membuat user student
        $student = User::create([
            'username' => 'student',
            'name' => 'Student User',
            'email' => 'student@example.com',
            'password' => bcrypt('password'), // Enkripsi password
            'photo_profile_url' => 'https://ui-avatars.com/api/?name=Student+User',
        ]);

        // Ambil ID Role Student
        $studentRole = Role::where('name', 'student')->first();

        // Buat role student jika belum ada
        if (!$studentRole) {
            $studentRole = Role::create([
                'name' => 'student',
                'display_name' => 'Student',
                'description' => 'Role for student users',
            ]);
        }

        // Tambahkan role student ke user
        $student->roles()->attach($studentRole);

        // Buat data mahasiswa jika model Student ada
        // Uncomment kode di bawah ini jika memiliki model Student
        try {
            Student::create([
                'user_id' => $student->id,
                'nim' => '123456789',
                'study_program_id' => 1, // Sesuaikan dengan ID program studi yang tersedia
                // Tambahkan field lain yang diperlukan untuk table students
            ]);
        } catch (\Exception $e) {
            // Tangani jika ada error, misalnya kolom yang diperlukan belum ada
            $this->command->info('Error creating student profile: ' . $e->getMessage());
        }
    }
}
