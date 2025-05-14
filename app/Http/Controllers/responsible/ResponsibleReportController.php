<?php

namespace App\Http\Controllers\Responsible;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\StudentGrade;
use App\Models\Presence;
use App\Models\Stase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ResponsibleReportController extends Controller
{
    /**
     * Display the reports and summary page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Get logged in PIC's stase
        $stase = Stase::where('responsible_user_id', Auth::id())->first();
        
        // Base query with relationships
        $studentsQuery = Student::with(['user', 'internshipClass.classYear', 'studyProgram.campus'])
            ->whereHas('presences', function($query) use ($stase) {
                $query->whereHas('presenceSession.schedule', function($q) use ($stase) {
                    $q->where('stase_id', $stase->id);
                });
            });

        // Apply filters if they exist
        if ($request->filled('study_program')) {
            $studentsQuery->where('study_program_id', $request->study_program);
        }

        if ($request->filled('campus')) {
            $studentsQuery->whereHas('studyProgram', function($query) use ($request) {
                $query->where('campus_id', $request->campus);
            });
        }

        if ($request->filled('class_year')) {
            $studentsQuery->whereHas('internshipClass', function($query) use ($request) {
                $query->where('class_year_id', $request->class_year);
            });
        }

        // Get filtered students
        $students = $studentsQuery->get();

        // Calculate attendance percentage for each student
        foreach($students as $student) {
            $totalSessions = Presence::whereHas('presenceSession.schedule', function($query) use ($stase) {
                $query->where('stase_id', $stase->id);
            })->where('student_id', $student->id)->count();
            
            $presentSessions = Presence::whereHas('presenceSession.schedule', function($query) use ($stase) {
                $query->where('stase_id', $stase->id);
            })->where('student_id', $student->id)
              ->where('status', 'present')
              ->count();
            
            $student->attendance_percentage = $totalSessions > 0 ? 
                round(($presentSessions / $totalSessions) * 100) : 0;
        }

        // Get average grades for filtered students
        $averageGrades = StudentGrade::whereIn('student_id', $students->pluck('id'))
            ->where('stase_id', $stase->id)
            ->select('student_id', DB::raw('AVG(avg_grades) as average_grade'))
            ->groupBy('student_id')
            ->with('student.user', 'student.studyProgram.campus')
            ->orderBy('average_grade', 'desc')
            ->take(4)
            ->get();

        // Get top performers from filtered students
        $topPerformers = StudentGrade::whereIn('student_id', $students->pluck('id'))
            ->where('stase_id', $stase->id)
            ->select('student_id', DB::raw('AVG(avg_grades) as average_grade'))
            ->groupBy('student_id')
            ->with('student.user')
            ->orderBy('average_grade', 'desc')
            ->take(5)
            ->get();

        // Calculate overall average for filtered students
        $overallAverage = StudentGrade::whereIn('student_id', $students->pluck('id'))
            ->where('stase_id', $stase->id)
            ->avg('avg_grades') ?? 0;

        // Get unique study programs, campuses and class years for filters
        $studyPrograms = $students->pluck('studyProgram')->unique();
        $campuses = $students->pluck('studyProgram.campus')->unique();
        $classYears = $students->pluck('internshipClass.classYear')->unique();

        return view('pages.responsible.reports.index', compact(
            'students',
            'averageGrades',
            'topPerformers',
            'overallAverage',
            'studyPrograms',
            'campuses',
            'classYears',
            'stase'
        ));
    }
}