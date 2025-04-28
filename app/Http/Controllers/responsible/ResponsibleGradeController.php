<?php

namespace App\Http\Controllers\Responsible;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ResponsibleGradeController extends Controller
{
    /**
     * Display the grades management page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.responsible.grades.index');
    }
}