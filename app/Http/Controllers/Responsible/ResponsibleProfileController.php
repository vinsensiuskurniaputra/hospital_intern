<?php

namespace App\Http\Controllers\Responsible;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ResponsibleUser;
use Auth;
use Illuminate\Support\Facades\Hash;

class ResponsibleProfileController extends Controller
{
    /**
     * Display the profile page for responsible user.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $responsible = ResponsibleUser::where('user_id', $user->id)->first();
        
        return view('pages.responsible.profile.index', compact('user', 'responsible'));
    }

    public function edit()
    {
        $user = Auth::user();
        $responsible = ResponsibleUser::where('user_id', $user->id)->first();
        
        return view('pages.responsible.profile.editpj', compact('user', 'responsible'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email:rfc,dns',
            'telp' => 'required|regex:/^[0-9]+$/'
        ], [
            'name.required' => 'Nama lengkap harus diisi',
            'name.string' => 'Nama lengkap harus berupa text',
            'name.max' => 'Nama lengkap maksimal 255 karakter',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'telp.required' => 'Nomor telepon harus diisi',
            'telp.regex' => 'Nomor telepon harus berupa angka'
        ]);

        $user = Auth::user();
        
        // Update user data
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        // Update responsible user data
        ResponsibleUser::where('user_id', $user->id)->update([
            'telp' => $request->telp
        ]);

        return redirect()->route('responsible.profile')
            ->with('success', 'Profil berhasil diperbarui');
    }

    public function showChangePassword()
    {
        $user = Auth::user();
        $responsible = ResponsibleUser::where('user_id', $user->id)->first();
        
        return view('pages.responsible.profile.gantipasswordpj', compact('user', 'responsible'));
    }

    
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai']);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->route('responsible.profile')->with('success', 'Password berhasil diubah');
    }
}