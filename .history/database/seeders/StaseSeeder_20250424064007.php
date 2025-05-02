<?php

namespace Database\Seeders;

use App\Models\Stase;
use Illuminate\Database\Seeder;

class StaseSeeder extends Seeder
{
    public function run(): void
    {
        $stases = [
            ['name' => 'Stase Anak', 'responsible_user_id' => 1],
            ['name' => 'Stase Bedah', 'responsible_user_id' => 2],
            ['name' => 'Stase Gigi', 'responsible_user_id' => 3],
            ['name' => 'Stase Mata', 'responsible_user_id' => 4],
            ['name' => 'Stase THT', 'responsible_user_id' => 5],
            ['name' => 'Stase Saraf', 'responsible_user_id' => 6],
        ];

        foreach ($stases as $stase) {
            Stase::create($stase);
        }
    }
}
