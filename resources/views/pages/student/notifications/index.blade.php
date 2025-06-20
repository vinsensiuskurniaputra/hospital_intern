@extends('layouts.auth')

@section('content')
<div class="max-w-6xl mx-auto bg-white rounded-xl shadow-md border p-8 space-y-8" x-data="{
    open: false,
    selectedFilter: 'Semua',
    notifications: {{ Js::from($notifications) }},
    clickedNotifications: {},
    
    toggleClicked(id) {
        this.clickedNotifications[id] = !this.clickedNotifications[id];
    },
    
    sortedNotifications() {
        return this.notifications.sort((a, b) => {
            return new Date(this.parseDate(b.date)) - new Date(this.parseDate(a.date));
        });
    },
    
    parseDate(dateStr) {
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
                <button @click="selectedFilter = 'info'; open = false" 
                        class="w-full px-4 py-2 text-left text-sm hover:bg-green-100"
                        :class="{ 'bg-green-100': selectedFilter === 'info' }">
                    Info
                </button>
                <button @click="selectedFilter = 'warning'; open = false" 
                        class="w-full px-4 py-2 text-left text-sm hover:bg-green-100"
                        :class="{ 'bg-green-100': selectedFilter === 'warning' }">
                    Warning
                </button>
                <button @click="selectedFilter = 'error'; open = false" 
                        class="w-full px-4 py-2 text-left text-sm hover:bg-green-100"
                        :class="{ 'bg-green-100': selectedFilter === 'error' }">
                    Error
                </button>
            </div>
        </div>
    </div>

    <!-- Notifications List -->
    <div class="space-y-4">
        <template x-for="notification in sortedNotifications()" :key="notification.id">
            <a :href="`/student/notifications/${notification.id}`"
               class="block hover:no-underline">
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
                                <span class="inline-flex items-center px-3 py-1 text-m font-semibold rounded-full mt-2 capitalize tracking-wide shadow-sm"
                                      :class="{
                                            'bg-green-100 text-green-800': notification.type === 'info',
                                            'bg-yellow-100 text-yellow-800': notification.type === 'warning',
                                            'bg-blue-100 text-blue-800': notification.type === 'error',
                                       }" x-text="notification.type"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </template>
    </div>

</div>

    <!-- Footer -->
    <div class="mt-8 text-center text-sm text-gray-500">
        @2025 IK Polines
    </div>

@endsection