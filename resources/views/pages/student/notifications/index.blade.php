<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StudentNotifikasiController extends Controller
{
    public function index()
    {
        $notifications = [
            [
                'id' => 1,
                'title' => 'Pengambilan Sertifikat Magang',
                'created_at' => '2024-08-05T23:59:00',
                'content' => 'Bagi mahasiswa yang telah menyelesaikan seluruh rangkaian magang dan evaluasi, sertifikat magang sudah bisa diunduh melalui sistem mulai 09 Agustus 2024.',
                'type' => 'Umum',
                'is_read' => false
            ],
            [
                'id' => 2,
                'title' => 'Pergantian Jadwal',
                'created_at' => '2024-07-09T00:03:00',
                'content' => 'Pengumuman untuk mahasiswa Kelas FK-01 di Departemen Kesehatan: jadwal rotasi magang diubah menjadi 11 Juli. Mohon untuk mengecek jadwal terbaru di sistem dan menyesuaikan dengan perubahan ini.',
                'type' => 'Jadwal',
                'is_read' => true
            ],
            [
                'id' => 3,
                'title' => 'Jadwal Ujian Evaluasi Sebelum Rotasi Baru',
                'created_at' => '2024-06-28T10:00:00',
                'content' => 'Mahasiswa magang diharapkan untuk mengikuti ujian evaluasi sebelum rotasi ke departemen berikutnya. Ujian akan dilaksanakan secara online melalui sistem pada 30 Jun 2024 pukul 10:00 WIB.',
                'type' => 'Evaluasi',
                'is_read' => true
            ],
            [
                'id' => 4,
                'title' => 'Kebijakan Baru Kedisiplinan Magang',
                'created_at' => '2024-03-29T15:00:00',
                'content' => 'Mulai tanggal 1 April 2024, mahasiswa magang diwajibkan untuk hadir minimal 90% dari total hari magang.',
                'type' => 'Kebijakan',
                'is_read' => true
            ],
            [
                'id' => 5,
                'title' => 'Pengumpulan Berkas Administrasi Magang',
                'created_at' => '2024-02-28T12:00:00',
                'content' => 'Mahasiswa wajib mengumpulkan berkas magang sebelum 27 Maret 2025 melalui sistem atau ke bagian administrasi.',
                'type' => 'Administrasi',
                'is_read' => true
            ]
        ];

        return view('notifikasi.index', compact('notifications'));
    }
}
?>

@extends('layouts.auth')

