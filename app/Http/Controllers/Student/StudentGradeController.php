<?php

namespace App\Http\Controllers\Student;

use App\Models\Student;
use Barryvdh\DomPDF\PDF;
use App\Models\StudentGrade;
use App\Models\StudentComponentGrade;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class StudentGradeController extends Controller
{
    public function index()
    {
        // Get currently authenticated user's student record
        $student = Student::where('user_id', Auth::id())->first();
        
        // Initialize empty collection
        $grades = collect();
        
        if ($student) {
            // Get all stases with grades for this student
            $grades = StudentGrade::with([
                'stase', 
                'stase.responsibleUsers.user'
            ])
            ->where('student_id', $student->id)
            ->get();
            
            // Get all component grades for this student
            $componentGrades = StudentComponentGrade::with('gradeComponent')
                ->where('student_id', $student->id)
                ->get()
                ->groupBy('stase_id');
            
            // Merge component grades with main grades
            $grades = $grades->map(function($grade) use ($componentGrades) {
                $staseComponents = $componentGrades->get($grade->stase_id, collect())
                    ->keyBy('gradeComponent.name');
                
                $grade->skill_grade = $staseComponents->get('Keahlian')->value ?? null;
                $grade->professional_grade = $staseComponents->get('Profesionalisme')->value ?? null;
                $grade->communication_grade = $staseComponents->get('Komunikasi')->value ?? null;
                $grade->patient_management_grade = $staseComponents->get('Kemampuan Menangani Pasien')->value ?? null;
                
                return $grade;
            });
        }
        
        return view('pages.student.grades.index', compact('grades', 'student'));
    }
    
    public function exportPdf(PDF $pdfGenerator)
    {
        $student = Student::where('user_id', Auth::id())->first();
        $grades = collect();

        if ($student) {
            // Use same logic as index method to get complete grade data
            $grades = StudentGrade::with([
                'stase', 
                'stase.responsibleUsers', 
                'stase.responsibleUsers.user'
            ])
            ->where('student_id', $student->id)
            ->get()
            ->map(function($grade) use ($student) {
                // Get component grades for this stase
                $componentGrades = StudentComponentGrade::with('gradeComponent')
                    ->where('student_id', $student->id)
                    ->where('stase_id', $grade->stase_id)
                    ->get()
                    ->keyBy('gradeComponent.name');
                
                // Map component grades to specific columns
                $grade->skill_grade = $componentGrades->get('Keahlian')->value ?? null;
                $grade->professional_grade = $componentGrades->get('Profesionalisme')->value ?? null;
                $grade->communication_grade = $componentGrades->get('Komunikasi')->value ?? null;
                $grade->patient_management_grade = $componentGrades->get('Kemampuan Menangani Pasien')->value ?? null;
                
                // Calculate average if not already set
                if (!$grade->avg_grades) {
                    $validGrades = collect([
                        $grade->skill_grade,
                        $grade->professional_grade,
                        $grade->communication_grade,
                        $grade->patient_management_grade
                    ])->filter()->values();
                    
                    $grade->avg_grades = $validGrades->count() > 0 ? 
                        round($validGrades->avg(), 2) : null;
                }
                
                return $grade;
            });
        }

        // Load view PDF dan kirim data ke sana
        $pdf = $pdfGenerator->loadView('pages.student.grades.grade_template', [
            'student' => $student,
            'grades' => $grades,
            'generated_at' => now()->format('d M Y, H:i'),
        ])->setPaper('A4', 'landscape'); // Landscape untuk tabel yang lebar

        return $pdf->download('nilai_mahasiswa_'.$student->nim.'.pdf');
    }
}