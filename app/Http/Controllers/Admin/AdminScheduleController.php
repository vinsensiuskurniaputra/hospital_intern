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
        $today = now()->format('Y-m-d');
        
        $allSchedules = Schedule::with([
            'internshipClass.classYear',
            'stase.departement',
            'stase.responsibleUsers.user',
        ])->paginate(10);

        $filteredSchedules = Schedule::with([
            'internshipClass',
            'stase.departement'
        ])
        ->whereDate('start_date', '<=', $today)
        ->whereDate('end_date', '>=', $today)
        ->get();

        // Get departments for filter dropdown
        $departments = Departement::all();
        $internshipClasses = InternshipClass::with('classYear')->get();
        $responsibles = ResponsibleUser::with('user')->get();

        return view('pages.admin.schedule.index', compact(
            'allSchedules',
            'filteredSchedules',
            'departments',
            'internshipClasses',
            'responsibles'
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
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date'
        ], [
            'internship_class_id.required' => 'Kelas Magang harus dipilih',
            'stase_id.required' => 'Stase harus dipilih',
            'start_date.required' => 'Tanggal Mulai harus diisi',
            'end_date.required' => 'Tanggal Selesai harus diisi',
            'end_date.after_or_equal' => 'Tanggal Selesai harus setelah atau sama dengan Tanggal Mulai'
        ]);

        try {
            // Buat schedule langsung dari validated data
            Schedule::create($validated);

            return redirect()
                ->route('presences.schedules.index')
                ->with('success', 'Jadwal berhasil dibuat');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat membuat jadwal: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Schedule $schedule)
    {
        return view('pages.admin.schedule.show', compact('schedule'));
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
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        try {
            $schedule->update($validated);

            return redirect()
                ->route('presences.schedules.index')
                ->with('success', 'Jadwal berhasil diperbarui');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui jadwal');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Schedule $schedule)
    {
        try {
            $schedule->delete();

            return redirect()
                ->route('presences.schedules.index')
                ->with('success', 'Jadwal berhasil dihapus');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat menghapus jadwal');
        }
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

    public function filterByDate(Request $request)
    {
        try {
            $date = $request->date;

            $schedules = Schedule::with([
                'internshipClass',
                'stase.departement'
            ])
            ->whereDate('start_date', '<=', $date)
            ->whereDate('end_date', '>=', $date)
            ->get();

            return response()->json([
                'success' => true,
                'schedules' => $schedules
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error filtering schedules'
            ]);
        }
    }
}
