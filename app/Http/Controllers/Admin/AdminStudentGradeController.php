<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Departement;
use App\Models\StudentGrade;
use App\Models\StudyProgram;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminStudentGradeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $studentGrades = StudentGrade::paginate(10);
        $studyPrograms = StudyProgram::all();
        $departements = Departement::all();

        // Rata-rata semua data
        $allStudentAvg = StudentGrade::avg('avg_grades');

        // Bulan ini
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $currentAvg = StudentGrade::whereMonth('created_at', $currentMonth)
                        ->whereYear('created_at', $currentYear)
                        ->avg('avg_grades');

        // Bulan lalu
        $lastMonthDate = Carbon::now()->subMonth();
        $lastAvg = StudentGrade::whereMonth('created_at', $lastMonthDate->month)
                        ->whereYear('created_at', $lastMonthDate->year)
                        ->avg('avg_grades');

        // Hitung persentase perubahan
        $changePercent = 0;
        if ($lastAvg > 0) {
            $changePercent = (($currentAvg - $lastAvg) / $lastAvg) * 100;
        }

        $isDown = $changePercent < 0;

        return view('pages.admin.student_grade.index', compact('studentGrades', 'allStudentAvg', 'currentAvg', 'lastAvg', 'changePercent', 'isDown', 'studyPrograms', 'departements'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(StudentGrade $studentGrade)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StudentGrade $studentGrade)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StudentGrade $studentGrade)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StudentGrade $studentGrade)
    {
        //
    }

    public function filter(Request $request)
    {
        $query = StudentGrade::query();

        if ($request->has('study_program') && $request->study_program != '') {
            $query->whereHas('student.studyProgram', function ($q) use ($request) {
                $q->where('id', $request->study_program);
            });
        }

        if ($request->has('departement') && $request->departement != '') {
            $query->whereHas('stase.departement', function ($q) use ($request) {
                $q->where('id', $request->departement);
            });
        }

        if ($request->has('search') && $request->search != '') {
            $query->whereHas('student.user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }
        
        $studentGrades = $query->paginate(10);

        return view('components.admin.student_grade.table', compact('studentGrades'))->render();
    }

}
