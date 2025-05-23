<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
<<<<<<< HEAD
use App\Models\Campus;
use App\Models\ClassYear;
use App\Models\InternshipClass;
use App\Models\Student;
use App\Models\StudyProgram;
use App\Models\User;
use Illuminate\Http\Request;

class StudentProfileController extends Controller
{
    public function index() {
        $userId = auth()->user()->id;
        $studentId = Student::where('user_id', $userId)->first('id');

        $data1 = User::find($userId, ['name', 'email']);
        $data2 = Student::where('user_id', $userId)->first(['nim', 'telp']);
        
        $prodi_id = Student::where('user_id', $userId)->first('study_program_id');
        $prodiName = StudyProgram::find($prodi_id, 'name');

        $campusId =StudyProgram::find($prodi_id, 'campus_id');
        $campusId = $campusId[0]->campus_id;
        $campusName = Campus::where('id', $campusId)->first('name');
        
        $internshipClassId = Student::where('user_id', $userId)->first('internship_class_id');
        $classYearId = InternshipClass::find($internshipClassId, 'class_year_id');
        $classYearId = $classYearId[0]->class_year_id;
        $classYear = ClassYear::where(  'id', $classYearId)->first('class_year');
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
}
=======
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentProfileController extends Controller
{
    /**
     * Display the student profile page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->first();
        
        return view('pages.student.profile.index', compact('user', 'student'));
    }
}
>>>>>>> 9c82e193c40f54f6e87e9d789a0cf88946e1c3e4
