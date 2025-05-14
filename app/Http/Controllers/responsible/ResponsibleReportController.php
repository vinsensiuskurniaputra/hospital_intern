<?php

namespace App\Http\Controllers\Responsible;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ResponsibleReportController extends Controller
{
    /**
     * Display the reports and summary page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.responsible.reports.index');
    }
}