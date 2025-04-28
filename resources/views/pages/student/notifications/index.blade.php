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
    notifications: {{ json_encode($notifications) }}
}">
    <!-- Header with Filter -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-xl font-semibold text-gray-800">Notifikasi / Pengumuman Penting</h1>
        
        <!-- Filter Dropdown -->
        <div class="relative">
            <button @click="isFilterOpen = !isFilterOpen" 
                    class="px-4 py-2 text-sm bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none flex items-center min-w-[120px] justify-between">
                <span x-text="selectedFilter || 'Filter'"></span>
                <i class="bi bi-chevron-down ml-2"></i>
            </button>
            
            <!-- Dropdown Menu -->
            <div x-show="isFilterOpen" 
                @click.away="isFilterOpen = false"
                class="absolute right-0 mt-1 w-48 bg-white rounded-lg shadow-lg z-50 border border-gray-200">
                
                <button @click="selectedFilter = 'Umum'; isFilterOpen = false"
                        class="w-full px-4 py-2 text-sm text-left hover:bg-[#E7F7E8] hover:text-[#637F26]"
                        :class="{ 'bg-[#E7F7E8] text-[#637F26]': selectedFilter === 'Umum' }">
                    Umum
                </button>
                <button @click="selectedFilter = 'Jadwal'; isFilterOpen = false"
                        class="w-full px-4 py-2 text-sm text-left hover:bg-[#E7F7E8] hover:text-[#637F26]"
                        :class="{ 'bg-[#E7F7E8] text-[#637F26]': selectedFilter === 'Jadwal' }">
                    Jadwal
                </button>
                <button @click="selectedFilter = 'Evaluasi'; isFilterOpen = false"
                        class="w-full px-4 py-2 text-sm text-left hover:bg-[#E7F7E8] hover:text-[#637F26]"
                        :class="{ 'bg-[#E7F7E8] text-[#637F26]': selectedFilter === 'Evaluasi' }">
                    Evaluasi
                </button>
                <button @click="selectedFilter = 'Kebijakan'; isFilterOpen = false"
                        class="w-full px-4 py-2 text-sm text-left hover:bg-[#E7F7E8] hover:text-[#637F26]"
                        :class="{ 'bg-[#E7F7E8] text-[#637F26]': selectedFilter === 'Kebijakan' }">
                    Kebijakan
                </button>
                <button @click="selectedFilter = 'Administrasi'; isFilterOpen = false"
                        class="w-full px-4 py-2 text-sm text-left hover:bg-[#E7F7E8] hover:text-[#637F26]"
                        :class="{ 'bg-[#E7F7E8] text-[#637F26]': selectedFilter === 'Administrasi' }">
                    Administrasi
                </button>
            </div>
        </div>
    </div>

    <!-- Notifications List -->
    <div class="space-y-4">
        <template x-for="notification in notifications
            .filter(n => !selectedFilter || n.type === selectedFilter)
            .sort((a, b) => new Date(b.created_at) - new Date(a.created_at))" 
            :key="notification.id">
            <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm transition-all duration-200"
                 :class="{ 
                     'border-l-4 border-l-[#637F26] bg-gray-50': !notification.is_read,
                     'hover:border-l-4 hover:border-l-[#637F26]': notification.is_read
                 }"
                 @click="markAsRead(notification.id)">
                <div class="flex justify-between items-start mb-4">
                    <h2 class="text-lg font-semibold text-gray-800" x-text="notification.title"></h2>
                    <div class="flex items-center space-x-2">
                        <span class="text-sm text-gray-500" x-text="formatDate(notification.created_at)"></span>
                        <span class="px-3 py-1 text-sm font-medium rounded-full"
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
                <p class="text-gray-600" x-text="notification.content"></p>
            </div>
        </template>
    </div>
</div>

<script>
function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleString('id-ID', { 
        day: 'numeric',
        month: 'long',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

function markAsRead(notificationId) {
    if (!notification.is_read) {
        fetch(`/student/notifications/${notificationId}/read`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        }).then(() => {
            this.notifications = this.notifications.map(n => {
                if (n.id === notificationId) {
                    return { ...n, is_read: true };
                }
                return n;
            });
        });
    }
}
</script>
@endsection
