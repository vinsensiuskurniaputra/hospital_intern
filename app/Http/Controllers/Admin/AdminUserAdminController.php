<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;

class AdminUserAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = Role::where('name', 'admin')->first()->users()->paginate(10);
        return view('pages.admin.admin.index', compact('users'));
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
            'username' => 'required|string|unique:users,username',
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users,email',
            'photo_profile' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'password' => 'required|string|max:255|min:8',
            'password_confirmation' => 'required|string|same:password',
        ]);

        $photoPath = null;

        if ($request->hasFile('photo_profile')) {
            $photoPath = $request->file('photo_profile')->store('profile_pictures', 'public');
        }

        $user = User::addUser([
            'username' => $validatedData['username'],
            'name' => $validatedData['name'],
            'email' => $validatedData['email'] ?? null,
            'password' => $validatedData['password'],
            'photo_profile_url' => $photoPath,
        ]);

        $adminRole = Role::where('name', 'admin')->first();

        $user->roles()->attach($adminRole);

        return redirect()->route('admin.admins.index')->with('success', 'Admin created successfully');
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
    public function edit(User $admin)
    {
        $user = $admin;
        
        return view('pages.admin.admin.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $admin)
    {
        $validatedData = $request->validate([
            'username' => [
                'required',
                'string',
                Rule::unique('users', 'username')->ignore($admin->id, 'id'),
            ],
            'name' => 'required|string|max:255',
            'email' => [
                'nullable',
                'email',
                Rule::unique('users', 'email')->ignore($admin->id, 'id'),
            ],
            'photo_profile' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'password' => 'nullable|string|min:8|max:255',
            'password_confirmation' => 'nullable|string|same:password',
        ]);

        $photoPath = $admin->photo_profile_url;

        if ($request->hasFile('photo_profile')) {
            if ($photoPath) {
                Storage::disk('public')->delete($photoPath);
            }

            $photoPath = $request->file('photo_profile')->store('profile_pictures', 'public');
        }



        User::updateUser($admin->id, [
            'username' => $validatedData['username'],
            'name' => $validatedData['name'],
            'password' => $validatedData['password'],
            'email' => $validatedData['email'] ?? null,
            'photo_profile_url' => $photoPath,
        ]);

        return redirect()->route('admin.admins.index')->with('success', 'Admin updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $admin)
    {
        $userId = $admin->id;

        if($admin->id == auth()->user()->id){
            return redirect()->route('admin.admins.index')->with('error', 'You cannot delete yourself');
        }

        // Hapus data setelah ID tersimpan
        User::deleteUser($userId);

        return redirect()->route('admin.admins.index')->with('success', 'Admin deleted successfully');
    }

    public function filter(Request $request)
    {
        $query = Role::where('name', 'admin')->first()->users();

        if ($request->has('search') && $request->search != '') {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('email', 'like', '%' . $request->search . '%')
                ->orWhere('username', 'like', '%' . $request->search . '%');
            });
        }

        $users = $query->paginate(10);

        return view('components.admin.admin.table', compact('users'))->render();
    }
}
