<?php

namespace App\Http\Controllers\Admin;

use App\Models\Campus;
use App\Models\Student;
use App\Models\ClassYear;
use Illuminate\Http\Request;
use App\Models\InternshipClass;
use App\Http\Controllers\Controller;

class AdminInternshipClassController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $internshipClasses = InternshipClass::paginate(10);
        $classYears = ClassYear::all();
        $campuses = Campus::all();
        return view('pages.admin.internship_class.index', compact('internshipClasses', 'classYears', 'campuses'));
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
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'class_year_id' => 'required|exists:class_years,id',
            'campus_id' => 'required|exists:campuses,id',
            'description' => 'required|string|max:255',
        ]);


        InternshipClass::create([
            'name' => $validatedData['name'],
            'class_year_id' => $validatedData['class_year_id'],
            'campus_id' => $validatedData['campus_id'],
            'description' => $validatedData['description'],
        ]);

        return redirect()->route('admin.internshipClasses.index')->with('success', 'Internship class created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(InternshipClass $internshipClass)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(InternshipClass $internshipClass)
    {
        $classYears = ClassYear::all();
        $campuses = Campus::all();
        return view('pages.admin.internship_class.edit', compact('classYears', 'internshipClass', 'campuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, InternshipClass $internshipClass)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'class_year_id' => 'required|exists:class_years,id',
            'campus_id' => 'required|exists:campuses,id',
            'description' => 'required|string|max:255',
        ]);

        $internshipClass->update([
            'name' => $validatedData['name'],
            'class_year_id' => $validatedData['class_year_id'],
            'campus_id' => $validatedData['campus_id'],
            'description' => $validatedData['description'],
        ]);

        return redirect()->route('admin.internshipClasses.index')->with('success', 'Internship Class updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InternshipClass $internshipClass)
    {
        $internshipClass->delete();
        return redirect()->route('admin.internshipClasses.index')->with('success', 'Internship Class deleted successfully');
    }

    public function filter(Request $request)
    {
        $query = InternshipClass::query();

        if ($request->has('class_year') && $request->class_year != '') {
            $query->whereHas('classYear', function ($q) use ($request) {
                $q->where('class_year', $request->class_year);
            });
        }

        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $internshipClasses = $query->paginate(10);

        return view('components.admin.internship_class.table', compact('internshipClasses'))->render();
    }

    public function insertStudent()
    {
        $internshipClasses = InternshipClass::with('classYear')->get();
        // Get all students with their internship class info
        $students = Student::with(['user', 'internshipClass'])->get();
        $studentsGroupedByCampus = $students->groupBy('study_program.campus_id');
        $campuses = Campus::with('studyPrograms.Students')->get();
        return view('pages.admin.internship_class.insert_student', compact('internshipClasses', 'studentsGroupedByCampus', 'campuses', 'students'));
    }

    public function insertStudentStore(Request $request)
    {
        // Validate the request
        $validatedData = $request->validate([
            'internship_class_id' => 'required|exists:internship_classes,id',
            'students' => 'array',
            'students.*' => 'exists:students,id'
        ]);

        try {
            // Get the internship class
            $internshipClass = InternshipClass::findOrFail($validatedData['internship_class_id']);
            
            // Get all students currently in this class
            $currentStudents = Student::where('internship_class_id', $internshipClass->id)
                ->pluck('id')
                ->toArray();
            
            // Get the new selected students array (empty array if none selected)
            $selectedStudents = $validatedData['students'] ?? [];
            
            // Students to be removed (in current but not in selected)
            $studentsToRemove = array_diff($currentStudents, $selectedStudents);
            
            // Students to be added (in selected but not in current)
            $studentsToAdd = array_diff($selectedStudents, $currentStudents);
            
            // Remove students
            if (!empty($studentsToRemove)) {
                Student::whereIn('id', $studentsToRemove)
                    ->update(['internship_class_id' => null]);
            }
            
            // Add new students
            if (!empty($studentsToAdd)) {
                Student::whereIn('id', $studentsToAdd)
                    ->update(['internship_class_id' => $internshipClass->id]);
            }

            return redirect()
                ->route('admin.internshipClasses.index')
                ->with('success', 'Students updated in internship class successfully');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Failed to update students in internship class. ' . $e->getMessage())
                ->withInput();
        }
    }
}
