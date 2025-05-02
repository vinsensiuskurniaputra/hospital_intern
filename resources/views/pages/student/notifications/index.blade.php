@extends('layouts.auth')

@section('content')
<div class="p-6" x-data="{ 
    open: false, 
    selectedFilter: 'Semua',
    notifications: [
        {
            id: 1,
            title: 'Pengambilan Sertifikat Magang',
            description: 'Bagi mahasiswa yang telah menyelesaikan seluruh rangkaian magang dan evaluasi, sertifikat magang sudah bisa diunduh melalui sistem mulai 09 Agustus 2024.',
            date: '05 Agustus 2024 - 23:59',
            type: 'Umum',
            isRead: false
        },
        {
            id: 2,
            title: 'Pergantian Jadwal',
            description: 'Pengumuman untuk mahasiswa Kelas FK-01 di Departemen Kesehatan: jadwal rotasi magang diubah menjadi 11 Juli. Mohon untuk mengecek jadwal terbaru di sistem dan menyesuaikan dengan perubahan ini.',
            date: '09 Juli 2024 - 00:03',
            type: 'Jadwal',
            isRead: false
        },
        {
            id: 3,
            title: 'Jadwal Ujian Evaluasi Sebelum Rotasi Baru',
            description: 'Mahasiswa magang diharapkan untuk mengikuti ujian evaluasi sebelum rotasi ke departemen berikutnya. Ujian akan dilaksanakan secara online melalui sistem pada 30 Jun 2024 pukul 10:00 WIB.',
            date: '28 Juni 2024 - 10:00',
            type: 'Evaluasi',
            isRead: true
        },
        {
            id: 4,
            title: 'Kebijakan Baru Kedisiplinan Magang',
            description: 'Mulai tanggal 1 April 2024, mahasiswa magang diwajibkan untuk hadir minimal 90% dari total hari magang.',
            date: '29 Maret 2024 - 15:00',
            type: 'Kebijakan',
            isRead: true
        },
        {
            id: 5,
            title: 'Pengumpulan Berkas Administrasi Magang',
            description: 'Mahasiswa wajib mengumpulkan berkas magang sebelum 27 Maret 2025 melalui sistem atau ke bagian administrasi.',
            date: '28 Februari 2024 - 12:00',
            type: 'Administrasi',
            isRead: true
        }
    ],
    sortedNotifications() {
        return this.notifications.sort((a, b) => {
            return new Date(this.parseDate(b.date)) - new Date(this.parseDate(a.date));
        });
    },
    
    parseDate(dateStr) {
        // Convert Indonesian date format to JS Date format
        const [date, time] = dateStr.split(' - ');
        const [day, month, year] = date.split(' ');
        const months = {
            'Januari': '01', 'Februari': '02', 'Maret': '03', 'April': '04',
            'Mei': '05', 'Juni': '06', 'Juli': '07', 'Agustus': '08',
            'September': '09', 'Oktober': '10', 'November': '11', 'Desember': '12'
        };
        return `${year}-${months[month]}-${day.padStart(2, '0')}T${time}`;
    },

    filteredNotifications(notification) {
        if (this.selectedFilter === 'Semua') return true;
        return this.selectedFilter === notification.type;
    }
}">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-xl font-semibold text-gray-800">Notifikasi / Pengumuman Penting</h1>
        
        <!-- Filter Dropdown -->
        <div class="relative">
        <button @click="open = !open" 
                class="w-48 px-4 py-2 text-sm bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none inline-flex items-center justify-between">
            <span x-text="selectedFilter">Filter</span>
            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>

            
            <!-- Dropdown Menu -->
            <div x-show="open" 
                 @click.away="open = false"
                 class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg z-50 py-1">
                <button @click="selectedFilter = 'Semua'; open = false" 
                        class="w-full px-4 py-2 text-left text-sm hover:bg-green-100"
                        :class="{ 'bg-green-100': selectedFilter === 'Semua' }">
                    Semua
                </button>
                <button @click="selectedFilter = 'Umum'; open = false" 
                        class="w-full px-4 py-2 text-left text-sm hover:bg-green-100"
                        :class="{ 'bg-green-100': selectedFilter === 'Umum' }">
                    Umum
                </button>
                <button @click="selectedFilter = 'Jadwal'; open = false" 
                        class="w-full px-4 py-2 text-left text-sm hover:bg-green-100"
                        :class="{ 'bg-green-100': selectedFilter === 'Jadwal' }">
                    Jadwal
                </button>
                <button @click="selectedFilter = 'Evaluasi'; open = false" 
                        class="w-full px-4 py-2 text-left text-sm hover:bg-green-100"
                        :class="{ 'bg-green-100': selectedFilter === 'Evaluasi' }">
                    Evaluasi
                </button>
                <button @click="selectedFilter = 'Kebijakan'; open = false" 
                        class="w-full px-4 py-2 text-left text-sm hover:bg-green-100"
                        :class="{ 'bg-green-100': selectedFilter === 'Kebijakan' }">
                    Kebijakan
                </button>
                <button @click="selectedFilter = 'Administrasi'; open = false" 
                        class="w-full px-4 py-2 text-left text-sm hover:bg-green-100"
                        :class="{ 'bg-green-100': selectedFilter === 'Administrasi' }">
                    Administrasi
                </button>
            </div>
        </div>
    </div>

    <!-- Notifications List -->
    <div class="space-y-4">
        <template x-for="notification in sortedNotifications()" :key="notification.id">
            <div x-show="filteredNotifications(notification)"
                 class="bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow"
                 :class="{ 'bg-white': !notification.isRead }">
                <div class="p-6">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h2 class="text-xl font-semibold" :class="{ 'text-gray-900': !notification.isRead, 'text-gray-500': notification.isRead }" x-text="notification.title"></h2>
                            <p class="mt-4 font-semibold" :class="{ 'text-gray-900': !notification.isRead, 'text-gray-500': notification.isRead }" x-text="notification.description"></p>
                        </div>
                        <div class="ml-4 flex flex-col items-end">
                            <p class="text-sm text-gray-500" x-text="notification.date"></p>
                            <span class="inline-flex items-center px-3 py-1 text-sm font-medium rounded-full mt-2"
                                  :class="{
                                      'bg-green-100 text-green-800': notification.type === 'Umum',
                                      'bg-yellow-100 text-yellow-800': notification.type === 'Jadwal',
                                      'bg-blue-100 text-blue-800': notification.type === 'Evaluasi',
                                      'bg-red-100 text-red-800': notification.type === 'Kebijakan',
                                      'bg-purple-100 text-purple-800': notification.type === 'Administrasi'
                                  }" x-text="notification.type"></span>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>

    <!-- Footer -->
    <div class="mt-8 text-center text-sm text-gray-500">
        @2025 IK Polines
    </div>
</div>
@endsection