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
<div class="p-6" x-data="{ 
    isFilterOpen: false,
    selectedFilter: null,
    notifications: @json($notifications)
}">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-xl font-semibold text-gray-800">Notifikasi / Pengumuman Penting</h1>
        
        <div class="relative">
            <button @click="isFilterOpen = !isFilterOpen" 
                    class="px-4 py-2 text-sm bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none flex items-center min-w-[120px] justify-between">
                <span x-text="selectedFilter || 'Filter'"></span>
                <i class="bi bi-chevron-down ml-2"></i>
            </button>
            
            <div x-show="isFilterOpen" 
                @click.away="isFilterOpen = false"
                class="absolute right-0 mt-1 w-48 bg-white rounded-lg shadow-lg z-50 border border-gray-200">
                <button @click="selectedFilter = null; isFilterOpen = false"
                        class="w-full px-4 py-2 text-sm text-left hover:bg-[#E7F7E8] hover:text-[#637F26]"
                        :class="{ 'bg-[#E7F7E8] text-[#637F26]': selectedFilter === null }">
                    Semua
                </button>
                <template x-for="type in ['Umum', 'Jadwal', 'Evaluasi', 'Kebijakan', 'Administrasi']">
                    <button @click="selectedFilter = type; isFilterOpen = false"
                            class="w-full px-4 py-2 text-sm text-left hover:bg-[#E7F7E8] hover:text-[#637F26]"
                            :class="{ 'bg-[#E7F7E8] text-[#637F26]': selectedFilter === type }">
                        <span x-text="type"></span>
                    </button>
                </template>
            </div>
        </div>
    </div>

    <div class="space-y-4">
        <template x-for="notification in notifications
            .filter(n => !selectedFilter || n.type === selectedFilter)
            .sort((a, b) => new Date(b.created_at) - new Date(a.created_at))" 
            :key="notification.id">
            <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm transition-all duration-200"
                 :class="{ 
                     'border-l-4 border-l-[#637F26] bg-[#F5F7F0]': !notification.is_read,
                     'hover:border-l-4 hover:border-l-[#637F26]': notification.is_read
                 }"
                 @click="markAsRead(notification)">
                <div class="flex">
                    <div class="flex-1 pr-4">
                        <h2 class="text-lg font-semibold text-gray-800 mb-2" x-text="notification.title"></h2>
                        <p class="text-gray-600" x-text="notification.content"></p>
                    </div>
                    <div class="flex flex-col items-end min-w-[100px]">
                        <span class="text-sm text-gray-500 mb-2" x-text="formatDate(notification.created_at)"></span>
                        <span class="px-3 py-1 text-sm font-medium rounded-full whitespace-nowrap"
                              :class="{
                                'bg-emerald-100 text-emerald-800': notification.type === 'Umum',
                                'bg-amber-100 text-amber-800': notification.type === 'Jadwal',
                                'bg-green-100 text-green-800': notification.type === 'Evaluasi',
                                'bg-red-100 text-red-800': notification.type === 'Kebijakan',
                                'bg-green-100 text-green-800': notification.type === 'Administrasi'
                              }">
                            <span x-text="notification.type"></span>
                        </span>
                    </div>
                </div>
            </div>
        </template>
    </div>
</div>

@push('scripts')
<script>
function formatDate(dateString) {
    const options = { 
        day: 'numeric',
        month: 'long',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    };
    return new Date(dateString).toLocaleString('id-ID', options);
}

function markAsRead(notification) {
    if (!notification.is_read) {
        fetch(`/student/notifications/${notification.id}/read`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        }).then(() => {
            notification.is_read = true;
        });
    }
}
</script>
@endpush
@endsection
