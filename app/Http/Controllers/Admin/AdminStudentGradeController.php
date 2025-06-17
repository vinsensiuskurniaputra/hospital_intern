<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Student;
use App\Models\Departement;
use App\Models\StudentGrade;
use App\Models\StudyProgram;
use App\Models\Stase;
use App\Models\InternshipClass;
use App\Models\ClassYear;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminStudentGradeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $defaultDepartementId = $request->get('departement', 1); // Default to departement ID 1
        $perPage = $request->get('per_page', 10); // Default to 10 students per page

        $query = Student::with(['user', 'grades.stase.departement']);
        @dd($query);

        if ($request->has('study_program') && $request->study_program != '') {
            $query->whereHas('studyProgram', function ($q) use ($request) {
                $q->where('id', $request->study_program);
            });
        }

        if ($defaultDepartementId != '') {
            $query->whereHas('grades.stase.departement', function ($q) use ($defaultDepartementId) {
                $q->where('id', $defaultDepartementId);
            });
        }

        if ($request->has('internship_class') && $request->internship_class != '') {
            $query->where('internship_class_id', $request->internship_class);
        }

        if ($request->has('class_year') && $request->class_year != '') {
            $query->whereHas('internshipClass.classYear', function ($q) use ($request) {
                $q->where('id', $request->class_year);
            });
        }

        if ($request->has('search') && $request->search != '') {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        $students = $query->paginate($perPage)->appends($request->all()); // Append all request parameters to pagination links
        $studyPrograms = StudyProgram::all();
        $departements = Departement::all();
        $internshipClasses = InternshipClass::all();
        $classYears = ClassYear::all();
        $stases = Stase::where('departement_id', $defaultDepartementId)->get(); // Fetch stases for the selected department

        return view('pages.admin.student_grade.index', compact('students', 'studyPrograms', 'departements', 'internshipClasses', 'classYears', 'stases', 'defaultDepartementId', 'perPage'));
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
        $departementId = $request->get('departement', 1); // Default to departement ID 1
        $perPage = $request->get('per_page', 10); // Default to 10 students per page

        $query = Student::with(['user', 'grades.stase.departement']);

        if ($request->has('study_program') && $request->study_program != '') {
            $query->whereHas('studyProgram', function ($q) use ($request) {
                $q->where('id', $request->study_program);
            });
        }

        if ($departementId != '') {
            $query->whereHas('grades.stase.departement', function ($q) use ($departementId) {
                $q->where('id', $departementId);
            });
        }

        if ($request->has('search') && $request->search != '') {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        $students = $query->paginate($perPage)->appends($request->all()); // Append all request parameters to pagination links
        $stases = Stase::where('departement_id', $departementId)->get(); // Fetch stases for the selected department

        return view('components.admin.student_grade.table', compact('students', 'stases'))->render();
    }

}
