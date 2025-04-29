<?php

namespace App\Http\Controllers\Responsible;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ResponsibleNotificationController extends Controller
{
    public function index()
    {
        // Gunakan data statis daripada mengambil dari database
        $notifications = collect([
            [
                'id' => 1,
                'data' => [
                    'title' => 'Mahasiswa Baru',
                    'message' => '5 mahasiswa baru ditambahkan ke departemen Anda. Mohon disiapkan orientasi.'
                ],
                'created_at' => now()->subDays(1),
                'read_at' => null
            ],
            [
                'id' => 2,
                'data' => [
                    'title' => 'Jadwal Evaluasi',
                    'message' => 'Evaluasi performa mahasiswa internship akan dilaksanakan minggu depan.'
                ],
                'created_at' => now()->subDays(3),
                'read_at' => now()->subDays(2)
            ],
            [
                'id' => 3,
                'data' => [
                    'title' => 'Pertemuan Koordinasi',
                    'message' => 'Pertemuan koordinasi semua penanggung jawab akan dilaksanakan besok pukul 10.00.'
                ],
                'created_at' => now()->subDays(6),
                'read_at' => now()->subDays(5)
            ]
        ]);
        
        return view('pages.responsible.notifications.index', [
            'notifications' => $notifications
        ]);
    }
}