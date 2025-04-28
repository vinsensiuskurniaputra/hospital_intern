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
                'id' => 1,
                'title' => 'Pengambilan Sertifikat Magang',
                'content' => 'Bagi mahasiswa yang telah menyelesaikan seluruh rangkaian magang dan evaluasi, sertifikat magang sudah bisa diunduh melalui sistem mulai 09 Agustus 2024.',
                'type' => 'Umum',
                'created_at' => '2024-08-05 23:59:00',
                'is_read' => false
            ],
            [
                'id' => 2,
                'title' => 'Pergantian Jadwal',
                'content' => 'Pengumuman untuk mahasiswa Kelas FK-01 di Departemen Kesehatan: jadwal rotasi magang diubah menjadi 11 Juli. Mohon untuk mengecek jadwal terbaru di sistem dan menyesuaikan dengan perubahan ini.',
                'type' => 'Jadwal',
                'created_at' => '2024-07-09 00:03:00',
                'is_read' => true
            ],
            [
                'id' => 3,
                'title' => 'Jadwal Ujian Evaluasi Sebelum Rotasi Baru',
                'content' => 'Mahasiswa magang diharapkan untuk mengikuti ujian evaluasi sebelum rotasi ke departemen berikutnya. Ujian akan dilaksanakan secara online melalui sistem pada 30 Jun 2024 pukul 10:00 WIB.',
                'type' => 'Evaluasi',
                'created_at' => '2024-06-28 10:00:00',
                'is_read' => true
            ],
            [
                'id' => 4,
                'title' => 'Kebijakan Baru Kedisiplinan Magang',
                'content' => 'Mulai tanggal 1 April 2024, mahasiswa magang diwajibkan untuk hadir minimal 90% dari total hari magang.',
                'type' => 'Kebijakan',
                'created_at' => '2024-03-29 15:00:00',
                'is_read' => true
            ],
            [
                'id' => 5,
                'title' => 'Pengumpulan Berkas Administrasi Magang',
                'content' => 'Mahasiswa wajib mengumpulkan berkas magang sebelum 27 Maret 2025 melalui sistem atau ke bagian administrasi.',
                'type' => 'Administrasi',
                'created_at' => '2024-02-28 12:00:00',
                'is_read' => true
            ]
        ];

        return view('pages.student.notifications.index', compact('notifications'));
    }

    public function markAsRead($id)
    {
        // TODO: Implement mark as read functionality
        return response()->json(['success' => true]);
    }
}