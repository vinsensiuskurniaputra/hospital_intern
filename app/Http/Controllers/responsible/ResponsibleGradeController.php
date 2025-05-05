<?php

namespace App\Http\Controllers\Responsible;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\GradeComponent;
use App\Models\StudentGrade;
use App\Models\Schedule;
use App\Models\Stase;
use App\Models\InternshipClass;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ResponsibleGradeController extends Controller
{
    public function index()
    {
        // Get current PIC's stase with departement relation
        $stase = Stase::where('responsible_user_id', Auth::id())
                     ->with(['departement'])
                     ->first();
        
        if (!$stase) {
            return redirect()->back()->with('error', 'Tidak ditemukan stase yang Anda kelola.');
        }

        // Get active schedules for this stase with related data
        $schedules = Schedule::where('stase_id', $stase->id)
                           ->with([
                               'internshipClass.students.user',
                               'internshipClass.students.studyProgram',
                               'stase.departement'
                           ])
                           ->get();

        // Get pending students (without grades)
        $pendingStudents = [];
        foreach ($schedules as $schedule) {
            if ($schedule->internshipClass && $schedule->internshipClass->students) {
                foreach ($schedule->internshipClass->students as $student) {
                    // Check if student already has a grade for this schedule period
                    $hasGrade = StudentGrade::where('student_id', $student->id)
                                          ->where('stase_id', $stase->id)
                                          ->whereBetween('created_at', [$schedule->start_date, $schedule->end_date])
                                          ->exists();
                    
                    if (!$hasGrade) {
                        $pendingStudents[] = (object)[
                            'student' => $student,
                            'schedule' => $schedule,
                            'departement' => $stase->departement
                        ];
                    }
                }
            }
        }

        // Get grade components and descriptions
        $gradeComponents = GradeComponent::where('stase_id', $stase->id)
                                       ->get();

        // Get completed grades with related data
        $completedGrades = StudentGrade::with([
                'student.user',
                'stase.departement',
                'schedule'
            ])
            ->where('stase_id', $stase->id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($grade) {
                // Convert numeric grade to text representation
                if ($grade->avg_grades >= 85) {
                    $grade->grade_text = 'Sangat Baik (' . number_format($grade->avg_grades/20, 1) . '/5)';
                } elseif ($grade->avg_grades >= 75) {
                    $grade->grade_text = 'Baik (' . number_format($grade->avg_grades/20, 1) . '/5)';
                } else {
                    $grade->grade_text = 'Memuaskan (' . number_format($grade->avg_grades/20, 1) . '/5)';
                }
                return $grade;
            });

        return view('pages.responsible.grades.index', compact('pendingStudents', 'gradeComponents', 'completedGrades'));
    }

    // ...rest of the controller remains the same...
}