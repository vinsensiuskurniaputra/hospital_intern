<?php

namespace App\Http\Controllers\General;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $userRole = Auth::user()->roles()->first()->name;
        if ($userRole == 'admin') {
            return view('pages.admin.dashboard.index');
        } elseif ($userRole == 'student') {
            return view('pages.student.dashboard.index');
        } elseif ($userRole == 'responsible') {
            return view('pages.responsible.dashboard.index');
        }
    }
}
