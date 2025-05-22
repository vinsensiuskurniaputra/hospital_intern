@extends('layouts.auth')

@section('title', 'Schedule Management')

@section('content')
    <div class="p-6 space-y-6">
        <!-- Header Section -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-800">Manajemen Penjadwalan</h1>
                    <p class="mt-1 text-sm text-gray-500">Penjadwalan Kelas Magang Mahasiswa</p>
                </div>
                <a href="{{ route('presences.schedules.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-[#637F26] text-white rounded-lg hover:bg-[#85A832] transition-colors">
                    <i class="bi bi-plus-lg mr-2"></i>
                    Tambahkan Jadwal
                </a>
            </div>
        </div>

        <!-- Calendar and Schedule Section -->
        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-800">Kalender</h2>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
                <!-- Calendar Section -->
                <div class="lg:col-span-1">
                    <!-- Calendar Controls -->
                    <div class="flex gap-2 mb-4">
                        <div class="relative flex-1">
                            <select id="month-select"
                                class="form-select w-full pl-3 pr-8 py-1.5 text-sm rounded-lg border border-gray-300 appearance-none cursor-pointer">
                                @foreach (['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'] as $index => $month)
                                    <option value="{{ $index + 1 }}" {{ $index + 1 == date('n') ? 'selected' : '' }}>
                                        {{ $month }}</option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3">
                                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>

                        <div class="relative flex-1">
                            <select id="year-select"
                                class="form-select w-full pl-3 pr-8 py-1.5 text-sm rounded-lg border border-gray-300 appearance-none cursor-pointer">
                                @for ($year = 2000; $year <= 2026; $year++)
                                    <option value="{{ $year }}" {{ $year == date('Y') ? 'selected' : '' }}>
                                        {{ $year }}</option>
                                @endfor
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3">
                                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Calendar Grid -->
                    <div class="bg-gray-50 rounded-lg p-2">
                        <div class="grid grid-cols-7 gap-0.5 mb-0.5">
                            @foreach (['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'] as $day)
                                <div class="text-[10px] font-medium text-gray-500 text-center py-0.5 w-7">
                                    {{ $day }}</div>
                            @endforeach
                        </div>

                        <div id="calendar-grid" class="grid grid-cols-7 gap-0.5">
                            <!-- Calendar days will be inserted here by JavaScript -->
                        </div>
                    </div>

                    <!-- Calendar Buttons -->
                    <div class="flex justify-end gap-2 mt-3">
                        <button
                            class="calendar-cancel px-3 py-1.5 text-sm rounded-lg border border-gray-300 hover:bg-gray-50 transition-colors">
                            Batal
                        </button>
                        <button
                            class="calendar-done px-3 py-1.5 text-sm rounded-lg bg-[#637F26] text-white hover:bg-[#85A832] transition-colors">
                            Pilih Tanggal
                        </button>
                    </div>
                </div>

                <!-- Schedule List Section -->
                <div class="lg:col-span-3">
                    <div class="space-y-2">
                        @forelse ($filteredSchedules as $schedule)
                            <a href="{{ route('presences.schedules.show', $schedule->id) }}"
                                class="block bg-gray-50 rounded-lg p-3 hover:shadow-sm transition-all cursor-pointer">
                                <div class="flex items-center justify-between">
                                    <!-- ... existing card content ... -->
                                </div>
                            </a>
                        @empty
                            <div class="text-center text-gray-500 py-4">Tidak ada jadwal pada tanggal ini</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters and Table Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <!-- Enhanced Filters -->
            <div class="p-6 border-b border-gray-100">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="relative flex-1">
                        <select id="departemen-filter" class="w-full border border-gray-300 rounded-md py-2 px-4 pr-8 appearance-none bg-white">
                            <option value="">Semua Departemen</option>
                            @foreach ($departments as $department)
                                <option value="{{ $department->name }}">{{ $department->name }}</option>
                            @endforeach
                        </select>
                        <div class="absolute right-3 top-3 pointer-events-none">
                            <svg class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>

                    <div class="relative flex-1">
                        <select id="tahun-filter" class="w-full border border-gray-300 rounded-md py-2 px-4 pr-8 appearance-none bg-white">
                            <option value="">Semua Tahun</option>
                            @foreach ($internshipClasses as $class)
                                <option value="{{ $class->classYear->class_year }}">{{ $class->classYear->class_year }}</option>
                            @endforeach
                        </select>
                        <div class="absolute right-3 top-3 pointer-events-none">
                            <svg class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>

                    <div class="relative flex-1">
                        <select id="pembimbing-filter" class="w-full border border-gray-300 rounded-md py-2 px-4 pr-8 appearance-none bg-white">
                            <option value="">Semua Pembimbing</option>
                            @foreach ($responsibles as $responsible)
                                @if ($responsible && $responsible->user)
                                    <option value="{{ $responsible->user->name }}">{{ $responsible->user->name }}</option>
                                @endif
                            @endforeach
                        </select>
                        <div class="absolute right-3 top-3 pointer-events-none">
                            <svg class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>

                    <div class="relative flex-1">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" placeholder="Cari"
                            class="pl-10 w-full border border-gray-300 rounded-md py-2 px-4">
                    </div>
                </div>
            </div>

            <!-- Enhanced Table -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr>
                            <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Kelas Magang</th>
                            <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Stase</th>
                            <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Departemen</th>
                            <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tahun Angkatan</th>
                            <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Pembimbing</th>
                            <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Periode Rotasi</th>
                            <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($allSchedules as $schedule)
                            <tr>
                                <td class="py-3 px-4">{{ $schedule->internshipClass->name ?? 'N/A' }}</td>
                                <td class="py-3 px-4">{{ $schedule->stase->name ?? 'N/A' }}</td>
                                <td class="py-3 px-4">{{ $schedule->stase->departement->name ?? 'N/A' }}</td>
                                <td class="py-3 px-4">{{ $schedule->internshipClass->classYear->class_year ?? 'N/A' }}
                                </td>
                                <td class="py-3 px-4">
                                    @foreach ($schedule->stase->responsibleUsers as $responsible)
                                        <div class="py-3">
                                            {{ $responsible->user->name }}
                                        </div>
                                    @endforeach
                                </td>
                                <td class="py-3 px-4">
                                    @if ($schedule->start_date && $schedule->end_date)
                                        {{ \Carbon\Carbon::parse($schedule->start_date)->format('d-m-Y') }} s/d
                                        {{ \Carbon\Carbon::parse($schedule->end_date)->format('d-m-Y') }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td class="py-3 px-4">
                                    <div class="flex gap-2">
                                        <a href="{{ route('presences.schedules.edit', $schedule->id) }}"
                                            class="text-blue-500">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                        </a>
                                        <form action="{{ route('presences.schedules.destroy', $schedule->id) }}"
                                            method="POST" class="inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500">
                                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination for table -->
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $allSchedules->links() }}
            </div>
        </div>
    </div>

    <script>
        function confirmDelete(event) {
            if (!confirm('Apakah Anda yakin ingin menghapus jadwal ini?')) {
                event.preventDefault();
            }
        }

        // Add this to each delete form
        document.querySelectorAll('.delete-form').forEach(form => {
            form.addEventListener('submit', confirmDelete);
        });

        function updateScheduleList(date) {
            fetch(`/presences/schedules/filter-by-date?date=${date}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        const scheduleList = document.querySelector('.lg\\:col-span-3 .space-y-2');
                        let html = '';

                        data.schedules.forEach(schedule => {
                            html += `
                            <a href="/presences/schedules/${schedule.id}" 
                               class="block bg-gray-50 rounded-lg p-3 hover:shadow-sm transition-all cursor-pointer">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-sm font-medium text-gray-800 truncate">
                                            ${schedule.internship_class?.name || 'N/A'}
                                        </h3>
                                        <p class="text-xs text-gray-500 truncate">
                                            ${schedule.stase?.departement?.name || 'N/A'}
                                        </p>
                                    </div>
                                    <div class="flex items-center gap-4 ml-4">
                                        <div class="text-right whitespace-nowrap">
                                            <p class="text-xs text-gray-500">
                                                ${formatDate(schedule.start_date)} - ${formatDate(schedule.end_date)}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        `;
                        });

                        if (data.schedules.length === 0) {
                            html =
                                '<div class="text-center text-gray-500 py-4">Tidak ada jadwal pada tanggal ini</div>';
                        }

                        scheduleList.innerHTML = html;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    const scheduleList = document.querySelector('.lg\\:col-span-3 .space-y-2');
                    scheduleList.innerHTML =
                        '<div class="text-center text-gray-500 py-4">Terjadi kesalahan saat memuat jadwal</div>';
                });
        }

        // Add helper functions for date formatting
        function formatTime(time) {
            if (!time) return 'N/A';
            const [hours, minutes] = time.split(':');
            return `${hours}:${minutes}`;
        }

        function formatDate(date) {
            if (!date) return 'N/A';
            const d = new Date(date);
            return d.toLocaleDateString('id-ID', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric'
            });
        }

        // Update event listener untuk initialization
        document.addEventListener('DOMContentLoaded', function() {
            // ... existing calendar initialization ...

            // Load jadwal hari ini sebagai default
            const today = new Date().toISOString().split('T')[0];
            updateScheduleList(today);

            // Handle Pilih Tanggal button
            const doneButton = document.querySelector('.calendar-done');
            if (doneButton) {
                doneButton.addEventListener('click', function() {
                    if (selectedDate) {
                        updateScheduleList(selectedDate);
                    }
                });
            }
        });
    </script>
@endsection

@push('styles')
    <style>
        .form-select:focus,
        .form-input:focus {
            outline: none;
            border-color: #637F26;
            ring-color: #F5F7F0;
        }

        .calendar-day {
            transition: all 0.2s ease-in-out;
            padding: 0.15rem;
            /* Mengurangi padding */
            border-radius: 0.125rem;
            /* Mengurangi border radius */
            width: 1.75rem;
            /* Tetapkan lebar spesifik */
            height: 1.75rem;
            /* Tetapkan tinggi spesifik */
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            cursor: pointer;
            margin: 1px;
            /* Tambah margin kecil */
        }

        /* Ubah container kalender */
        #calendar-grid {
            max-width: 280px;
            /* Batasi lebar maksimum */
            margin: 0 auto;
        }

        /* Mengatur jarak antar hari dalam minggu */
        .grid-cols-7 {
            gap: 0.125rem;
            /* Mengurangi gap */
        }

        /* Header hari */
        .calendar-header {
            padding: 0.15rem 0;
        }

        /* Container utama kalender */
        .rounded-lg.bg-gray-50.p-2 {
            max-width: 300px;
            /* Batasi lebar maksimum container */
            margin: 0 auto;
        }

        .calendar-day:hover {
            background-color: #F5F7F0;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .calendar-day.selected-date {
            background-color: #637F26;
            color: white;
        }

        .calendar-day.today {
            border: 2px solid #637F26;
            font-weight: bold;
        }

        /* Styling untuk hari-hari dari bulan sebelum/sesudah */
        .calendar-day.other-month {
            color: #9CA3AF;
            background-color: #F3F4F6;
        }

        .calendar-day {
            width: 1.75rem;
            height: 1.75rem;
            padding: 0.1rem;
            border-radius: 0.125rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            cursor: pointer;
        }

        #calendar-grid {
            width: fit-content;
            margin: 0 auto;
        }

        .grid-cols-7>* {
            width: 1.75rem;
            /* Memastikan semua sel kalender memiliki lebar yang sama */
        }

        /* ...existing calendar styles... */
        .calendar-day {
            width: 1.75rem;
            height: 1.75rem;
            padding: 0.1rem;
            border-radius: 0.125rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            cursor: pointer;
        }

        .calendar-day.other-month {
            color: #9CA3AF;
            background-color: #F3F4F6;
            opacity: 0.5;
        }

        .calendar-day.today {
            border: 1px solid #637F26;
            font-weight: 600;
        }

        .calendar-day.selected-date {
            background-color: #637F26;
            color: white;
        }

        .calendar-day:hover:not(.other-month) {
            background-color: #F5F7F0;
        }
    </style>
@endpush

@push('scripts')
    <script>
        let selectedDate = new Date().toISOString().split('T')[0];

        function generateCalendar(year, month) {
            const firstDay = new Date(year, month - 1, 1);
            const daysInMonth = new Date(year, month, 0).getDate();
            const startingDay = firstDay.getDay();
            const today = new Date().toISOString().split('T')[0];

            const prevMonth = new Date(year, month - 2, 0);
            const prevMonthDays = prevMonth.getDate();

            let calendarHTML = '';

            // Previous month days
            for (let i = startingDay - 1; i >= 0; i--) {
                const prevDate = new Date(year, month - 2, prevMonthDays - i);
                const dateString = prevDate.toISOString().split('T')[0];
                calendarHTML += `
            <div data-date="${dateString}" class="calendar-day other-month">
                ${prevMonthDays - i}
            </div>`;
            }

            // Current month days
            for (let day = 1; day <= daysInMonth; day++) {
                const date = `${year}-${month.toString().padStart(2, '0')}-${day.toString().padStart(2, '0')}`;
                const isSelected = selectedDate === date;
                const isToday = today === date;

                calendarHTML += `
            <div 
                data-date="${date}"
                class="calendar-day ${isToday ? 'today' : ''} ${isSelected ? 'selected-date' : ''}"
                onclick="selectDate('${date}', this)">
                ${day}
            </div>`;
            }

            // Next month days
            const totalDays = startingDay + daysInMonth;
            const remainingDays = 42 - totalDays;

            for (let i = 1; i <= remainingDays; i++) {
                const nextDate = new Date(year, month, i);
                const dateString = nextDate.toISOString().split('T')[0];
                calendarHTML += `
            <div data-date="${dateString}" class="calendar-day other-month">
                ${i}
            </div>`;
            }

            document.getElementById('calendar-grid').innerHTML = calendarHTML;
        }

        // Update fungsi selectDate
        function selectDate(date, element) {
            const previousSelected = document.querySelector('.calendar-day.selected-date');
            if (previousSelected) {
                previousSelected.classList.remove('selected-date');
            }
            element.classList.add('selected-date');
            selectedDate = date;

            // Update dropdown bulan dan tahun sesuai tanggal yang dipilih
            const selectedDateObj = new Date(date);
            const monthSelect = document.getElementById('month-select');
            const yearSelect = document.getElementById('year-select');

            monthSelect.value = selectedDateObj.getMonth() + 1;
            yearSelect.value = selectedDateObj.getFullYear();
        }

        // Update event listener untuk initialization
        document.addEventListener('DOMContentLoaded', function() {
            const monthSelect = document.getElementById('month-select');
            const yearSelect = document.getElementById('year-select');

            // Month change handler
            monthSelect.addEventListener('change', function() {
                generateCalendar(
                    parseInt(yearSelect.value),
                    parseInt(this.value)
                );
            });

            // Year change handler
            yearSelect.addEventListener('change', function() {
                generateCalendar(
                    parseInt(this.value),
                    parseInt(monthSelect.value)
                );
            });

            // Handle Pilih Tanggal button
            const doneButton = document.querySelector('.calendar-done');
            if (doneButton) {
                doneButton.addEventListener('click', function() {
                    if (selectedDate) {
                        updateScheduleList(selectedDate);
                    }
                });
            }

            // Handle Cancel button
            const cancelButton = document.querySelector('.calendar-cancel');
            if (cancelButton) {
                cancelButton.addEventListener('click', function() {
                    const today = new Date();
                    selectedDate = today.toISOString().split('T')[0];
                    monthSelect.value = today.getMonth() + 1;
                    yearSelect.value = today.getFullYear();
                    generateCalendar(today.getFullYear(), today.getMonth() + 1);
                    updateScheduleList(selectedDate);
                });
            }

            // Initialize calendar dengan tanggal hari ini
            const today = new Date();
            selectedDate = today.toISOString().split('T')[0];
            generateCalendar(today.getFullYear(), today.getMonth() + 1);

            // Set selected date visual untuk hari ini
            const todayElement = document.querySelector(`[data-date="${selectedDate}"]`);
            if (todayElement) {
                selectDate(selectedDate, todayElement);
                updateScheduleList(selectedDate);
            }
        });
    </script>

    <script>
        // Filter functionality
        document.addEventListener('DOMContentLoaded', function() {
            const departemenFilter = document.getElementById('departemen-filter');
            const tahunFilter = document.getElementById('tahun-filter');
            const pembimbingFilter = document.getElementById('pembimbing-filter');
            const searchInput = document.querySelector('input[type="text"]');

            function fetchFilteredData() {
                const params = new URLSearchParams({
                    departemen: departemenFilter.value,
                    tahun: tahunFilter.value,
                    pembimbing: pembimbingFilter.value,
                    search: searchInput.value,
                    page: new URLSearchParams(window.location.search).get('page') || 1
                });

                fetch(`/presences/schedules/filter?${params}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    document.querySelector('tbody').innerHTML = data.table;
                    document.querySelector('.pagination').innerHTML = data.pagination;
                    
                    // Update URL with filter parameters
                    window.history.pushState({}, '', `?${params}`);
                });
            }

            // Debounce function
            function debounce(func, wait) {
                let timeout;
                return function executedFunction(...args) {
                    const later = () => {
                        clearTimeout(timeout);
                        func(...args);
                    };
                    clearTimeout(timeout);
                    timeout = setTimeout(later, wait);
                };
            }

            // Add event listeners with debounce
            const debouncedFetch = debounce(fetchFilteredData, 300);
            [departemenFilter, tahunFilter, pembimbingFilter].forEach(filter => {
                if (filter) {
                    filter.addEventListener('change', debouncedFetch);
                }
            });
            
            if (searchInput) {
                searchInput.addEventListener('input', debouncedFetch);
            }

            // Handle pagination clicks
            document.addEventListener('click', function(e) {
                if (e.target.closest('.pagination a')) {
                    e.preventDefault();
                    const url = new URL(e.target.closest('a').href);
                    const page = url.searchParams.get('page');
                    
                    const params = new URLSearchParams(window.location.search);
                    params.set('page', page);
                    
                    fetchFilteredData();
                }
            });
        });
    </script>
@endpush
