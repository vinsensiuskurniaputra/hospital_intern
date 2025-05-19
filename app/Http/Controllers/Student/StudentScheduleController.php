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

        $schedules = $student->internshipClass->schedules()
            ->with(['stase', 'stase.responsibleStases.responsibleUser.user'])
            ->orderBy('start_date')
            ->get();

        return view('pages.student.schedule.index', compact('schedules'));
    }

    public function getSchedulesByDate(Request $request)
    {
        $date = $request->date ?? now()->format('Y-m-d');
        $student = Auth::user()->student;

        $schedules = $student->internshipClass->schedules()
            ->whereDate('start_date', '<=', $date)
            ->whereDate('end_date', '>=', $date)
            ->whereHas('stase.responsibleStases')
            ->with(['stase', 'stase.responsibleStases.responsibleUser.user'])
            ->get()
            ->map(function($schedule) {
                $startDate = Carbon::parse($schedule->start_date)->locale('id');
                $endDate = Carbon::parse($schedule->end_date)->locale('id');

                return [
                    'stase' => $schedule->stase->name,
                    'class' => $schedule->internshipClass->name,
                    'startDateFormatted' => $startDate->translatedFormat('j F Y'),
                    'endDateFormatted' => $endDate->translatedFormat('j F Y'),
                    'responsibleUser' => $schedule->stase->responsibleStases->first()?->responsibleUser->user->name ?? '-'
                ];
            });

        return response()->json($schedules);
    }

    public function getFilteredSchedules(Request $request)
    {
        $student = Auth::user()->student;
        $filter = $request->filter;
        $now = now();

        $query = $student->internshipClass->schedules()
            ->whereHas('stase.responsibleStases')
            ->with(['stase', 'stase.responsibleStases.responsibleUser.user']);

        switch ($filter) {
            case 'this-month':
                $query->whereMonth('start_date', $now->month)
                     ->whereYear('start_date', $now->year);
                break;
            case 'this-week':
                $query->whereBetween('start_date', [
                    $now->startOfWeek()->format('Y-m-d'),
                    $now->endOfWeek()->format('Y-m-d')
                ]);
                break;
            case 'next-week':
                $query->whereBetween('start_date', [
                    $now->addWeek()->startOfWeek()->format('Y-m-d'),
                    $now->endOfWeek()->format('Y-m-d')
                ]);
                break;
            case 'next-month':
                $query->whereMonth('start_date', $now->addMonth()->month)
                     ->whereYear('start_date', $now->year);
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
                    'responsibleUser' => $schedule->stase->responsibleStases->first()?->responsibleUser->user->name ?? '-'
                ];
            });

        return response()->json($schedules);
    }
}
