<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\ResponsibleUser;
use App\Http\Controllers\Controller;

class AdminResponsibleUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $responsibles = ResponsibleUser::with(['user'])->paginate(10);
        return view('pages.admin.responsible.index', compact('responsibles'));
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
            'telp' => 'required|string',
        ]);

        $photoPath = null;

        if ($request->hasFile('photo_profile')) {
            $photoPath = $request->file('photo_profile')->store('profile_pictures', 'public');
        }

        $user = User::addUser([
            'username' => $validatedData['username'],
            'name' => $validatedData['name'],
            'email' => $validatedData['email'] ?? null,
            'password' => 'password',
            'photo_profile_url' => $photoPath,
        ]);

        $responsibleRole = Role::where('name', 'pic')->first();

        $user->roles()->attach($responsibleRole);

        $responsible = ResponsibleUser::createResponsibleUser([
            'user_id' => $user->id,
            'telp' => $validatedData['telp'],
        ]);

        return redirect()->route('admin.responsibles.index')->with('success', 'Responsible user created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(ResponsibleUser $responsibleUser)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ResponsibleUser $responsible)
    {
        $responsible = $responsible;
        
        return view('pages.admin.responsible.edit', compact('responsible'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ResponsibleUser $responsible)
    {
        $validatedData = $request->validate([
            'username' => 'required|string|unique:users,username,' . $responsible->user->id,
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . $responsible->user->id,
            'photo_profile' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'telp' => 'required|string',
        ]);

        $photoPath = $responsible->user->photo_profile_url;

        if ($request->hasFile('photo_profile')) {
            if ($photoPath) {
                Storage::disk('public')->delete($photoPath);
            }

            $photoPath = $request->file('photo_profile')->store('profile_pictures', 'public');
        }



        User::updateUser($responsible->user_id, [
            'username' => $validatedData['username'],
            'name' => $validatedData['name'],
            'email' => $validatedData['email'] ?? null,
            'photo_profile_url' => $photoPath,
        ]);

        ResponsibleUser::updateResponsibleUser($responsible->id, [
            'telp' => $validatedData['telp'],
        ]);

        return redirect()->route('admin.responsibles.index')->with('success', 'Responsible User updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ResponsibleUser $responsible)
    {
        $userId = $responsible->user_id;
        $responsibleUserId = $responsible->id;

        // Hapus data setelah ID tersimpan
        User::deleteUser($userId);
        ResponsibleUser::deleteResponsibleUser($responsibleUserId);

        return redirect()->route('admin.responsibles.index')->with('success', 'Responsible User deleted successfully');
    }

    public function filter(Request $request)
    {
        $query = ResponsibleUser::query();

        if ($request->has('search') && $request->search != '') {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('email', 'like', '%' . $request->search . '%')
                ->orWhere('username', 'like', '%' . $request->search . '%');
            });
        }

        $responsibles = $query->paginate(10);

        return view('components.admin.responsible.table', compact('responsibles'))->render();
    }

    public function import(Request $request)
    {
        $validated = $request->validate([
            'file' => 'required|mimes:csv,txt',
        ]);


        $file = $request->file('file');
        $rows = array_map('str_getcsv', file($file));
        $headers = array_map('strtolower', array_map('trim', $rows[0]));
        unset($rows[0]); 

        $inserted = 0;
        $skipped = 0;

        foreach ($rows as $row) {
            $row = array_combine($headers, $row);

            // Skip jika username atau nim sudah ada
            if (User::where('username', $row['username'])->orWhere('email', $row['email'])->exists()) {
                $skipped++;
                continue;
            }

            // Buat user
            $user = User::addUser([
                'username' => $row['username'],
                'name' => $row['name'],
                'email' => $row['email'] ?? null,
                'password' => 'password',
                'photo_profile_url' => null,
            ]);

            // Tambahkan role reposnible
            $reposnibleRole = Role::where('name', 'pic')->first();
            $user->roles()->attach($reposnibleRole);

            // Buat reposnible
            ResponsibleUser::create([
                'user_id' => $user->id,
                'telp' => $row['telp'],
            ]);

            $inserted++;
        }

        return redirect()->route('admin.responsibles.index')->with('success', "Import selesai: {$inserted} data ditambahkan, {$skipped} dilewati karena duplikat.");
    }

    public function downloadTemplate()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="pic_import_template.csv"',
        ];

        // Header sesuai dengan array keys yang digunakan di fungsi import()
        $columns = [
            'username',
            'name',
            'email',
            'telp'
        ];

        $callback = function () use ($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            // Contoh baris data kosong (atau dummy) sebagai panduan
            fputcsv($file, ['johndoe', 'John Doe', 'john@example.com', '08123456789']);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
