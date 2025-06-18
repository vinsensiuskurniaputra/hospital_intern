<?php

namespace App\Http\Controllers\Responsible;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class ResponsibleNotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::where(function($q) {
                $q->where('user_id', Auth::id())
                  ->orWhereNull('user_id'); // notifikasi global
            })
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

        return view('pages.responsible.notifications.index', compact('notifications'));
    }

    public function show($id)
    {
        $notification = Notification::findOrFail($id);
        return view('pages.responsible.notifications.detail', compact('notification'));
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