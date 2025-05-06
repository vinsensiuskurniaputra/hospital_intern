<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\ResponsibleUser;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ResponsibleUserSeeder extends Seeder
{
    public function run(): void
    {
        // Create responsible users
        $responsibles = [
            [
                'name' => 'Dr. Anak',
                'email' => 'dr.anak@hospital.test',
                'password' => Hash::make('password123'),
            ],
            [
                'name' => 'Dr. Bedah',
                'email' => 'dr.bedah@hospital.test',
                'password' => Hash::make('password123'),
            ],
            [
                'name' => 'Dr. Gigi',
                'email' => 'dr.gigi@hospital.test',
                'password' => Hash::make('password123'),
            ],
            [
                'name' => 'Dr. Mata',
                'email' => 'dr.mata@hospital.test',
                'password' => Hash::make('password123'),
            ],
            [
                'name' => 'Dr. THT',
                'email' => 'dr.tht@hospital.test',
                'password' => Hash::make('password123'),
            ],
            [
                'name' => 'Dr. Saraf',
                'email' => 'dr.saraf@hospital.test',
                'password' => Hash::make('password123'),
            ],
        ];

        $instructorRole = Role::where('name', 'instructor')->first();

        foreach ($responsibles as $responsible) {
            // Create user first
            $user = User::create($responsible);
            
            // Attach instructor role
            $user->roles()->attach($instructorRole);
            
            // Create responsible user record
            ResponsibleUser::create([
                'user_id' => $user->id,
            ]);
        }
    }
}
