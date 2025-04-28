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
                'username' => 'dr.anak',
                'name' => 'Dr. Anak',
                'email' => 'dr.anak@hospital.test',
                'password' => Hash::make('password'),
            ],
            [
                'username' => 'dr.bedah',
                'name' => 'Dr. Bedah',
                'email' => 'dr.bedah@hospital.test',
                'password' => Hash::make('password'),
            ],
            [
                'username' => 'dr.gigi',
                'name' => 'Dr. Gigi',
                'email' => 'dr.gigi@hospital.test',
                'password' => Hash::make('password'),
            ],
            [
                'username' => 'dr.mata',
                'name' => 'Dr. Mata',
                'email' => 'dr.mata@hospital.test',
                'password' => Hash::make('password'),
            ],
            [
                'username' => 'dr.tht',
                'name' => 'Dr. THT',
                'email' => 'dr.tht@hospital.test',
                'password' => Hash::make('password'),
            ],
            [
                'username' => 'dr.saraf',
                'name' => 'Dr. Saraf',
                'email' => 'dr.saraf@hospital.test',
                'password' => Hash::make('password'),
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
