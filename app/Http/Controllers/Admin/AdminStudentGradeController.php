<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StudentGrade;
use Illuminate\Http\Request;

class AdminStudentGradeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.admin.student_score.index');
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
}
