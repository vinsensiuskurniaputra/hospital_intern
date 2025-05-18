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
                'username' => 'tirtamh',
                'name' => 'dr. Tirta Mandira Hudhi',
                'email' => 'tirta@hospital.test',
                'password' => Hash::make('password'),
            ],
            [
                'username' => 'dr.bedah',
                'name' => 'dr. Dion Haryadi',
                'email' => 'dionh@hospital.test',
                'password' => Hash::make('password'),
            ],
            [
                'username' => 'decsamh',
                'name' => 'dr. DECSA MEDIKA HERTANTO, Sp.PD-KGH',
                'email' => 'decsa@hospital.test',
                'password' => Hash::make('password'),
            ],
            [
                'username' => 'ahwisdak',
                'name' => 'dr. A.H. WISDA KUSUMA, Sp.U',
                'email' => 'wisda@hospital.test',
                'password' => Hash::make('password'),
            ],
            
        ];

        $instructorRole = Role::where('name', 'pic')->first();

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
