<?php

namespace App\Http\Controllers\Admin;

use App\Models\Student;
use App\Models\Presence;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Maatwebsite\Excel\Facades\Excel; // If using a package like Laravel Excel

class AdminPresenceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Student::with('presences');

        if ($request->filled('class_year')) {
            $query->whereHas('internshipClass.classYear', function ($q) use ($request) {
                $q->where('id', $request->class_year);
            });
        }

        if ($request->filled('status')) {
            $query->whereHas('presences', function ($q) use ($request) {
                $q->where('status', $request->status);
            });
        }

        if ($request->filled('search')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('nim', 'like', '%' . $request->search . '%');
            });
        }

        $students = $query->paginate(10);

        $statuses = ['present', 'sick', 'absent'];
        foreach ($students as $student) {
            $total = $student->presences->count();

            foreach ($statuses as $status) {
                $count = $student->presences->where('status', $status)->count();
                $percentage = $total ? round(($count / $total) * 100, 2) : 0;
                $student->{$status . '_percentage'} = $percentage;
            }
        }

        $classYears = \App\Models\ClassYear::all();

        return view('pages.admin.student_presence.index', compact('students', 'classYears'));
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
    public function show(Presence $presence)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Presence $presence)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Presence $presence)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Presence $presence)
    {
        //
    }

    public function export(Request $request)
    {
        $query = Student::with('presences.user');

        if ($request->filled('class_year')) {
            $query->whereHas('internshipClass.classYear', function ($q) use ($request) {
                $q->where('id', $request->class_year);
            });
        }

        if ($request->filled('status')) {
            $query->whereHas('presences', function ($q) use ($request) {
                $q->where('status', $request->status);
            });
        }

        if ($request->filled('search')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('nim', 'like', '%' . $request->search . '%');
            });
        }

        $students = $query->get();

        $data = $students->map(function ($student) {
            return [
                'Name' => $student->user->name,
                'NIM' => $student->nim,
                'Present' => $student->presences->where('status', 'present')->count(),
                'Sick' => $student->presences->where('status', 'sick')->count(),
                'Absent' => $student->presences->where('status', 'absent')->count(),
            ];
        });

        $filename = 'student_presences_' . now()->format('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($data) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Name', 'NIM', 'Present', 'Sick', 'Absent']);

            foreach ($data as $row) {
                fputcsv($file, $row);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }
}
