<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class StudentScheduleController extends Controller
{
    public function index()
    {
        $student = Auth::user()->student;

        if (!$student || !$student->internshipClass) {
            return view('pages.student.schedule.index', [
                'todaySchedules' => [],
                'schedules' => []
            ]);
        }

        // Get today's schedules
        $todaySchedules = $student->internshipClass->schedules()
            ->with(['stase.responsibleUsers.user', 'internshipClass'])
            ->whereDate('start_date', '<=', Carbon::today())
            ->whereDate('end_date', '>=', Carbon::today())
            ->get();

        // Get all schedules for table
        $schedules = $student->internshipClass->schedules()
            ->with(['stase.responsibleUsers.user', 'internshipClass'])
            ->orderBy('start_date')
            ->get();

        return view('pages.student.schedule.index', compact('todaySchedules', 'schedules'));
    }

    public function getAllSchedules(Request $request)
    {
        $student = Auth::user()->student;

        if (!$student || !$student->internshipClass) {
            return response()->json([
                'success' => true,
                'schedules' => []
            ]);
        }

        $schedules = $student->internshipClass->schedules()
            ->with(['stase.responsibleUsers.user', 'internshipClass'])
            ->orderBy('start_date')
            ->get()
            ->map(function($schedule) {
                $startDate = Carbon::parse($schedule->start_date)->locale('id');
                $endDate = Carbon::parse($schedule->end_date)->locale('id');

                return [
                    'stase' => $schedule->stase->name,
                    'class' => $schedule->internshipClass->name,
                    'startDateFormatted' => $startDate->translatedFormat('j F Y'),
                    'endDateFormatted' => $endDate->translatedFormat('j F Y'),
                    'responsibleUser' => $schedule->stase->responsibleUsers->first()?->user->name ?? '-',
                    'start_date' => $schedule->start_date,
                    'end_date' => $schedule->end_date
                ];
            });

        return response()->json([
            'success' => true,
            'schedules' => $schedules
        ]);
    }

    public function getSchedulesByDate(Request $request)
    {
        $date = $request->date ?? now()->format('Y-m-d');
        $student = Auth::user()->student;

        if (!$student || !$student->internshipClass) {
            return response()->json([
                'success' => true,
                'schedules' => []
            ]);
        }

        $schedules = $student->internshipClass->schedules()
            ->whereDate('start_date', '<=', $date)
            ->whereDate('end_date', '>=', $date)
            ->with(['stase.responsibleUsers.user', 'internshipClass'])
            ->get()
            ->map(function($schedule) {
                return [
                    'stase' => $schedule->stase->name,
                    'class' => $schedule->internshipClass->name,
                    'responsibleUser' => $schedule->stase->responsibleUsers->first()?->user->name ?? '-'
                ];
            });

        return response()->json([
            'success' => true,
            'schedules' => $schedules
        ]);
    }

    public function getFilteredSchedules(Request $request)
    {
        $student = Auth::user()->student;
        $filter = $request->filter;
        $now = now();

        if (!$student || !$student->internshipClass) {
            return response()->json([
                'success' => true,
                'schedules' => []
            ]);
        }

        $query = $student->internshipClass->schedules()
            ->with(['stase.responsibleUsers.user', 'internshipClass']);

        switch ($filter) {
            case 'this-month':
                $query->whereMonth('start_date', $now->month)
                     ->whereYear('start_date', $now->year);
                break;
            case 'this-week':
                $query->whereBetween('start_date', [
                    $now->copy()->startOfWeek()->format('Y-m-d'),
                    $now->copy()->endOfWeek()->format('Y-m-d')
                ]);
                break;
            case 'next-week':
                $nextWeekStart = $now->copy()->addWeek()->startOfWeek();
                $nextWeekEnd = $now->copy()->addWeek()->endOfWeek();
                $query->whereBetween('start_date', [
                    $nextWeekStart->format('Y-m-d'),
                    $nextWeekEnd->format('Y-m-d')
                ]);
                break;
            case 'next-month':
                $nextMonth = $now->copy()->addMonth();
                $query->whereMonth('start_date', $nextMonth->month)
                     ->whereYear('start_date', $nextMonth->year);
                break;
        }

        $schedules = $query->get()
            ->map(function($schedule) {
                $startDate = Carbon::parse($schedule->start_date)->locale('id');
                $endDate = Carbon::parse($schedule->end_date)->locale('id');

                return [
                    'stase' => $schedule->stase->name,
                    'class' => $schedule->internshipClass->name,
                    'startDateFormatted' => $startDate->translatedFormat('j F Y'),
                    'endDateFormatted' => $endDate->translatedFormat('j F Y'),
                    'responsibleUser' => $schedule->stase->responsibleUsers->first()?->user->name ?? '-',
                    'start_date' => $schedule->start_date,
                    'end_date' => $schedule->end_date
                ];
            });

        return response()->json([
            'success' => true,
            'schedules' => $schedules
        ]);
    }
}