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
    public function index(Request $request)
    {
        try {
            // Query dasar dengan relasi yang diperlukan
            $query = Schedule::with([
                'internshipClass.classYear',
                'stase.departement',
                'stase.responsibleUsers.user'
            ]);

            // Terapkan filter jika ada
            if ($request->filled('departemen')) {
                $query->whereHas('stase.departement', function($q) use ($request) {
                    $q->where('departements.id', $request->departemen);
                });
            }

            if ($request->filled('tahun')) {
                $query->whereHas('internshipClass.classYear', function($q) use ($request) {
                    $q->where('internship_classes.id', $request->tahun);
                });
            }

            if ($request->filled('pembimbing')) {
                $query->whereHas('stase.responsibleUsers', function($q) use ($request) {
                    $q->where('responsible_users.id', $request->pembimbing);
                });
            }

            if ($request->filled('search')) {
                $searchTerm = $request->search;
                $query->where(function($q) use ($searchTerm) {
                    $q->whereHas('internshipClass', function($sq) use ($searchTerm) {
                        $sq->where('name', 'like', "%{$searchTerm}%");
                    })
                    ->orWhereHas('stase', function($sq) use ($searchTerm) {
                        $sq->where('name', 'like', "%{$searchTerm}%");
                    })
                    ->orWhereHas('stase.departement', function($sq) use ($searchTerm) {
                        $sq->where('name', 'like', "%{$searchTerm}%");
                    });
                });
            }

            // Dapatkan data yang sudah difilter untuk tabel
            $allSchedules = $query->paginate(10)->appends($request->all());

            // Dapatkan jadwal yang aktif hari ini untuk tampilan kalender
            $today = now()->format('Y-m-d');
            $filteredSchedules = Schedule::with([
                'internshipClass.classYear',
                'stase.departement',
                'stase.responsibleUsers.user'
            ])
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today);

            // Terapkan filter yang sama ke filteredSchedules
            if ($request->filled('departemen')) {
                $filteredSchedules->whereHas('stase.departement', function($q) use ($request) {
                    $q->where('departements.id', $request->departemen);
                });
            }

            if ($request->filled('tahun')) {
                $filteredSchedules->whereHas('internshipClass.classYear', function($q) use ($request) {
                    $q->where('internship_classes.id', $request->tahun);
                });
            }

            if ($request->filled('pembimbing')) {
                $filteredSchedules->whereHas('stase.responsibleUsers', function($q) use ($request) {
                    $q->where('responsible_users.id', $request->pembimbing);
                });
            }

            // Eksekusi query filteredSchedules
            $filteredSchedules = $filteredSchedules->get();

            return view('pages.admin.schedule.index', [
                'allSchedules' => $allSchedules,
                'filteredSchedules' => $filteredSchedules,
                'departments' => Departement::all(),
                'internshipClasses' => InternshipClass::with('classYear')->get(),
                'responsibles' => ResponsibleUser::with('user')->get()
            ]);

        } catch (\Exception $e) {
            \Log::error('Index error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memuat data');
        }
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
            // Get the original values before update
            $originalStaseId = $schedule->stase_id;
            $originalStartDate = $schedule->start_date;
            $originalEndDate = $schedule->end_date;

            // Update the schedule
            $schedule->update($validated);

            // Get class name for notification
            $className = $schedule->internshipClass->name;

            // Check what changed and create appropriate notification message
            if ($originalStaseId != $validated['stase_id']) {
                // Stase changed - create notifications for stase change
                $studentMessage = "Stase pada jadwal praktikum \"{$className}\" telah diperbaharui, silahkan periksa halaman jadwal";
                $responsibleMessage = "Jadwal Praktikum \"{$className}\" telah diperbaharui, Pembimbing yang mungkin berkaitan dengan kelas tersebut silahkan periksa halaman jadwal";
            } else if ($originalStartDate != $validated['start_date'] || $originalEndDate != $validated['end_date']) {
                // Period changed - create notifications for period change
                $studentMessage = "Periode pada jadwal praktikum \"{$className}\" telah diperbaharui, silahkan periksa halaman jadwal";
                $responsibleMessage = "Jadwal Praktikum \"{$className}\" telah diperbaharui, Pembimbing yang mungkin berkaitan dengan kelas tersebut silahkan periksa halaman jadwal";
            }

            // If there were changes that require notification
            if (isset($studentMessage)) {
                // Create notification for students (user_id = 2)
                Notification::create([
                    'user_id' => 2,
                    'title' => 'Jadwal Praktikum Diperbaharui',
                    'message' => $studentMessage,
                    'type' => 'info',
                    'is_read' => 0,
                    'icon' => 'bi bi-calendar-event'
                ]);

                // Create notification for responsible users (user_id = 3)
                Notification::create([
                    'user_id' => 3,
                    'title' => 'Perubahan Jadwal Praktikum',
                    'message' => $responsibleMessage,
                    'type' => 'warning',
                    'is_read' => 0,
                    'icon' => 'bi bi-calendar-event'
                ]);
            }

            return redirect()
                ->route('presences.schedules.index')
                ->with('success', 'Jadwal berhasil diperbarui');
        } catch (\Exception $e) {
            \Log::error('Update schedule error: ' . $e->getMessage());
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
        try {
            // Query for allSchedules
            $query = Schedule::with([
                'internshipClass.classYear',
                'stase.departement',
                'stase.responsibleUsers.user'
            ]);

            // Query for filteredSchedules (today's schedules)
            $today = now()->format('Y-m-d');
            $filteredSchedules = Schedule::with([
                'internshipClass.classYear',
                'stase.departement',
                'stase.responsibleUsers.user'
            ])
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today);

            // Apply filters to both queries
            if ($request->filled('departemen')) {
                $query->whereHas('stase.departement', function($q) use ($request) {
                    $q->where('departements.id', $request->departemen);
                });
                $filteredSchedules->whereHas('stase.departement', function($q) use ($request) {
                    $q->where('departements.id', $request->departemen);
                });
            }

            if ($request->filled('tahun')) {
                $query->whereHas('internshipClass.classYear', function($q) use ($request) {
                    $q->where('internship_classes.id', $request->tahun);
                });
                $filteredSchedules->whereHas('internshipClass.classYear', function($q) use ($request) {
                    $q->where('internship_classes.id', $request->tahun);
                });
            }

            if ($request->filled('pembimbing')) {
                $query->whereHas('stase.responsibleUsers', function($q) use ($request) {
                    $q->where('responsible_users.id', $request->pembimbing);
                });
                $filteredSchedules->whereHas('stase.responsibleUsers', function($q) use ($request) {
                    $q->where('responsible_users.id', $request->pembimbing);
                });
            }

            if ($request->filled('search')) {
                $searchTerm = $request->search;
                $query->where(function($q) use ($searchTerm) {
                    $q->whereHas('internshipClass', function($sq) use ($searchTerm) {
                        $sq->where('name', 'like', "%{$searchTerm}%");
                    })
                    ->orWhereHas('stase', function($sq) use ($searchTerm) {
                        $sq->where('name', 'like', "%{$searchTerm}%");
                    })
                    ->orWhereHas('stase.departement', function($sq) use ($searchTerm) {
                        $sq->where('name', 'like', "%{$searchTerm}%");
                    });
                });
                
                $filteredSchedules->where(function($q) use ($searchTerm) {
                    $q->whereHas('internshipClass', function($sq) use ($searchTerm) {
                        $sq->where('name', 'like', "%{$searchTerm}%");
                    })
                    ->orWhereHas('stase', function($sq) use ($searchTerm) {
                        $sq->where('name', 'like', "%{$searchTerm}%");
                    })
                    ->orWhereHas('stase.departement', function($sq) use ($searchTerm) {
                        $sq->where('name', 'like', "%{$searchTerm}%");
                    });
                });
            }

            // Execute queries
            $allSchedules = $query->paginate(10)->appends($request->all());
            $filteredSchedules = $filteredSchedules->get();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'html' => view('components.admin.schedule.table-body', ['allSchedules' => $allSchedules])->render(),
                    'pagination' => $allSchedules->links()->render()
                ]);
            }

            return view('pages.admin.schedule.index', [
                'allSchedules' => $allSchedules,
                'filteredSchedules' => $filteredSchedules,
                'departments' => Departement::all(),
                'internshipClasses' => InternshipClass::with('classYear')->get(),
                'responsibles' => ResponsibleUser::with('user')->get()
            ]);

        } catch (\Exception $e) {
            \Log::error('Filter error: ' . $e->getMessage());
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat memfilter data'
                ], 500);
            }
            return back()->with('error', 'Terjadi kesalahan saat memfilter data');
        }
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
