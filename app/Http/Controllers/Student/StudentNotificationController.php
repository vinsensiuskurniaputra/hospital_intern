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
        // Ambil notifikasi user yang sedang login dari database
        $notifications = Notification::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($notification) {
                return [
                    'id' => $notification->id,
                    'title' => $notification->title,
                    'message' => $notification->message,
                    'created_at' => $notification->created_at,
                    'read_at' => $notification->is_read ? $notification->updated_at : null,
                    'is_read' => $notification->is_read,
                    'type' => $notification->type,
                    'icon' => $notification->icon
                ];
            });
        
        return view('pages.student.notifications.index', [
            'notifications' => $notifications
        ]);
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
}