<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class StudentNotificationController extends Controller
{
    public function index()
    {
        // Get notifications for logged in user
        $notifications = Notification::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($notification) {
                return [
                    'id' => $notification->id,
                    'title' => $notification->title, 
                    'description' => $notification->message,
                    'date' => $notification->created_at->format('d F Y - H:i'),
                    'type' => $notification->type,
                    'isRead' => $notification->is_read
                ];
            });

        return view('pages.student.notifications.index', compact('notifications'));
    }
    
    public function markAsRead($id)
    {
        $notification = Notification::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();
        
        if ($notification) {
            $notification->is_read = true;
            $notification->save();
        }
        
        return back();
    }
    
    public function markAllAsRead()
    {
        Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);
        
        return back();
    }
    
    public function show($id)
    {
        $notification = Notification::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Mark notification as read when viewed
        if (!$notification->is_read) {
            $notification->is_read = true;
            $notification->save();
        }

        return view('pages.student.notifications.detail', compact('notification'));
    }
}