<?php

namespace App\Http\Controllers\Admin;

use App\Models\Campus;
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
}
