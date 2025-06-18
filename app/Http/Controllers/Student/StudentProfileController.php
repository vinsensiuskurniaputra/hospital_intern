<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Campus;
use App\Models\ClassYear;
use App\Models\InternshipClass;
use App\Models\Student;
use App\Models\StudyProgram;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class StudentProfileController extends Controller
{
    public function index() {
        $userId = auth()->user()->id;
        $studentId = Student::where('user_id', $userId)->first('id');

        $data1 = User::find($userId, ['name', 'email']);
        $data2 = Student::where('user_id', $userId)->first(['nim', 'telp']);
        
        $prodi_id = Student::where('user_id', $userId)->first('study_program_id');
        $prodiName = StudyProgram::find($prodi_id, 'name');

        $campusId = StudyProgram::find($prodi_id, 'campus_id');
        $campusId = $campusId[0]->campus_id;
        $campusName = Campus::where('id', $campusId)->first('name');
        
        $internshipClassId = Student::where('user_id', $userId)->first('internship_class_id');
        $classYearId = InternshipClass::find($internshipClassId, 'class_year_id');
        $classYearId = $classYearId[0]->class_year_id;
        $classYear = ClassYear::where('id', $classYearId)->first('class_year');
        $classYear = $classYear->class_year;
        
        // return ;
        $results = [
            'namaLengkap' => $data1->name,
            'email' => $data1->email,
            'nim' => $data2->nim,
            'telp' => $data2->telp,
            'campus' => $campusName->name,
            'angkatan' => $classYear,
            'prodi' => $prodiName[0]->name
        ];

        return view("pages.student.profile.index", compact('results'));
    }


    public function showChangePassword()
    {
        $userId = auth()->user()->id;
        $student = Student::where('user_id', $userId)->first();
        
        // Get study program name
        $prodi = StudyProgram::find($student->study_program_id);

        $user = User::find($userId);
        
        $results = [
            'namaLengkap' => $user->name,
            'prodi' => $prodi->name, // Tambahkan ini
            'photo' => $student->photo ?? null
        ];

        return view('pages.student.profile.gantipassword', compact('results'));
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
            'new_password_confirmation' => 'required'
        ], [
            'new_password.confirmed' => 'Konfirmasi password tidak sesuai',
            'new_password.min' => 'Password minimal 8 karakter',
            'current_password.required' => 'Password lama harus diisi',
            'new_password.required' => 'Password baru harus diisi',
            'new_password_confirmation.required' => 'Konfirmasi password harus diisi'
        ]);

        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password lama tidak sesuai']);
        }

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return redirect()->route('student.profile')
            ->with('success', 'Password berhasil diperbarui');
    }

    public function edit()
    {
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->first();

        if (!$student) {
            return redirect()->back()->with('error', 'Data mahasiswa tidak ditemukan');
        }

        // Get study program and campus info
        $studyProgram = StudyProgram::find($student->study_program_id);
        $campus = Campus::find($studyProgram->campus_id);
        
        // Get class year info
        $internshipClass = InternshipClass::find($student->internship_class_id);
        $classYear = $internshipClass ? ClassYear::find($internshipClass->class_year_id) : null;

        $results = [
            'namaLengkap' => $user->name,
            'email' => $user->email,
            'nim' => $student->nim,
            'telp' => $student->telp,
            'prodi' => $studyProgram->name,
            'campus' => $campus->name,
            'angkatan' => $classYear ? $classYear->class_year : '-',
            'photo' => $student->photo ?? null
        ];

        return view('pages.student.profile.edit', compact('results'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->first();

        if (!$student) {
            return redirect()->back()->with('error', 'Data mahasiswa tidak ditemukan');
        }

        $request->validate([
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'telp' => [
                'required',
                'regex:/^0\d{9,12}$/', // Must start with 0 and be 10-13 digits total
                Rule::unique('students', 'telp')->ignore($student->id)
            ],
        ], [
            'telp.regex' => 'Nomor telepon harus dimulai dengan 0 dan terdiri dari 10-13 digit',
            'telp.required' => 'Nomor telepon wajib diisi',
            'telp.unique' => 'Nomor telepon sudah digunakan',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah digunakan',
        ]);

        $user->email = $request->email;
        $user->save();

        $student->telp = $request->telp;
        $student->save();

        return redirect()->route('student.profile')->with('success', 'Profil berhasil diperbarui');
    }
}