@section('content')
<div class="p-6">
    <!-- Header with Interactive Filter -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-xl font-semibold text-gray-800">Notifikasi / Pengumuman Penting</h1>
        <div class="relative" x-data="{ isOpen: false }">
            <button @click="isOpen = !isOpen" 
                    class="px-4 py-2 text-sm bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#637F26] flex items-center">
                Filter
                <i class="bi bi-chevron-down ml-2"></i>
            </button>
            
            <!-- Filter Dropdown -->
            <div x-show="isOpen" 
                 @click.away="isOpen = false"
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50">
                <div class="px-4 py-2 text-sm text-gray-500 border-b border-gray-200">Kategori</div>
                <label class="flex items-center px-4 py-2 hover:bg-gray-50 cursor-pointer">
                    <input type="checkbox" class="rounded text-[#637F26] focus:ring-[#637F26] mr-2">
                    <span class="text-sm text-gray-700">Umum</span>
                </label>
                <label class="flex items-center px-4 py-2 hover:bg-gray-50 cursor-pointer">
                    <input type="checkbox" class="rounded text-[#637F26] focus:ring-[#637F26] mr-2">
                    <span class="text-sm text-gray-700">Jadwal</span>
                </label>
                <label class="flex items-center px-4 py-2 hover:bg-gray-50 cursor-pointer">
                    <input type="checkbox" class="rounded text-[#637F26] focus:ring-[#637F26] mr-2">
                    <span class="text-sm text-gray-700">Evaluasi</span>
                </label>
                <label class="flex items-center px-4 py-2 hover:bg-gray-50 cursor-pointer">
                    <input type="checkbox" class="rounded text-[#637F26] focus:ring-[#637F26] mr-2">
                    <span class="text-sm text-gray-700">Kebijakan</span>
                </label>
                <label class="flex items-center px-4 py-2 hover:bg-gray-50 cursor-pointer">
                    <input type="checkbox" class="rounded text-[#637F26] focus:ring-[#637F26] mr-2">
                    <span class="text-sm text-gray-700">Administrasi</span>
                </label>
                <div class="border-t border-gray-200 mt-2 pt-2 px-4">
                    <button class="w-full bg-[#637F26] text-white rounded-lg px-4 py-2 text-sm">
                        Terapkan Filter
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Notifications List -->
    <div class="space-y-4">
        <!-- Notification Item 1 -->
        <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm">
            <div class="flex justify-between items-start mb-4">
                <h2 class="text-lg font-semibold text-gray-800">Pengambilan Sertifikat Magang</h2>
                <div class="flex items-center space-x-2">
                    <span class="text-sm text-gray-500">05 Agustus 2024 - 23:59</span>
                    <span class="px-3 py-1 text-sm font-medium bg-emerald-100 text-emerald-800 rounded-full">
                        Umum
                    </span>
                </div>
            </div>
            <p class="text-gray-600">Bagi mahasiswa yang telah menyelesaikan seluruh rangkaian magang dan evaluasi, sertifikat magang sudah bisa diunduh melalui sistem mulai 09 Agustus 2024.</p>
        </div>

        <!-- Notification Item 2 -->
        <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm">
            <div class="flex justify-between items-start mb-4">
                <h2 class="text-lg font-semibold text-gray-800">Pergantian Jadwal</h2>
                <div class="flex items-center space-x-2">
                    <span class="text-sm text-gray-500">09 Juli 2024 - 00:03</span>
                    <span class="px-3 py-1 text-sm font-medium bg-amber-100 text-amber-800 rounded-full">
                        Jadwal
                    </span>
                </div>
            </div>
            <p class="text-gray-600">Pengumuman untuk mahasiswa Kelas FK-01 di Departemen Kesehatan: jadwal rotasi magang diubah menjadi 11 Juli. Mohon untuk mengecek jadwal terbaru di sistem dan menyesuaikan dengan perubahan ini.</p>
        </div>

        <!-- Notification Item 3 -->
        <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm">
            <div class="flex justify-between items-start mb-4">
                <h2 class="text-lg font-semibold text-gray-800">Jadwal Ujian Evaluasi Sebelum Rotasi Baru</h2>
                <div class="flex items-center space-x-2">
                    <span class="text-sm text-gray-500">28 Juni 2024 - 10:00</span>
                    <span class="px-3 py-1 text-sm font-medium bg-emerald-100 text-emerald-800 rounded-full">
                        Evaluasi
                    </span>
                </div>
            </div>
            <p class="text-gray-600">Mahasiswa magang diharapkan untuk mengikuti ujian evaluasi sebelum rotasi ke departemen berikutnya. Ujian akan dilaksanakan secara online melalui sistem pada 30 Jun 2024 pukul 10:00 WIB.</p>
        </div>

        <!-- Notification Item 4 -->
        <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm">
            <div class="flex justify-between items-start mb-4">
                <h2 class="text-lg font-semibold text-gray-800">Kebijakan Baru Kedisiplinan Magang</h2>
                <div class="flex items-center space-x-2">
                    <span class="text-sm text-gray-500">29 Maret 2024 - 15:00</span>
                    <span class="px-3 py-1 text-sm font-medium bg-red-100 text-red-800 rounded-full">
                        Kebijakan
                    </span>
                </div>
            </div>
            <p class="text-gray-600">Mulai tanggal 1 April 2024, mahasiswa magang diwajibkan untuk hadir minimal 90% dari total hari magang.</p>
        </div>

        <!-- Notification Item 5 -->
        <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm">
            <div class="flex justify-between items-start mb-4">
                <h2 class="text-lg font-semibold text-gray-800">Pengumpulan Berkas Administrasi Magang</h2>
                <div class="flex items-center space-x-2">
                    <span class="text-sm text-gray-500">28 Februari 2024 - 12:00</span>
                    <span class="px-3 py-1 text-sm font-medium bg-green-100 text-green-800 rounded-full">
                        Administrasi
                    </span>
                </div>
            </div>
            <p class="text-gray-600">Mahasiswa wajib mengumpulkan berkas magang sebelum 27 Maret 2025 melalui sistem atau ke bagian administrasi.</p>
        </div>
    </div>
</div>
@endsection
