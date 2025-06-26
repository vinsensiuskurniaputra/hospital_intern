<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GradeComponent;
use App\Models\Stase;
use Illuminate\Http\Request;

class AdminGradeComponent extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $gradeComponents = GradeComponent::paginate(10);
        $stases = Stase::all();
        return view('components.admin.grade_component.index', compact('gradeComponents', 'stases'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $stases = Stase::all();
        return view('components.admin.grade_component.add', compact('stases'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'stase_id' => 'required|exists:stases,id',
        ]);

        GradeComponent::create($validatedData);

        return redirect()->route('admin.gradeComponents.index')->with('success', 'Grade Component created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(GradeComponent $gradeComponent)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(GradeComponent $gradeComponent)
    {
        $stases = Stase::all();
        return view('components.admin.grade_component.edit', compact('gradeComponent', 'stases'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, GradeComponent $gradeComponent)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'stase_id' => 'required|exists:stases,id',
        ]);

        $gradeComponent->update($validatedData);

        return redirect()->route('admin.gradeComponents.index')->with('success', 'Grade Component updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GradeComponent $gradeComponent)
    {
        $gradeComponent->delete();
        return redirect()->route('admin.gradeComponents.index')->with('success', 'Grade Component deleted successfully');
    }

    public function filter(Request $request)
    {
        $query = GradeComponent::query();

        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $gradeComponents = $query->paginate(10);

        return view('components.admin.grade_component.table', compact('gradeComponents'))->render();
    }
}
