<?php

namespace App\Http\Controllers\Admin;

use App\Models\Menu;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class AdminUserAuthorizationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::paginate(10);
        $userCount = User::count();
        $roleCount = Role::count();
        $menuCount = Menu::count();
        $roles = Role::all();
        return view('pages.admin.user_authorization.index', compact('users', 'userCount', 'roleCount', 'menuCount', 'roles'));
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
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $user = $user;
        $roles = Role::all();
        
        return view('pages.admin.user_authorization.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        // Validasi input
        $validatedData = $request->validate([
            'roles' => ['required', 'array'], // roles harus berupa array
            'roles.*' => ['exists:roles,id'], // Setiap item dalam roles harus ada di tabel roles
        ]);

        // Ambil role berdasarkan ID yang sudah divalidasi
        $roles = Role::whereIn('id', $validatedData['roles'])->get();

        // Sinkronisasi role dengan user
        $user->roles()->sync($roles);

        return redirect()->route('admin.user_authorizations.index')->with('success', 'User roles updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }

    public function filter(Request $request)
    {
        $query = User::query();

        if ($request->has('role') && !empty($request->role) && is_numeric($request->role)) {
            $query->whereHas('roles', function ($q) use ($request) {
                $q->where('role_id', $request->role); 
            });
        }

        if ($request->has('search') && $request->search != '') {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('email', 'like', '%' . $request->search . '%')
                ->orWhere('username', 'like', '%' . $request->search . '%');
            });
        }

        $users = $query->paginate(10);

        return view('components.admin.user_authorization.table', compact('users'))->render();
    }
}
