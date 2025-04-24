<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Stase;
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
         ])->paginate(10); // Changed from get() to paginate()

        $departments = Departement::all();
        $responsibles = ResponsibleUser::with('user')->whereHas('user')->get();
        $internshipClasses = InternshipClass::with('classYear')->get();

        return view('pages.admin.schedule.index', compact(
            'schedules',
            'departments',
            'responsibles',
            'internshipClasses'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $internshipClasses = InternshipClass::all();
        $departments = Departement::all();
        $stases = Stase::all();

        return view('pages.admin.schedule.create', compact(
            'internshipClasses',
            'departments',
            'stases'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'internship_class_id' => 'required|exists:internship_classes,id',
            'stase_id' => 'required|exists:stases,id',
            'departement_id' => 'required|exists:departements,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
        ]);

        // Add the current date as date_schedule
        $validated['date_schedule'] = now()->toDateString();

        Schedule::create($validated);

        return redirect()
            ->route('admin.schedules.index')
            ->with('success', 'Jadwal berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function View(Schedule $schedule)
    {
        return view('pages.admin.schedule.view', compact('schedule'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Schedule $schedule)
    {
        $internshipClasses = InternshipClass::all();
        $departments = Departement::all();
        $stases = Stase::all();

        return view('pages.admin.schedule.edit', compact(
            'schedule',
            'internshipClasses',
            'departments',
            'stases'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Schedule $schedule)
    {
        $validated = $request->validate([
            'internship_class_id' => 'required|exists:internship_classes,id',
            'stase_id' => 'required|exists:stases,id',
            'departement_id' => 'required|exists:departements,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
        ]);

        $schedule->update($validated);

        return redirect()
            ->route('admin.schedules.index')
            ->with('success', 'Schedule updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Schedule $schedule)
    {
        $schedule->delete();

        return redirect()
            ->route('admin.schedules.index')
            ->with('success', 'Jadwal berhasil dihapus');
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
