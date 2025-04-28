<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StudentNotificationsController extends Controller
{
    protected $notifications = [
        ['id' => 1, 'message' => 'Notification 1'],
        ['id' => 2, 'message' => 'Notification 2'],
        // Add more notifications as needed
    ];
    public function index()
    {
        $notifications = [
            // ...your notification data...
        ];

        return view('pages.student.notifications.index', compact('notifications'));
    }

    public function show($id)
    {
        $notification = [
            'id' => 1,
            'title' => 'Pengambilan Sertifikat Magang',
            'date' => '05 Agustus 2023 - 23:59',
            'type' => 'Umum',
            'content' => 'Diberitahukan kepada seluruh mahasiswa magang yang telah menyelesaikan seluruh rangkaian magang dan evaluasi, bahwa sertifikat magang sudah dapat diunduh melalui sistem mulai 11 Juli 2024.

Pastikan semua kewajiban akademik telah terpenuhi sebelum mengunduh sertifikat. Jika mengalami kendala dalam proses pengunduhan atau terdapat data yang kurang sesuai, silakan segera menghubungi admin sistem atau koordinator magang untuk bantuan lebih lanjut.

Terima kasih atas partisipasi dan dedikasi selama program magang berlangsung. Semoga pengalaman ini bermanfaat bagi perkembangan karier Anda!',
            'action' => [
                'text' => 'Unduh Sertifikat',
                'url' => '#'
            ]
        ];

        return view('pages.student.notifications.show', compact('notification'));
    }
}