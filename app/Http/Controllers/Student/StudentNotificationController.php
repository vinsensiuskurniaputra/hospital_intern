<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StudentNotificationController extends Controller
{
    public function index()
    {
        // Gunakan data statis daripada mengambil dari database
        $notifications = collect([
            [
                'id' => 1,
                'data' => [
                    'title' => 'Jadwal Praktik Berubah',
                    'message' => 'Jadwal praktik di Departemen Bedah diubah menjadi hari Kamis pukul 09.00.'
                ],
                'created_at' => now()->subDays(2),
                'read_at' => null
            ],
            [
                'id' => 2,
                'data' => [
                    'title' => 'Pengumpulan Laporan',
                    'message' => 'Batas pengumpulan laporan praktik adalah minggu ini. Mohon diselesaikan tepat waktu.'
                ],
                'created_at' => now()->subDays(5),
                'read_at' => now()->subDays(4)
            ],
            [
                'id' => 3,
                'data' => [
                    'title' => 'Nilai Praktikum',
                    'message' => 'Nilai praktikum Anda sudah keluar. Silakan cek di menu Nilai.'
                ],
                'created_at' => now()->subDays(7),
                'read_at' => now()->subDays(7)
            ]
        ]);
        
        return view('pages.student.notifications.index', [
            'notifications' => $notifications
        ]);
    }
}