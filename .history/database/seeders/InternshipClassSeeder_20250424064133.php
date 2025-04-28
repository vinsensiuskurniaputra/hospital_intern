<?php

namespace Database\Seeders;

use App\Models\InternshipClass;
use App\Models\ClassYear;
use Illuminate\Database\Seeder;

class InternshipClassSeeder extends Seeder
{
    public function run(): void
    {
        // First create class years
        $classYears = [
            ['class_year' => '2024/2025'],
            ['class_year' => '2025/2026'],
        ];

        foreach ($classYears as $year) {
            ClassYear::create($year);
        }

        // Then create internship classes
        $classes = [
            ['name' => 'FK-01', 'class_year_id' => 1],
            ['name' => 'FK-02', 'class_year_id' => 1],
            ['name' => 'FK-03', 'class_year_id' => 1],
            ['name' => 'FK-04', 'class_year_id' => 2],
            ['name' => 'FK-05', 'class_year_id' => 2],
            ['name' => 'FK-06', 'class_year_id' => 2],
        ];

        foreach ($classes as $class) {
            InternshipClass::create($class);
        }
    }
}
