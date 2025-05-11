<?php

namespace App\Http\Controllers\Responsible;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Stase;
use App\Models\Departement;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResponsibleScheduleController extends Controller
{
    public function index()
    {
        // Get current user's stase
        $userStase = Stase::where('responsible_user_id', Auth::id())->first();
        
        if (!$userStase) {
            return view('pages.responsible.schedule.index', [
                'todaySchedules' => [],
                'schedules' => [],
                'departments' => [],
                'students' => [],
                'currentClass' => null
            ]);
        }

        // Get today's schedules
        $todaySchedules = Schedule::with(['internshipClass', 'stase.departement'])
            ->where('stase_id', $userStase->id)
            ->whereDate('date_schedule', Carbon::today())
            ->get()
            ->map(function($schedule) {
                return [
                    'class_name' => $schedule->internshipClass->name ?? 'Unknown Class',
                    'time_range' => '10:00 - 14:00', // Default time if not available
                    'department' => $schedule->stase->departement->name ?? 'Unknown Department'
                ];
            });

        // Get all schedules for table
        $schedules = Schedule::with(['internshipClass', 'stase.departement', 'stase.responsibleUser'])
            ->where('stase_id', $userStase->id)
            ->orderBy('date_schedule', 'desc')
            ->get();

        // Get all departments for filter
        $departments = Departement::all();

        // Get students for current class (using first schedule's class as example)
        $currentSchedule = $schedules->first();
        $students = [];
        $currentClass = null;

        if ($currentSchedule) {
            $currentClass = $currentSchedule->internshipClass;
            $students = Student::where('internship_class_id', $currentSchedule->internship_class_id)
                ->take(8) // Limit to 8 students as example
                ->get();
        }

        return view('pages.responsible.schedule.index', [
            'todaySchedules' => $todaySchedules,
            'schedules' => $schedules,
            'departments' => $departments,
            'students' => $students,
            'currentClass' => $currentClass
        ]);
    }

    public function getSchedules(Request $request)
    {
        try {
            $date = $request->get('date');
            $userStase = Stase::where('responsible_user_id', Auth::id())->first();

            if (!$userStase) {
                return response()->json([
                    'success' => true,
                    'schedules' => []
                ]);
            }

            // Debug log
            \Log::info('Fetching schedules with params:', [
                'date' => $date,
                'stase_id' => $userStase->id
            ]);

            $schedules = Schedule::with(['internshipClass', 'stase.departement'])
                ->whereDate('date_schedule', $date)
                ->get();

            // Debug log
            \Log::info('Found schedules:', [
                'count' => $schedules->count(),
                'schedules' => $schedules->toArray()
            ]);

            $formattedSchedules = $schedules->map(function($schedule) {
                return [
                    'class_name' => $schedule->internshipClass->name ?? 'Unknown Class',
                    'time_range' => Carbon::parse($schedule->start_date)->format('H:i') . ' - ' . 
                                  Carbon::parse($schedule->end_date)->format('H:i'),
                    'department' => $schedule->stase->departement->name ?? 'Unknown Department',
                    'debug_info' => [
                        'schedule_id' => $schedule->id,
                        'date_schedule' => $schedule->date_schedule,
                        'stase_id' => $schedule->stase_id
                    ]
                ];
            });

            return response()->json([
                'success' => true,
                'schedules' => $formattedSchedules,
                'debug' => [
                    'requested_date' => $date,
                    'raw_count' => $schedules->count(),
                    'formatted_count' => $formattedSchedules->count(),
                    'user_stase_id' => $userStase->id
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Schedule loading error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memuat jadwal',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }
}