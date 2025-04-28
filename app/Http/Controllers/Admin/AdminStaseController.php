<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Stase;
use Illuminate\Http\Request;
use App\Models\ResponsibleUser;
use App\Http\Controllers\Controller;

class AdminStaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stases = Stase::paginate(10);
        $responsibles = User::whereHas('roles', function ($query) {
            $query->where('name', 'responsible');
        })->get();
        return view('pages.admin.stase.index', compact('stases', 'responsibles'));
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
            'responsible_user_id' => 'required|exists:responsible_users,id',
            'detail' => 'required|string|max:255',
        ]);


        $user = Stase::create([
            'name' => $validatedData['name'],
            'responsible_user_id' => $validatedData['responsible_user_id'],
            'detail' => $validatedData['detail'],
        ]);

        return redirect()->route('admin.stases.index')->with('success', 'Stase created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Stase $stase)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Stase $stase)
    {
        $responsibles = User::whereHas('roles', function ($query) {
            $query->where('name', 'responsible');
        })->get();
        return view('pages.admin.stase.edit', compact('stase', 'responsibles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Stase $stase)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'responsible_user_id' => 'required|exists:responsible_users,id',
            'detail' => 'required|string|max:255',
        ]);


        $stase->update([
            'name' => $validatedData['name'],
            'responsible_user_id' => $validatedData['responsible_user_id'],
            'detail' => $validatedData['detail'],
        ]);

        return redirect()->route('admin.stases.index')->with('success', 'Stase updated successfully');
    }
 
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Stase $stase)
    {
        $stase->delete();
        return redirect()->route('admin.stases.index')->with('success', 'Stase deleted successfully');
    }

    public function filter(Request $request)
    {
        $query = Stase::query();

        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%')->orWhere('detail', 'like', '%' . $request->search . '%');
        }

        $stases = $query->paginate(10);

        return view('components.admin.stase.table', compact('stases'))->render();
    }
}
