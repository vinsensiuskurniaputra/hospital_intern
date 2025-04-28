<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Notifications\DatabaseNotification as Notification;

class StudentNotificationsController extends Controller
{
    public function index()
    {
        $notifications = Notification::latest()->get();
        return view('pages.student.notifications.index', compact('notifications'));
    }
}