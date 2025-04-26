@extends('layouts.auth')

@section('content')
<div class="p-6">
    <div class="bg-white rounded-lg shadow-sm">
        <!-- Header -->
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <h1 class="text-xl font-semibold text-gray-800">Notifikasi / Pengumuman Penting</h1>
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg flex items-center">
                    Filter
                    <i class="bi bi-chevron-down ml-2"></i>
                </button>
                <!-- Dropdown Filter -->
                <div x-show="open" @click.away="open = false" 
                     class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200">
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Semua</a>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Umum</a>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Jadwal</a>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Evaluasi</a>
                </div>
            </div>
        </div>

        <!-- Notification List -->
        <div class="divide-y divide-gray-200">
            <!-- Notification Item 1 -->
            <div class="p-6 hover:bg-gray-50">
                <div class="flex justify-between items-start">
                    <div class="space-y-2">
                        <h3 class="font-semibold text-gray-800">Pengambilan Sertifikat Magang</h3>
                        <p class="text-gray-600 text-sm">
                            Bagi mahasiswa yang telah menyelesaikan seluruh rangkaian magang dan evaluasi, sertifikat magang sudah bisa diunduh melalui sistem mulai 09 Agustus 2024.
                        </p>
                        <span class="inline-block px-3 py-1 text-sm bg-emerald-100 text-emerald-800 rounded-full">Umum</span>
                    </div>
                    <span class="text-sm text-gray-500">05 Agustus 2024 - 23:59</span>
                </div>
            </div>

            <!-- Notification Item 2 -->
            <div class="p-6 hover:bg-gray-50">
                <div class="flex justify-between items-start">
                    <div class="space-y-2">
                        <h3 class="font-semibold text-gray-800">Pergantian Jadwal</h3>
                        <p class="text-gray-600 text-sm">
                            Pengumuman untuk mahasiswa kelas FK-01 di Departemen Kesehatan: jadwal rotasi magang diubah menjadi 11 Juli. Mohon untuk mengecek jadwal terbaru di sistem dan menyesuaikan dengan perubahan ini.
                        </p>
                        <span class="inline-block px-3 py-1 text-sm bg-amber-100 text-amber-800 rounded-full">Jadwal</span>
                    </div>
                    <span class="text-sm text-gray-500">09 Juli 2024 - 00:03</span>
                </div>
            </div>

            <!-- Notification Item 3 -->
            <div class="p-6 hover:bg-gray-50">
                <div class="flex justify-between items-start">
                    <div class="space-y-2">
                        <h3 class="font-semibold text-gray-800">Jadwal Ujian Evaluasi Sebelum Rotasi Baru</h3>
                        <p class="text-gray-600 text-sm">
                            Mahasiswa magang diharapkan untuk mengikuti ujian evaluasi sebelum rotasi ke departemen berikutnya. Ujian akan dilaksanakan secara online melalui sistem pada 30 Juni 2024 pukul 10:00 WIB.
                        </p>
                        <span class="inline-block px-3 py-1 text-sm bg-emerald-100 text-emerald-800 rounded-full">Evaluasi</span>
                    </div>
                    <span class="text-sm text-gray-500">28 Juni 2024 - 10:00</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection