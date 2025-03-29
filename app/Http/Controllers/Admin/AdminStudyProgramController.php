<?php

namespace App\Http\Controllers\Admin;

use App\Models\Campus;
use App\Models\StudyProgram;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminStudyProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $studyPrograms = StudyProgram::with(['campus'])->paginate(10);
        $campuses = Campus::all();
        return view('pages.admin.study_program.index', compact('studyPrograms', 'campuses'));
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
            'campus_id' => 'required|exists:campuses,id',
        ]);


        $user = StudyProgram::create([
            'name' => $validatedData['name'],
            'campus_id' => $validatedData['campus_id'],
        ]);

        return redirect()->route('admin.studyPrograms.index')->with('success', 'Study Program created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(StudyProgram $studyProgram)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StudyProgram $studyProgram)
    {
        $campuses = Campus::all();
        return view('pages.admin.study_program.edit', compact('studyProgram', 'campuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StudyProgram $studyProgram)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'campus_id' => 'required|exists:campuses,id',
        ]);


        $studyProgram->update([
            'name' => $validatedData['name'],
            'campus_id' => $validatedData['campus_id'],
        ]);

        return redirect()->route('admin.studyPrograms.index')->with('success', 'Study Program updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StudyProgram $studyProgram)
    {
        $studyProgram->delete();
        return redirect()->route('admin.studyPrograms.index')->with('success', 'Study Program deleted successfully');
    }

    public function filter(Request $request)
    {
        $query = StudyProgram::query();

        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $studyPrograms = $query->paginate(10);

        return view('components.admin.study_program.table', compact('studyPrograms'))->render();
    }
}
