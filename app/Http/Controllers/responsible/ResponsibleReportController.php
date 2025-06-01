<?php

namespace App\Http\Controllers\Responsible;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\StudentGrade;
use App\Models\Presence;
use App\Models\Stase;
use App\Models\InternshipClass;
use App\Models\ResponsibleUser;
use App\Models\Schedule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; // Tambahkan import ini

class ResponsibleReportController extends Controller
{
    /**
     * Display the reports and summary page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Get the responsible user record
        $responsibleUser = ResponsibleUser::where('user_id', Auth::id())->first();
        
        if (!$responsibleUser) {
            return redirect()->back()->with('error', 'No responsible user record found');
        }
        
        // Get only stases assigned to this responsible user
        $stases = Stase::whereHas('responsibleUsers', function($query) use ($responsibleUser) {
            $query->where('responsible_user_id', $responsibleUser->id);
        })->get();
        
        // If no stases found, handle that case
        if ($stases->isEmpty()) {
            Log::warning('User ID ' . Auth::id() . ' has no assigned stases.');
            return view('pages.responsible.reports.index', [
                'students' => collect(),
                'stases' => collect(),
                'internshipClasses' => collect(),
                'averageGrades' => collect(),
                'topPerformers' => collect(),
                'overallAverage' => 0,
                'classYears' => collect(),
                'currentYear' => date('Y'),
                'staseName' => 'None'
            ])->with('error', 'No stases assigned to your account');
        }
        
        // Default to the first stase assigned to the user if no stase is selected
        $stase = $request->filled('stase') ? 
                Stase::whereIn('id', $stases->pluck('id'))
                     ->where('id', $request->stase)
                     ->first() : 
                $stases->first();
        
        // Get only internship classes that have schedules with the user's stases
        $internshipClasses = InternshipClass::whereHas('schedules', function($query) use ($stases) {
            $query->whereIn('stase_id', $stases->pluck('id'));
        })->get();
        
        // FIXED: Modified query to show all students in the selected stase's classes
        // Base query with relationships
        $studentsQuery = Student::with(['user', 'internshipClass.classYear', 'studyProgram.campus'])
            ->whereHas('internshipClass', function($query) use ($stase) {
                $query->whereHas('schedules', function($q) use ($stase) {
                    $q->where('stase_id', $stase->id);
                });
            });
    
        // Apply search filter if it exists
        if ($request->filled('search')) {
            $search = $request->search;
            $studentsQuery->where(function($query) use ($search) {
                $query->where('nim', 'like', "%{$search}%")
                      ->orWhereHas('user', function($q) use ($search) {
                          $q->where('name', 'like', "%{$search}%");
                      });
            });
        }
        
        // Apply internship class filter if it exists
        if ($request->filled('internship_class')) {
            $studentsQuery->where('internship_class_id', $request->internship_class);
        }

        // Apply class year filter if it exists
        if ($request->filled('class_year')) {
            $studentsQuery->whereHas('internshipClass', function($query) use ($request) {
                $query->where('class_year_id', $request->class_year);
            });
        }

        // Get filtered students
        $students = $studentsQuery->get();

        // Get active stase for filtering
        $activeStase = $request->filled('stase') ? 
                      Stase::whereIn('id', $stases->pluck('id'))->find($request->stase) : 
                      $stase;

        // Calculate attendance percentage for each student
        foreach($students as $student) {
            $totalSessions = Presence::whereHas('presenceSession.schedule', function($query) use ($activeStase) {
                $query->where('stase_id', $activeStase->id);
            })->where('student_id', $student->id)->count();
            
            $presentSessions = Presence::whereHas('presenceSession.schedule', function($query) use ($activeStase) {
                $query->where('stase_id', $activeStase->id);
            })->where('student_id', $student->id)
              ->where('status', 'present')
              ->count();
            
            $student->attendance_percentage = $totalSessions > 0 ? 
                round(($presentSessions / $totalSessions) * 100) : 0;
        }

        // Get average grades for filtered students
        $averageGrades = StudentGrade::whereIn('student_id', $students->pluck('id'))
            ->where('stase_id', $activeStase->id)
            ->select('student_id', DB::raw('AVG(avg_grades) as average_grade'))
            ->groupBy('student_id')
            ->with('student.user', 'student.studyProgram.campus')
            ->orderBy('average_grade', 'desc')
            ->take(4)
            ->get();

        // Get top performers from filtered students
        $topPerformers = StudentGrade::whereIn('student_id', $students->pluck('id'))
            ->where('stase_id', $activeStase->id)
            ->select('student_id', DB::raw('AVG(avg_grades) as average_grade'))
            ->groupBy('student_id')
            ->with('student.user')
            ->orderBy('average_grade', 'desc')
            ->take(5)
            ->get();

        // Calculate overall average for filtered students
        $overallAverage = StudentGrade::whereIn('student_id', $students->pluck('id'))
            ->where('stase_id', $activeStase->id)
            ->avg('avg_grades') ?? 0;

        // Get unique class years for filters
        $classYears = $students->map(function($student) {
            return $student->internshipClass->classYear ?? null;
        })->filter()->unique('id');

        // Update the Yellow Header information for the Performa Terbaik card
        $currentYear = date('Y');
        $staseName = $activeStase->name ?? 'Semua';

        // Calculate grade distribution for all students in current stase, regardless of filters
        $gradeDistribution = StudentGrade::whereHas('student.internshipClass', function($query) use ($activeStase) {
                $query->whereHas('schedules', function($q) use ($activeStase) {
                    $q->where('stase_id', $activeStase->id);
                });
            })
            ->where('stase_id', $activeStase->id)
            ->select(
                DB::raw('COUNT(CASE WHEN avg_grades >= 90 THEN 1 END) as above90'),
                DB::raw('COUNT(CASE WHEN avg_grades >= 70 AND avg_grades < 90 THEN 1 END) as above70'),
                DB::raw('COUNT(CASE WHEN avg_grades >= 50 AND avg_grades < 70 THEN 1 END) as above50'),
                DB::raw('COUNT(*) as total')
            )
            ->first();

        // Calculate percentages
        $totalStudents = $gradeDistribution->total ?: 1; // Prevent division by zero
        $gradePercentages = [
            'above90' => round(($gradeDistribution->above90 / $totalStudents) * 100, 1),
            'above70' => round(($gradeDistribution->above70 / $totalStudents) * 100, 1),
            'above50' => round(($gradeDistribution->above50 / $totalStudents) * 100, 1)
        ];

        // Add to compact array
        return view('pages.responsible.reports.index', compact(
            'students',
            'averageGrades',
            'topPerformers',
            'overallAverage',
            'classYears',
            'stases',
            'internshipClasses',
            'stase',
            'currentYear',
            'staseName',
            'gradePercentages' // Tambahkan ini
        ));
    }
}