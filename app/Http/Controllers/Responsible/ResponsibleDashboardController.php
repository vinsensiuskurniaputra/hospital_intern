<?php

namespace App\Http\Controllers\Responsible;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ResponsibleDashboardController extends Controller
{
    /**
     * Display the responsible dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.responsible.dashboard.index');
    }
}