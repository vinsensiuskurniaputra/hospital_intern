<?php

namespace App\Http\Controllers\Admin;

use App\Models\Menu;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminRoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::paginate(10);
        $menus = Menu::all();
        return view('pages.admin.role.index', compact('roles', 'menus'));
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
            'menus' => ['required', 'array'], 
            'menus.*' => ['exists:menus,id'], 
        ]);

        $menus = Menu::whereIn('id', $validatedData['menus'])->get();

        $role = Role::create([
            'name' => $validatedData['name']
        ]);

        $role->menus()->sync($menus);

        return redirect()->route('admin.roles.index')->with('success', 'Role created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        $role = $role;
        $menus = Menu::where('parent_id', null)->get();
        
        return view('pages.admin.role.edit', compact('role', 'menus'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'menus' => ['required', 'array'], 
            'menus.*' => ['exists:menus,id'], 
        ]);

        $roleDefault = Role::whereIn('name', ['admin', 'student', 'responsible'])->pluck('id');
        
        if($roleDefault->contains($role->id)){
            if( $validatedData['name'] != $role->name){
                return redirect()->route('admin.roles.index')->with('error', 'You cannot change name default role');
            }
        }

        $menus = Menu::whereIn('id', $validatedData['menus'])->get();

        $role->update([
            'name' => $validatedData['name']
        ]);

        $role->menus()->sync($menus);

        return redirect()->route('admin.roles.index')->with('success', 'Role updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        $roleDefault = Role::whereIn('name', ['admin', 'student', 'responsible'])->pluck('id');

        if($roleDefault->contains($role->id)){
            return redirect()->route('admin.roles.index')->with('error', 'You cannot delete default role');
        }

        $role->delete();
        return redirect()->route('admin.roles.index')->with('success', 'Role deleted successfully');
    }

    public function filter(Request $request)
    {
        $query = Role::query();

        if ($request->has('search') && $request->search != '') {
            $searchTerm = trim($request->search);
            $query->where('name', 'like', "%{$searchTerm}%");
        }

        $roles = $query->paginate(10);

        return view('components.admin.role.table', compact('roles'))->render();
    }
}
