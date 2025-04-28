<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StudentScheduleController extends Controller
{
    public function index()
    {
        return view('pages.student.schedule.index');
    }
}