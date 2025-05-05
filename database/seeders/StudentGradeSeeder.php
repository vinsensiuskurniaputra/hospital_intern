<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\StudentGrade;
use App\Models\Stase;
use App\Models\Departement;
use Illuminate\Database\Seeder;

class StudentGradeSeeder extends Seeder
{
    public function run(): void
    {
        // Get all students, stases, and departments
        $students = Student::all();
        $stases = Stase::all();

        if ($students->isEmpty() || $stases->isEmpty()) {
            echo "Warning: Required data missing. Make sure Students, Stases, and Departments exist.\n";
            return;
        }

        foreach ($students as $student) {
            foreach ($stases as $stase) {
                // Not every student has grades in every stase
                if (rand(0, 10) > 1) { // 90% chance to have a grade
                    if (rand(0, 10) > 5) { // 50% chance to have grade in this department
                        StudentGrade::create([
                            'stase_id' => $stase->id,
                            'student_id' => $student->id,
                            'avg_grades' => rand(70, 100) // Random grade between 70-100
                        ]);
                    }
                }
            }
        }
    }
}