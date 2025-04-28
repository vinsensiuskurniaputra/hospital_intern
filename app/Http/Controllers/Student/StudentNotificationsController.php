<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StudentNotificationsController extends Controller
{
    public function index()
    {
        $notifications = [
            [
                'title' => 'Pengambilan Sertifikat Magang',
                'date' => '05 Agustus 2024 - 23:59',
                'content' => 'Bagi mahasiswa yang telah menyelesaikan seluruh rangkaian magang dan evaluasi, sertifikat magang sudah bisa diunduh melalui sistem mulai 09 Agustus 2024.',
                'tag' => 'Umum',
                'tag_color' => 'emerald'
            ],
            // Data lainnya bisa ditambahkan disini
        ];

        return view('pages.student.notifications.index', compact('notifications'));
    }
}