<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StudentAttendanceController extends Controller
{
    public function index()
    {
        $attendanceStats = [
            'total' => 1000,
            'hadir' => 52.1,
            'izin' => 22.8,
            'alpha' => 15.9
        ];

        $stases = [
            [
                'name' => 'Stase Dokter Umum',
                'department' => 'Departemen Umum',
                'date' => '1 Jan - 31 Maret 2025',
                'percentage' => 80
            ],
            [
                'name' => 'Stase Dokter Umum',
                'department' => 'Departemen Umum',
                'date' => '1 Apr - 30 Juni 2025',
                'percentage' => 0
            ]
        ];

        return view('pages.student.attendance.index', compact('attendanceStats', 'stases'));
    }
}