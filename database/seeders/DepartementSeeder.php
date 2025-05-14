<?php

namespace Database\Seeders;

use App\Models\Departement;
use Illuminate\Database\Seeder;

class DepartementSeeder extends Seeder
{
    public function run()
    {
        $departments = [
            ['name' => 'Keperawatan'],
            ['name' => 'Kedokteran'],
            ['name' => 'Departemen 3'],
            ['name' => 'Departemen 4'],
            // Tambahkan data sesuai kebutuhan
        ];

        foreach ($departments as $department) {
            Departement::create($department);
        }
    }
}