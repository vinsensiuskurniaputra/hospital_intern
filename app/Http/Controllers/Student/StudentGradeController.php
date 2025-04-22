<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StudentGradeController extends Controller
{
    public function index()
    {
        return view('pages.student.grades.index');
    }
}