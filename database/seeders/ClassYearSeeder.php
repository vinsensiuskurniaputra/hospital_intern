<?php

namespace Database\Seeders;

use App\Models\ClassYear;
use Illuminate\Database\Seeder;

class ClassYearSeeder extends Seeder
{
    public function run(): void
    {
        $years = [
            ['class_year' => '2022/2023'],
            ['class_year' => '2023/2024'],
            ['class_year' => '2024/2025'],
            ['class_year' => '2025/2026'],
        ];

        foreach ($years as $year) {
            ClassYear::create($year);
        }
    }
}