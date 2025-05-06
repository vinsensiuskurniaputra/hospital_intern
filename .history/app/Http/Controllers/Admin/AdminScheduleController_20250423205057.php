<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\InternshipClass;
use App\Models\Departement;
use App\Models\ResponsibleUser;
use Illuminate\Http\Request;

class AdminScheduleController extends Controller
{
    public function index()
    {
        $schedules = Schedule::with([
            'internshipClass.classYear',
            'stase',
            'departement',
            'responsibleUser.user'
        ])->get();

        return view('pages.admin.schedule.index', compact('schedules'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Schedule $schedule)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Schedule $schedule)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Schedule $schedule)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Schedule $schedule)
    {
        //
    }

    public function filter(Request $request)
    {
        $query = Schedule::query();

        if ($request->has('department') && $request->department != '') {
            $query->where('departement_id', $request->department);
        }

        if ($request->has('instructor') && $request->instructor != '') {
            $query->where('responsible_user_id', $request->instructor);
        }

        if ($request->has('year') && $request->year != '') {
            $query->whereHas('internshipClass.classYear', function($q) use ($request) {
                $q->where('id', $request->year);
            });
        }

        $schedules = $query->with([
            'internshipClass.classYear',
            'stase',
            'departement',
            'responsibleUser.user'
        ])->get();
        
        return view('components.admin.schedule.table', compact('schedules'))->render();
    }
}
