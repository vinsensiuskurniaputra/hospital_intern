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
        $notifications = Notification::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($notification) {
                return [
                    'id' => $notification->id,
                    'title' => $notification->title,
                    'description' => $notification->message,
                    'date' => $notification->created_at->format('d F Y - H:i'),
                    'type' => $this->getNotificationType($notification->type), // Konversi tipe notifikasi
                    'isRead' => (bool) $notification->is_read
                ];
            });

        return view('pages.responsible.notifications.index', compact('notifications'));
    }

    private function getNotificationType($type)
    {
        $types = [
            'info' => 'Umum',
            'warning' => 'Jadwal',
            'error' => 'Evaluasi'
        ];

        return $types[$type] ?? 'Umum';
    }

    public function show($id)
    {
        $notification = Notification::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if (!$notification->is_read) {
            $notification->is_read = true;
            $notification->save();
        }

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