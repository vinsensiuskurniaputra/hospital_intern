<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
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
    }
}
