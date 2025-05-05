<?php

namespace Database\Seeders;

use App\Models\Stase;
use Illuminate\Database\Seeder;

class StaseSeeder extends Seeder
{
    public function run(): void
    {
        $stases = [
            ['name' => 'Stase Umum', 'responsible_user_id' => 1, 'departement_id' => 1],
            ['name' => 'Stase Ortopedi', 'responsible_user_id' => 2, 'departement_id' => 1],
            ['name' => 'Stase Penyakit Dalam', 'responsible_user_id' => 3, 'departement_id' => 1],
            ['name' => 'Stase Urologi', 'responsible_user_id' => 4, 'departement_id' => 2],

        ];

        foreach ($stases as $stase) {
            Stase::create($stase);
        }
    }
}
