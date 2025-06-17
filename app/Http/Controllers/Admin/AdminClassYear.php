<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassYear;
use Illuminate\Http\Request;

class AdminClassYear extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $classYears = ClassYear::paginate(10);
        return view('pages.admin.class_year.index', compact('classYears'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.admin.class_year.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'class_year' => 'required|string|max:255',
        ]);

        ClassYear::create($validatedData);

        return redirect()->route('admin.classYears.index')->with('success', 'Class Year created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(ClassYear $classYear)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ClassYear $classYear)
    {
        return view('pages.admin.class_year.edit', compact('classYear'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ClassYear $classYear)
    {
        $validatedData = $request->validate([
            'class_year' => 'required|string|max:255',
        ]);

        $classYear->update($validatedData);

        return redirect()->route('admin.classYears.index')->with('success', 'Class Year updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ClassYear $classYear)
    {
        $classYear->delete();
        return redirect()->route('admin.classYears.index')->with('success', 'Class Year deleted successfully');
    }
}
