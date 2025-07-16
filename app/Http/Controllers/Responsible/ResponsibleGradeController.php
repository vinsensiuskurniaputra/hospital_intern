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
use App\Models\ClassYear;
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
        
        // // PERBAIKAN: Get ALL stases instead of only assigned stases
        $stases = Stase::with('departement')->get();
        
        // Fallback: jika tidak ada stase sama sekali
        if ($stases->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada stase yang tersedia.');
        }
        
        // TAMBAHAN: Get available years from ClassYear - SEMUA data tanpa filter
        $availableYears = ClassYear::orderBy('class_year', 'desc')->get();
        
        // Get selected stase (from request or default to first)
        $selectedStaseId = $request->input('stase_id', $stases->first()->id);
        $stase = $stases->find($selectedStaseId) ?? $stases->first();
        
        // TAMBAHAN: Get selected year
        $selectedYear = $request->input('year');
        
        // PERBAIKAN: Get available classes berdasarkan tahun yang dipilih - HANYA data yang ada
        $availableClasses = collect();
        if ($selectedYear) {
            // Ambil kelas berdasarkan tahun dari ClassYear - HANYA yang sudah ada
            $classYear = ClassYear::where('class_year', $selectedYear)->first();
            if ($classYear) {
                $availableClasses = InternshipClass::where('class_year_id', $classYear->id)->get();
            }
        }
        
        // Get selected class (from request or default to first if available)
        $selectedClassId = $request->input('class_id');
        $internshipClass = null;
        
        if ($selectedClassId && $availableClasses->isNotEmpty()) {
            $internshipClass = $availableClasses->find($selectedClassId) ?? $availableClasses->first();
        } elseif ($availableClasses->isNotEmpty()) {
            $internshipClass = $availableClasses->first();
        }
        
        // Get department info
        $departement = $stase->departement;
        
        // Initialize variables
        $allStudents = collect();
        $students = collect();
        $remainingCount = 0;
        $showAll = false;
        
        // PERBAIKAN: Filter students hanya jika tahun dan kelas sudah dipilih
        if ($selectedYear && $internshipClass) {
            $studentsQuery = Student::where('internship_class_id', $internshipClass->id)
                ->with(['user', 'studyProgram.campus']);
            
            // Filter berdasarkan tahun ajaran melalui relasi class_year
            $studentsQuery->whereHas('internshipClass.classYear', function($query) use ($selectedYear) {
                $query->where('class_year', $selectedYear);
            });
            
            $allStudents = $studentsQuery->get();
            
            // Check if show_all parameter exists
            if ($request->has('show_all')) {
                $students = $allStudents;
                $remainingCount = 0;
                $showAll = true;
            } else {
                $students = $allStudents->take(5);
                $remainingCount = max(0, $allStudents->count() - 5);
                $showAll = false;
            }
        }
        
        // Get grade components for this stase
        $gradeComponents = GradeComponent::where('stase_id', $stase->id)->get();
        
        // If no grade components, create default ones
        if ($gradeComponents->isEmpty()) {
            $components = [
                'Sikap', // TAMBAHAN: Sikap sebagai komponen pertama
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
        
        // PERBAIKAN: Ambil data dari StudentComponentGrade hanya jika ada students
        $existingGrades = [];
        if ($students->isNotEmpty()) {
            $studentIds = $students->pluck('id')->toArray();
            $componentGrades = \App\Models\StudentComponentGrade::where('stase_id', $stase->id)
                ->whereIn('student_id', $studentIds)
                ->with(['gradeComponent'])
                ->get()
                ->groupBy('student_id');
            
            // PERBAIKAN: Ambil SEMUA data rata-rata dari StudentGrade
            $studentGrades = StudentGrade::where('stase_id', $stase->id)
                ->whereIn('student_id', $studentIds)
                ->get()
                ->keyBy('student_id');
            
            // Format the grades data for easier access in the view
            foreach ($students as $student) {
                $studentId = $student->id;
                
                // Check if student has component grades
                if (isset($componentGrades[$studentId])) {
                    $gradeData = [];
                    foreach ($componentGrades[$studentId] as $grade) {
                        $gradeData[$grade->grade_component_id] = $grade->value;
                    }
                    $existingGrades[$studentId] = [
                        'grades' => $gradeData,
                        'updated_at' => $componentGrades[$studentId]->first()->updated_at ?? null,
                        'average' => isset($studentGrades[$studentId]) ? $studentGrades[$studentId]->avg_grades : null
                    ];
                }
                // Check if student only has average (without component grades)
                elseif (isset($studentGrades[$studentId])) {
                    $existingGrades[$studentId] = [
                        'grades' => [],
                        'updated_at' => $studentGrades[$studentId]->updated_at ?? null,
                        'average' => $studentGrades[$studentId]->avg_grades
                    ];
                }
            }
        }
        
        return view('pages.responsible.grades.index', [
            'kelas' => $internshipClass ? $internshipClass->name : '',
            'stase' => $stase,
            'departement' => $departement,
            'students' => $students,
            'gradeComponents' => $gradeComponents,
            'existingGrades' => $existingGrades,
            'remainingCount' => $remainingCount,
            'showAll' => $showAll,
            'availableClasses' => $availableClasses,
            'selectedClassId' => $internshipClass ? $internshipClass->id : null,
            'stases' => $stases, // Sekarang berisi SEMUA stase
            'selectedStaseId' => $stase->id,
            'availableYears' => $availableYears,
            'selectedYear' => $selectedYear
        ]);
    }
    
    public function store(Request $request)
    {
        // Validate request dengan dukungan desimal
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'stase_id' => 'required|exists:stases,id',
            'grades' => 'required|array',
            'grades.*' => 'required|numeric|min:0|max:100',
        ], [
            'grades.*.max' => 'Nilai tidak boleh lebih dari 100.',
            'grades.*.min' => 'Nilai tidak boleh kurang dari 0.',
            'grades.*.numeric' => 'Nilai harus berupa angka.',
        ]);
        
        // Get responsible user ID
        $responsibleUserId = Auth::user()->responsibleUser->id;
        
        // Get today's date
        $today = Carbon::today()->format('Y-m-d');
        
        // Calculate average grade dengan penanganan desimal yang tepat
        $grades = array_map(function($grade) {
            return round(floatval($grade), 2);
        }, $request->grades);
        
        // Filter out zero values for average calculation
        $nonZeroGrades = array_filter($grades, function($grade) {
            return $grade > 0;
        });
        
        $avgGrade = count($nonZeroGrades) > 0 ? round(array_sum($nonZeroGrades) / count($nonZeroGrades), 2) : 0;
        
        // Save overall grade to StudentGrade
        StudentGrade::updateOrCreate(
            [
                'student_id' => $request->student_id,
                'stase_id' => $request->stase_id
            ],
            [
                'avg_grades' => $avgGrade
            ]
        );
        
        // Save individual component grades to StudentComponentGrade
        foreach ($request->grades as $componentId => $value) {
            // Ensure component ID is valid
            $component = GradeComponent::find($componentId);
            if (!$component) continue;
            
            // Only save if value is greater than 0
            if (floatval($value) > 0) {
                \App\Models\StudentComponentGrade::updateOrCreate(
                    [
                        'student_id' => $request->student_id,
                        'grade_component_id' => $componentId,
                        'stase_id' => $request->stase_id
                    ],
                    [
                        'value' => round(floatval($value), 2),
                        'evaluation_date' => $today,
                        'responsible_user_id' => $responsibleUserId
                    ]
                );
            } else {
                // Delete the record if value is 0 or empty
                \App\Models\StudentComponentGrade::where([
                    'student_id' => $request->student_id,
                    'grade_component_id' => $componentId,
                    'stase_id' => $request->stase_id
                ])->delete();
            }
        }
        
        // PERBAIKAN: Redirect dengan parameter yang sama
        $redirectUrl = route('responsible.grades.index') . '?stase_id=' . $request->stase_id . '&class_id=' . $request->input('class_id');
        
        if ($request->filled('year')) {
            $redirectUrl .= '&year=' . $request->input('year');
        }
        
        if ($request->has('show_all')) {
            $redirectUrl .= '&show_all=1';
        }
        
        return redirect($redirectUrl)->with('success', 'Nilai berhasil disimpan dengan rata-rata ' . $avgGrade);
    }
}