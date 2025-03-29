<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campus;
use Illuminate\Http\Request;

class AdminCampusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $campuses = Campus::paginate(10);
        return view('pages.admin.campus.index', compact('campuses'));
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
            'detail' => 'nullable|string',
        ]);


        $campus = Campus::create([
            'name' => $validatedData['name'],
            'detail' => $validatedData['detail'],
        ]);

        return redirect()->route('admin.campuses.index')->with('success', 'Campus created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Campus $campus)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Campus $campus)
    {
        
        return view('pages.admin.campus.edit', compact('campus'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Campus $campus)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'detail' => 'nullable|string',
        ]);


        $campus->update([
            'name' => $validatedData['name'],
            'detail' => $validatedData['detail'],
        ]);

        return redirect()->route('admin.campuses.index')->with('success', 'Campus updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Campus $campus)
    {
        $campus->delete();
        return redirect()->route('admin.campuses.index')->with('success', 'Campus deleted successfully');
    }

    public function filter(Request $request)
    {
        $query = Campus::query();

        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $campuses = $query->paginate(10);

        return view('components.admin.campus.table', compact('campuses'))->render();
    }
}
