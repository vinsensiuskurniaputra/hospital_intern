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
                'date' => '05 Agustus 2024 - 23:59',
                'content' => 'Bagi mahasiswa yang telah menyelesaikan seluruh rangkaian magang dan evaluasi, sertifikat magang sudah bisa diunduh melalui sistem mulai 09 Agustus 2024.',
                'type' => 'Umum',
                'tag_color' => 'emerald'
            ],
            [
                'id' => 2,
                'title' => 'Pergantian Jadwal',
                'date' => '09 Juli 2024 - 00:03',
                'content' => 'Pengumuman untuk mahasiswa Kelas FK-01 di Departemen Kesehatan: jadwal rotasi magang diubah menjadi 11 Juli. Mohon untuk mengecek jadwal terbaru di sistem dan menyesuaikan dengan perubahan ini.',
                'type' => 'Jadwal',
                'tag_color' => 'amber'
            ],
            [
                'id' => 3,
                'title' => 'Jadwal Ujian Evaluasi Sebelum Rotasi Baru',
                'date' => '28 Juni 2024 - 10:00',
                'content' => 'Mahasiswa magang diharapkan untuk mengikuti ujian evaluasi sebelum rotasi ke departemen berikutnya. Ujian akan dilaksanakan secara online melalui sistem pada 30 Jun 2024 pukul 10:00 WIB.',
                'type' => 'Evaluasi',
                'tag_color' => 'emerald'
            ],
            [
                'id' => 4,
                'title' => 'Kebijakan Baru Kedisiplinan Magang',
                'date' => '29 Maret 2024 - 15:00',
                'content' => 'Mulai tanggal 1 April 2024, mahasiswa magang diwajibkan untuk hadir minimal 90% dari total hari magang.',
                'type' => 'Kebijakan',
                'tag_color' => 'red'
            ],
            [
                'id' => 5,
                'title' => 'Pengumpulan Berkas Administrasi Magang',
                'date' => '28 Februari 2024 - 12:00',
                'content' => 'Mahasiswa wajib mengumpulkan berkas magang sebelum 27 Maret 2025 melalui sistem atau ke bagian administrasi.',
                'type' => 'Administrasi',
                'tag_color' => 'green'
            ],
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
        
        <!-- Filter Dropdown -->
        <div class="relative" x-data="{ 
            isOpen: false,
            selectedFilter: 'Filter',
            notifications: @json($notifications)
        }">
            <!-- Filter Button -->
            <button @click="isOpen = !isOpen" 
                    class="px-4 py-2 text-sm bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none flex items-center min-w-[120px]">
                <span x-text="selectedFilter"></span>
                <i class="bi bi-chevron-down ml-2"></i>
            </button>
            
            <!-- Dropdown Menu -->
            <div x-show="isOpen" 
                 @click.away="isOpen = false"
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg z-50">
                
                <!-- Filter Options -->
                <button @click="selectedFilter = 'Umum'; isOpen = false" 
                        class="w-full px-4 py-2 text-left text-sm hover:bg-[#E7F7E8] hover:text-[#637F26] transition-colors"
                        :class="{ 'bg-[#E7F7E8] text-[#637F26]': selectedFilter === 'Umum' }">
                    Umum
                </button>
                
                <button @click="selectedFilter = 'Jadwal'; isOpen = false"
                        class="w-full px-4 py-2 text-left text-sm hover:bg-[#E7F7E8] hover:text-[#637F26] transition-colors"
                        :class="{ 'bg-[#E7F7E8] text-[#637F26]': selectedFilter === 'Jadwal' }">
                    Jadwal
                </button>
                
                <button @click="selectedFilter = 'Evaluasi'; isOpen = false"
                        class="w-full px-4 py-2 text-left text-sm hover:bg-[#E7F7E8] hover:text-[#637F26] transition-colors"
                        :class="{ 'bg-[#E7F7E8] text-[#637F26]': selectedFilter === 'Evaluasi' }">
                    Evaluasi
                </button>
                
                <button @click="selectedFilter = 'Kebijakan'; isOpen = false"
                        class="w-full px-4 py-2 text-left text-sm hover:bg-[#E7F7E8] hover:text-[#637F26] transition-colors"
                        :class="{ 'bg-[#E7F7E8] text-[#637F26]': selectedFilter === 'Kebijakan' }">
                    Kebijakan
                </button>
                
                <button @click="selectedFilter = 'Administrasi'; isOpen = false"
                        class="w-full px-4 py-2 text-left text-sm hover:bg-[#E7F7E8] hover:text-[#637F26] transition-colors"
                        :class="{ 'bg-[#E7F7E8] text-[#637F26]': selectedFilter === 'Administrasi' }">
                    Administrasi
                </button>
            </div>
        </div>
    </div>

    <!-- Notifications List -->
    <div class="space-y-4">
        <template x-for="notification in notifications.filter(n => selectedFilter === 'Filter' || n.type === selectedFilter)" :key="notification.id">
            <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm">
                <div class="flex justify-between items-start mb-4">
                    <h2 class="text-lg font-semibold text-gray-800" x-text="notification.title"></h2>
                    <div class="flex items-center space-x-2">
                        <span class="text-sm text-gray-500" x-text="notification.date"></span>
                        <span class="px-3 py-1 text-sm font-medium rounded-full"
                              :class="`bg-${notification.tag_color}-100 text-${notification.tag_color}-800`">
                            <span x-text="notification.type"></span>
                        </span>
                    </div>
                </div>
                <p class="text-gray-600" x-text="notification.content"></p>
            </div>
        </template>
    </div>
</div>
@endsection
