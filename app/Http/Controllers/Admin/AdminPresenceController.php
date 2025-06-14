<?php

namespace App\Http\Controllers\Admin;

use App\Models\Student;
use App\Models\Presence;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminPresenceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $students = Student::with('presences')->paginate(10);

        $statuses = ['present', 'sick', 'absent'];
        foreach ($students as $student) {
            $total = $student->presences->count();

            foreach ($statuses as $status) {
                $count = $student->presences->where('status', $status)->count();
                $percentage = $total ? round(($count / $total) * 100, 2) : 0;
                $student->{$status . '_percentage'} = $percentage;
            }
        }

        return view('pages.admin.student_presence.index', compact('students'));
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
}
