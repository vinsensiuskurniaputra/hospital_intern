<?php

namespace App\Http\Controllers\Responsible;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ResponsibleAttendanceController extends Controller
{
    /**
     * Display the attendance management page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.responsible.attendance.index');
    }
}