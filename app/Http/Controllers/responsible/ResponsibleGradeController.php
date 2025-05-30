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
        
        // Get all stases assigned to this responsible user
        $responsibleUser = ResponsibleUser::where('user_id', $user->id)->first();
        if (!$responsibleUser) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses sebagai penanggung jawab.');
        }
        
        // Get all stases this user is responsible for
        $stases = $responsibleUser->stases;
        if ($stases->isEmpty()) {
            return redirect()->back()->with('error', 'Anda belum memiliki stase yang ditugaskan.');
        }
        
        // Get available classes (creating defaults if needed)
        $availableClasses = [
            InternshipClass::firstOrCreate(['name' => 'FK-01'], ['status' => 'active']),
            InternshipClass::firstOrCreate(['name' => 'FK-02'], ['status' => 'active'])
        ];
        
        // Get selected class (from request or default to first)
        $selectedClassId = $request->input('class_id', $availableClasses[0]->id);
        $internshipClass = InternshipClass::find($selectedClassId) ?? $availableClasses[0];
        
        // Get selected stase (from request or default to first)
        $selectedStaseId = $request->input('stase_id', $stases->first()->id);
        $stase = $stases->find($selectedStaseId) ?? $stases->first();
        
        // Get department info
        $departement = $stase->departement;
        
        // Get all students in the selected class
        $allStudents = Student::where('internship_class_id', $internshipClass->id)
            ->with(['user', 'studyProgram.campus'])
            ->get();
        
        // Check if show_all parameter exists
        if ($request->has('show_all')) {
            // Show all students when show_all parameter is present
            $students = $allStudents;
            $remainingCount = 0;
            $showAll = true;
        } else {
            // Otherwise show only first 5 students
            $students = $allStudents->take(5);
            $remainingCount = max(0, $allStudents->count() - 5);
            $showAll = false;
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
            'kelas' => $internshipClass->name,
            'stase' => $stase,
            'departement' => $departement,
            'students' => $students,
            'gradeComponents' => $gradeComponents,
            'existingGrades' => $existingGrades,
            'remainingCount' => $remainingCount,
            'showAll' => $showAll,
            'availableClasses' => $availableClasses,
            'selectedClassId' => $internshipClass->id,
            'stases' => $stases,
            'selectedStaseId' => $stase->id
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
        
        // Get responsible user ID
        $responsibleUserId = Auth::user()->responsibleUser->id;
        
        // Get today's date
        $today = Carbon::today()->format('Y-m-d');
        
        // Calculate average grade
        $avgGrade = array_sum($request->grades) / count($request->grades);
        
        // Save overall grade to StudentGrade
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
        
        // Save individual component grades to StudentComponentGrade
        foreach ($request->grades as $componentId => $value) {
            // Ensure component ID is valid
            $component = GradeComponent::find($componentId);
            if (!$component) continue;
            
            // Save component grade
            \App\Models\StudentComponentGrade::updateOrCreate(
                [
                    'student_id' => $request->student_id,
                    'grade_component_id' => $componentId,
                    'stase_id' => $request->stase_id
                ],
                [
                    'value' => $value,
                    'evaluation_date' => $today,
                    'responsible_user_id' => $responsibleUserId
                ]
            );
        }
        
        return redirect()->back()->with('success', 'Nilai berhasil disimpan.');
    }
}