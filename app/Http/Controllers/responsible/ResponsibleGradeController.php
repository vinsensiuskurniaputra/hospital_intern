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
use App\Models\Departement;
use App\Models\ResponsibleUser;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ResponsibleGradeController extends Controller
{
    public function index(Request $request)
    {
        // Get current responsible user
        $user = Auth::user();
        
        // Get stase assigned to this responsible user
        $responsibleUser = ResponsibleUser::where('user_id', $user->id)->first();
        if (!$responsibleUser) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses sebagai penanggung jawab.');
        }
        
        $stase = $responsibleUser->stases()->first();
        if (!$stase) {
            return redirect()->back()->with('error', 'Anda belum memiliki stase yang ditugaskan.');
        }
        
        // Get department info
        $departement = $stase->departement;
        
        // Get FK-01 class or latest if not found
        $internshipClass = InternshipClass::where('name', 'FK-01')->first() ?? InternshipClass::latest()->first();
        if (!$internshipClass) {
            return redirect()->back()->with('error', 'Tidak ada kelas aktif saat ini.');
        }
        
        $kelas = 'FK-01';
        
        // Get all students in the class
        $allStudents = Student::where('internship_class_id', $internshipClass->id)
            ->with(['user', 'studyProgram.campus'])
            ->get();
        
        // Check if show_all parameter exists
        if ($request->has('show_all')) {
            // Show all students when show_all parameter is present
            $students = $allStudents;
            $remainingCount = 0;
        } else {
            // Otherwise show only first 5 students
            $students = $allStudents->take(5);
            $remainingCount = max(0, $allStudents->count() - 5);
        }
        
        // Get grade components for this stase
        $gradeComponents = GradeComponent::where('stase_id', $stase->id)->get();
        
        // If no grade components, create default ones
        if ($gradeComponents->isEmpty()) {
            $components = [
                'Keahlian',
                'Komunikasi',
                'Profesionalisme',
                'Kemampuan Menangani Pasien'
            ];
            
            foreach ($components as $component) {
                GradeComponent::create([
                    'stase_id' => $stase->id,
                    'name' => $component
                ]);
            }
            
            $gradeComponents = GradeComponent::where('stase_id', $stase->id)->get();
        }
        
        // Get existing grades
        $studentIds = $students->pluck('id')->toArray();
        $existingGrades = StudentGrade::where('stase_id', $stase->id)
            ->whereIn('student_id', $studentIds)
            ->get()
            ->groupBy('student_id');
        
        return view('pages.responsible.grades.index', [
            'kelas' => $kelas,
            'stase' => $stase,
            'departement' => $departement,
            'students' => $students,
            'gradeComponents' => $gradeComponents,
            'existingGrades' => $existingGrades,
            'remainingCount' => $remainingCount,
            'showAll' => $request->has('show_all')
        ]);
    }
    
    public function store(Request $request)
    {
        // Validate request
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'stase_id' => 'required|exists:stases,id',
            'grades' => 'required|array',
            'grades.*' => 'required|numeric|min:0|max:100',
        ]);
        
        // Calculate average grade
        $avgGrade = array_sum($request->grades) / count($request->grades);
        
        // Save or update grades
        StudentGrade::updateOrCreate(
            [
                'student_id' => $request->student_id,
                'stase_id' => $request->stase_id
            ],
            [
                'avg_grades' => $avgGrade,
                'grade_details' => json_encode($request->grades)
            ]
        );
        
        return redirect()->back()->with('success', 'Nilai berhasil disimpan.');
    }
}