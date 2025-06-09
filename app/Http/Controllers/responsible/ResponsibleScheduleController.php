<?php

namespace App\Http\Controllers\Responsible;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Stase;
use App\Models\Departement;
use App\Models\Student;
use App\Models\ResponsibleUser;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResponsibleScheduleController extends Controller
{
    public function index()
    {
        // Get responsible user data
        $responsibleUser = ResponsibleUser::where('user_id', Auth::id())->first();
        
        if (!$responsibleUser) {
            return view('pages.responsible.schedule.index', [
                'todaySchedules' => [],
                'schedules' => [],
                'departments' => [],
                'students' => [],
                'currentClass' => null
            ]);
        }

        // Get stase IDs
        $staseIds = $responsibleUser->stases->pluck('id');

        // Fix the query for today's schedules to properly check date range
        $todaySchedules = Schedule::with(['internshipClass.classYear', 'stase.departement'])
            ->whereIn('stase_id', $staseIds)
            ->whereDate('start_date', '<=', Carbon::today())
            ->whereDate('end_date', '>=', Carbon::today())
            ->get();

        // Get all schedules for table, ordered by start_date
        $schedules = Schedule::with(['internshipClass', 'stase.departement'])
            ->whereIn('stase_id', $staseIds)
            ->orderBy('start_date', 'asc')
            ->get();

        // Get all departments for filter
        $departments = Departement::all();

        // Get students for current class
        $currentSchedule = $schedules->first();
        $students = [];
        $currentClass = null;

        if ($currentSchedule) {
            $currentClass = $currentSchedule->internshipClass;
            // Load stase relationship
            $currentSchedule->load('stase');
            
            $students = Student::with(['user', 'studyProgram'])
                ->where('internship_class_id', $currentSchedule->internship_class_id)
                ->get();
        }

        // Get stases for filter
        $stases = Stase::whereIn('id', $staseIds)->get();
        
        // Get internship classes for filter
        $internshipClasses = Schedule::whereIn('stase_id', $staseIds)
            ->with('internshipClass')
            ->get()
            ->pluck('internshipClass')
            ->unique('id');

        return view('pages.responsible.schedule.index', 
            compact('todaySchedules', 'schedules', 'departments', 'students', 
                    'currentClass', 'currentSchedule', 'stases', 'internshipClasses')
        );
    }

    public function getSchedules(Request $request) 
    {
        try {
            $date = $request->date;
            
            // Get responsible user data
            $responsibleUser = ResponsibleUser::where('user_id', Auth::id())->first();
            
            if (!$responsibleUser) {
                return response()->json([
                    'success' => true,
                    'schedules' => []
                ]);
            }

            // Get stase IDs
            $staseIds = $responsibleUser->stases->pluck('id');
            
            // Only fetch schedules for stases assigned to the responsible user
            $schedules = Schedule::with(['stase.departement', 'internshipClass.classYear'])
                ->whereIn('stase_id', $staseIds)
                ->whereDate('start_date', '<=', $date)
                ->whereDate('end_date', '>=', $date)
                ->get();
                
            return response()->json([
                'success' => true,
                'schedules' => $schedules->map(function($schedule) {
                    return [
                        'class_name' => $schedule->internshipClass->name ?? 'N/A',
                        'academic_year' => $schedule->internshipClass->classYear->class_year ?? null,
                        'stase_name' => $schedule->stase->name ?? 'N/A',
                        'department' => $schedule->stase->departement->name ?? 'N/A',
                    ];
                })
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function filter(Request $request)
    {
        try {
            $request->validate([
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date'
            ]);

            // Get responsible user data
            $responsibleUser = ResponsibleUser::where('user_id', Auth::id())->first();
            
            if (!$responsibleUser) {
                return response()->json([
                    'success' => true,
                    'schedules' => []
                ]);
            }

            // Get stase IDs
            $staseIds = $responsibleUser->stases->pluck('id');

            $schedules = Schedule::with(['internshipClass', 'stase.departement'])
                ->whereIn('stase_id', $staseIds)
                ->whereBetween('start_date', [$request->start_date, $request->end_date])
                ->orderBy('start_date', 'asc')
                ->get();

            return response()->json([
                'success' => true,
                'schedules' => $schedules
            ]);

        } catch (\Exception $e) {
            \Log::error('Schedule filter error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getClassDetails(Request $request)
    {
        try {
            $request->validate([
                'stase_id' => 'required|exists:stases,id',
                'class_id' => 'required|exists:internship_classes,id',
            ]);

            // Update query untuk include campus dan photo
            $students = Student::with([
                'user:id,name,photo_profile_url', 
                'studyProgram.campus:id,name',
                'StudyProgram:id,name,campus_id'
            ])
            ->where('internship_class_id', $request->class_id)
            ->get();

            return response()->json([
                'success' => true,
                'students' => $students
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getClassesForStase(Request $request)
    {
        try {
            $request->validate([
                'stase_id' => 'required|exists:stases,id',
            ]);

            // Add the class_year relationship to the query
            $classes = \App\Models\InternshipClass::with('classYear')
                ->whereHas('schedules', function($query) use ($request) {
                    $query->where('stase_id', $request->stase_id);
                })
                ->get();

            return response()->json([
                'success' => true,
                'classes' => $classes
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getStudentDetail(Request $request)
    {
        try {
            $request->validate([
                'student_id' => 'required|exists:students,id',
            ]);

            // Updated to specifically request the correct column names
            $student = Student::with([
                'user:id,name,email,photo_profile_url', 
                'studyProgram.campus:id,name',
                'studyProgram:id,name,campus_id',
                'internshipClass',
                'internshipClass.classYear:id,class_year' // Request the class_year column specifically
            ])
            ->where('id', $request->student_id)
            ->first();

            if (!$student) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data mahasiswa tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'student' => $student
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}