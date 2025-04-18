<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StudentDashboardController extends Controller
{
    public function index()
    {
        // Perubahan path view untuk menyesuaikan dengan struktur folder baru
        return view('pages.student.dashboard.index');
    }
}