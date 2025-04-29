<?php

namespace App\Http\Controllers\Responsible;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ResponsibleScheduleController extends Controller
{
    /**
     * Display the schedule page for responsible user.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.responsible.schedule.index');
    }
}