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
            'stase.responsibleUser.user'
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

    public function show(Schedule $schedule)
    {
        return view('pages.admin.schedule.show', compact('schedule'));
    }

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
        $query = Schedule::with([
            'internshipClass.classYear',
            'stase.departement',
            'stase.responsibleUser.user'
        ]);

        // Filter by department
        if ($request->has('departemen') && $request->departemen) {
            $query->whereHas('stase.departement', function($q) use ($request) {
                $q->where('id', $request->departemen);
            });
        }

        // Filter by year
        if ($request->has('tahun') && $request->tahun) {
            $query->whereHas('internshipClass', function($q) use ($request) {
                $q->where('id', $request->tahun);
            });
        }

        // Filter by responsible
        if ($request->has('pembimbing') && $request->pembimbing) {
            $query->whereHas('stase.responsibleUser', function($q) use ($request) {
                $q->where('id', $request->pembimbing);
            });
        }

        // Filter by search term
        if ($request->has('search') && $request->search) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->whereHas('internshipClass', function($q) use ($searchTerm) {
                    $q->where('name', 'like', "%{$searchTerm}%");
                })
                ->orWhereHas('stase', function($q) use ($searchTerm) {
                    $q->where('name', 'like', "%{$searchTerm}%");
                })
                ->orWhereHas('stase.departement', function($q) use ($searchTerm) {
                    $q->where('name', 'like', "%{$searchTerm}%");
                })
                ->orWhereHas('stase.responsibleUser.user', function($q) use ($searchTerm) {
                    $q->where('name', 'like', "%{$searchTerm}%");
                });
            });
        }

        $schedules = $query->get();

        return response()->json([
            'success' => true,
            'schedules' => $schedules
        ]);
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