<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\StudentGrade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentGradeController extends Controller
{
    public function index()
    {
        // Get currently authenticated user's student record
        $student = Student::where('user_id', Auth::id())->first();
        
        // Initialize empty collection
        $grades = collect();
        
        if ($student) {
            // Properly load grades with relationships
            $grades = StudentGrade::with(['stase', 'stase.responsibleUsers', 'stase.responsibleUsers.user'])
                ->where('student_id', $student->id)
                ->get();
        }
        
        return view('pages.student.grades.index', compact('grades'));
    }
    
    public function exportPdf()
    {
        // PDF export functionality can be implemented here
        return redirect()->back()->with('info', 'Fitur ekspor PDF sedang dalam pengembangan');
    }
}