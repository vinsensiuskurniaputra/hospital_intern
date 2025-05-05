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
            ['class_year' => '2020'],
            ['class_year' => '2021'],
            ['class_year' => '2022'],
            ['class_year' => '2023'],
            ['class_year' => '2024'],
            ['class_year' => '2025'],
        ];

        foreach ($classYears as $year) {
            ClassYear::create($year);
        }

        // Then create internship classes
        $classes = [
            ['name' => 'FK-01', 'class_year_id' => 1, 'campus_id' => 1],
            ['name' => 'FK-02', 'class_year_id' => 2, 'campus_id' => 1],
            ['name' => 'FK-03', 'class_year_id' => 3, 'campus_id' => 2],
            ['name' => 'FK-04', 'class_year_id' => 4, 'campus_id' => 2],
            ['name' => 'FK-05', 'class_year_id' => 5, 'campus_id' => 3],
            ['name' => 'FK-06', 'class_year_id' => 6, 'campus_id' => 3],
        ];

        foreach ($classes as $class) {
            InternshipClass::create($class);
        }
    }
}
