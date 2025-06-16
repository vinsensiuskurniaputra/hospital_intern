<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
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
                    })
                    ->orWhereHas('internshipClass.classYear', function($sq) use ($searchTerm) {
                        $sq->where('class_year', 'like', "%{$searchTerm}%");
                    })
                    ->orWhereHas('stase.responsibleUsers.user', function($sq) use ($searchTerm) {
                        $sq->where('name', 'like', "%{$searchTerm}%");
                    })
                    ->orWhere(function($sq) use ($searchTerm) {
                        $sq->whereRaw("DATE_FORMAT(start_date, '%d-%m-%Y') LIKE ?", ["%{$searchTerm}%"])
                           ->orWhereRaw("DATE_FORMAT(end_date, '%d-%m-%Y') LIKE ?", ["%{$searchTerm}%"]);
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
            $locale = app()->getLocale();
            \Carbon\Carbon::setLocale('id');
            
            // Get the original values before update
            $originalStaseId = $schedule->stase_id;
            $originalStartDate = $schedule->start_date;
            $originalEndDate = $schedule->end_date;
            $originalStaseName = $schedule->stase->name;
            
            // Update the schedule
            $schedule->update($validated);
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
            // Query dasar dengan relasi yang diperlukan
            $query = Schedule::with([
                'internshipClass.classYear',
                'stase.departement',
                'stase.responsibleUsers.user'
            ]);

            // Filter by department
            if ($request->filled('departemen')) {
                $query->whereHas('stase.departement', function($q) use ($request) {
                    $q->where('departements.id', $request->departemen);
                });
            }

            // Filter by year
            if ($request->filled('tahun')) {
                $query->whereHas('internshipClass.classYear', function($q) use ($request) {
                    $q->where('internship_classes.id', $request->tahun);
                });
            }

            // Filter by responsible
            if ($request->filled('pembimbing')) {
                $query->whereHas('stase.responsibleUsers', function($q) use ($request) {
                    $q->where('responsible_users.id', $request->pembimbing);
                });
            }

            // Filter by search term
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
                    })
                    ->orWhereHas('internshipClass.classYear', function($sq) use ($searchTerm) {
                        $sq->where('class_year', 'like', "%{$searchTerm}%");
                    })
                    ->orWhereHas('stase.responsibleUsers.user', function($sq) use ($searchTerm) {
                        $sq->where('name', 'like', "%{$searchTerm}%");
                    })
                    ->orWhere(function($sq) use ($searchTerm) {
                        $sq->whereRaw("DATE_FORMAT(start_date, '%d-%m-%Y') LIKE ?", ["%{$searchTerm}%"])
                           ->orWhereRaw("DATE_FORMAT(end_date, '%d-%m-%Y') LIKE ?", ["%{$searchTerm}%"]);
                    });
                });
            }

            $allSchedules = $query->paginate(10)->appends($request->all());

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'html' => view('components.admin.schedule.table-body', [
                        'allSchedules' => $allSchedules
                    ])->render(),
                    'pagination' => $allSchedules->links()->render(),
                    'total' => $allSchedules->total(), // Add total count
                    'from' => $allSchedules->firstItem(), // Add first item number
                    'to' => $allSchedules->lastItem(), // Add last item number
                    'current_page' => $allSchedules->currentPage() // Add current page
                ]);
            }

            return view('pages.admin.schedule.index', [
                'allSchedules' => $allSchedules,
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