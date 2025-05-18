<?php

namespace Database\Seeders;

use App\Models\Certificate;
use App\Models\Student;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CertificateSeeder extends Seeder
{
    public function run(): void
    {
        // Only create certificates for students who have completed their program
        $students = Student::all();

        if ($students->isEmpty()) {
            echo "Warning: No students found. Run StudentSeeder first.\n";
            return;
        }

        foreach ($students as $student) {
            // 30% chance a student has a certificate (assuming not all have completed)
            if (rand(0, 10) > 7) {
                Certificate::create([
                    'student_id' => $student->id,
                    'kode' => 'CERT-' . strtoupper(Str::random(8)),
                    'certificate_url' => 'certificates/' . $student->id . '_' . time() . '.pdf'
                ]);
            }
        }
    }
}