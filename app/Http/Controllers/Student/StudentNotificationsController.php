<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentNotificationsController extends Controller
{
    /**
     * Display the student notifications page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Here you could fetch notifications for the student
        // For now we just return the view
        return view('pages.student.notifications.index');
    }
}