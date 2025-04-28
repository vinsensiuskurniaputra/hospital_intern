<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StudentNotificationsController extends Controller
{
    public function index()
    {
        $notifications = [
            // ...your notification data...
        ];

        return view('pages.student.notifications.index', compact('notifications'));
    }
}