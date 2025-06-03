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

        // Get today's schedules for all assigned stases
        $todaySchedules = Schedule::with(['internshipClass', 'stase.departement'])
            ->whereIn('stase_id', $staseIds)
            ->whereDate('start_date', Carbon::today())
            ->get()
            ->map(function($schedule) {
                return [
                    'class_name' => $schedule->internshipClass->name ?? 'N/A',
                    'stase_name' => $schedule->stase->name ?? 'N/A',
                    'department' => $schedule->stase->departement->name ?? 'N/A'
                ];
            });

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
            $responsibleUser = ResponsibleUser::where('user_id', Auth::id())->first();
            
            if (!$responsibleUser) {
                return response()->json([
                    'success' => true,
                    'schedules' => []
                ]);
            }

            $staseIds = $responsibleUser->stases->pluck('id');

            $schedules = Schedule::with(['internshipClass', 'stase.departement'])
                ->whereIn('stase_id', $staseIds)
                ->whereDate('start_date', $date)
                ->get()
                ->map(function($schedule) {
                    return [
                        'class_name' => $schedule->internshipClass->name ?? 'N/A',
                        'stase_name' => $schedule->stase->name ?? 'N/A',
                        'department' => $schedule->stase->departement->name ?? 'N/A'
                    ];
                });

            return response()->json([
                'success' => true,
                'schedules' => $schedules
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
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
                'studyProgram:id,name,campus_id'
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

            // Get classes that have schedules in this stase
            $classes = Schedule::where('stase_id', $request->stase_id)
                ->with('internshipClass:id,name')
                ->get()
                ->pluck('internshipClass')
                ->unique('id')
                ->values();

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
}