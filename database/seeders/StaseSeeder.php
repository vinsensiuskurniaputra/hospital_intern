<?php

namespace Database\Seeders;

use App\Models\Stase;
use Illuminate\Database\Seeder;

class StaseSeeder extends Seeder
{
    public function run(): void
    {
        $stases = [
            ['name' => 'Stase Umum', 'departement_id' => 1],
            ['name' => 'Stase Ortopedi', 'departement_id' => 1],
            ['name' => 'Stase Penyakit Dalam', 'departement_id' => 1],
            ['name' => 'Stase Urologi', 'departement_id' => 2],

        ];

        foreach ($stases as $stase) {
            Stase::create($stase);
        }
    }
}
