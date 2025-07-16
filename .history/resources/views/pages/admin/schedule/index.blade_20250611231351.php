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
                        <select id="departemen-filter"
                            class="w-full border border-gray-300 rounded-md py-2 px-4 pr-8 appearance-none bg-white">
                            <option value="">Departemen</option>
                            @foreach ($departments as $department)
                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                            @endforeach
                        </select>
                        <div class="absolute right-3 top-3 pointer-events-none">
                            <svg class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                                </path>
                            </svg>
                        </div>
                    </div>

                    <div class="relative flex-1">
                        <select id="tahun-filter"
                            class="w-full border border-gray-300 rounded-md py-2 px-4 pr-8 appearance-none bg-white">
                            <option value="">Tahun Angkatan</option>
                            @foreach ($internshipClasses as $class)
                                <option value="{{ $class->id }}">{{ $class->classYear->class_year }}</option>
                            @endforeach
                        </select>
                        <div class="absolute right-3 top-3 pointer-events-none">
                            <svg class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                                </path>
                            </svg>
                        </div>
                    </div>

                    <div class="relative flex-1">
                        <select id="pembimbing-filter"
                            class="w-full border border-gray-300 rounded-md py-2 px-4 pr-8 appearance-none bg-white">
                            <option value="">Pembimbing</option>
                            @foreach ($responsibles as $responsible)
                                @if ($responsible && $responsible->user)
                                    <option value="{{ $responsible->id }}">{{ $responsible->user->name }}</option>
                                @endif
                            @endforeach
                        </select>
                        <div class="absolute right-3 top-3 pointer-events-none">
                            <svg class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                                </path>
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
                        <input type="text" 
                            id="search-filter"
                            placeholder="Cari"
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
                        html = '<div class="text-center text-gray-500 py-4">Tidak ada jadwal pada tanggal ini</div>';
                    }
                    
                    scheduleList.innerHTML = html;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                const scheduleList = document.querySelector('.lg\\:col-span-3 .space-y-2');
                scheduleList.innerHTML = '<div class="text-center text-gray-500 py-4">Terjadi kesalahan saat memuat jadwal</div>';
            });
        }

        function updateTableFilters() {
            const departemen = document.getElementById('departemen-filter').value;
            const tahun = document.getElementById('tahun-filter').value;
            const pembimbing = document.getElementById('pembimbing-filter').value;
            const search = document.querySelector('input[type="text"][placeholder="Cari"]').value;

            fetch(`/presences/schedules/filter?departemen=${departemen}&tahun=${tahun}&pembimbing=${pembimbing}&search=${search}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                const tbody = document.querySelector('table tbody');
                tbody.innerHTML = '';
                
                data.schedules.forEach(schedule => {
                    const row = `
                        <tr>
                            <td class="py-3 px-4">${schedule.internship_class?.name || 'N/A'}</td>
                            <td class="py-3 px-4">${schedule.stase?.name || 'N/A'}</td>
                            <td class="py-3 px-4">${schedule.stase?.departement?.name || 'N/A'}</td>
                            <td class="py-3 px-4">${schedule.internship_class?.class_year?.class_year || 'N/A'}</td>
                            <td class="py-3 px-4">${schedule.stase?.responsible_user?.user?.name || 'N/A'}</td>
                            <td class="py-3 px-4">${formatDate(schedule.start_date)} s/d ${formatDate(schedule.end_date)}</td>
                            <td class="py-3 px-4">
                                <div class="flex gap-2">
                                    <a href="/presences/schedules/${schedule.id}/edit" class="text-blue-500">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    <form action="/presences/schedules/${schedule.id}" method="POST" class="inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    `;
                    tbody.insertAdjacentHTML('beforeend', row);
                });

                // Reattach event listeners for delete forms
                document.querySelectorAll('.delete-form').forEach(form => {
                    form.addEventListener('submit', confirmDelete);
                });
            })
            .catch(error => {
                console.error('Error:', error);
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

            // Add event listeners to all filter inputs
            const filterInputs = [
                document.getElementById('departemen-filter'),
                document.getElementById('tahun-filter'),
                document.getElementById('pembimbing-filter'),
                document.querySelector('input[type="text"][placeholder="Cari"]')
            ];

            filterInputs.forEach(input => {
                if (input) {
                    input.addEventListener('change', updateTableFilters);
                    if (input.tagName === 'INPUT') {
                        input.addEventListener('keyup', updateTableFilters);
                    }
                }
            });
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
        document.addEventListener('DOMContentLoaded', function() {
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

            const departemenFilter = document.getElementById('departemen-filter');
            const tahunFilter = document.getElementById('tahun-filter');
            const pembimbingFilter = document.getElementById('pembimbing-filter');
            const searchInput = document.getElementById('search-filter');
            const tbody = document.querySelector('tbody');
            const paginationContainer = document.querySelector('.px-6.py-4.border-t');

            // Tambahkan fungsi untuk handle pagination click
            function handlePaginationClick(e) {
                e.preventDefault();
                const url = new URL(e.target.href);
                const params = new URLSearchParams(url.search);
                
                // Tambahkan filter yang aktif ke params
                params.set('departemen', departemenFilter.value || '');
                params.set('tahun', tahunFilter.value || '');
                params.set('pembimbing', pembimbingFilter.value || '');
                params.set('search', searchInput.value || '');

                // Update URL tanpa reload
                window.history.pushState({}, '', `${url.pathname}?${params.toString()}`);
                
                fetchFilteredData(params);
            }

            // Fungsi untuk fetch data
            function fetchFilteredData(params) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <div class="flex items-center justify-center">
                                <svg class="animate-spin h-5 w-5 text-gray-500 mr-2" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                </svg>
                                Loading...
                            </div>
                        </td>
                    </tr>
                `;

                fetch(`/presences/schedules/filter?${params.toString()}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update table body
                        tbody.innerHTML = data.html;

                        // Update pagination and results count
                        if (paginationContainer) {
                            if (data.total > 0) {
                                // Show pagination if there are results
                                paginationContainer.innerHTML = data.pagination;
                                
                                // Update results count text
                                const resultsCount = document.querySelector('.text-sm.text-gray-700') || 
                                    document.createElement('div');
                                resultsCount.className = 'text-sm text-gray-700';
                                resultsCount.innerHTML = `Showing ${data.from} to ${data.to} of ${data.total} results`;
                                
                                // Add results count if it doesn't exist
                                if (!document.querySelector('.text-sm.text-gray-700')) {
                                    paginationContainer.insertBefore(resultsCount, paginationContainer.firstChild);
                                }
                            } else {
                                // Show "No results found" message
                                paginationContainer.innerHTML = `
                                    <div class="text-sm text-gray-700">No results found</div>
                                `;
                            }
                            
                            // Reattach pagination click handlers
                            paginationContainer.querySelectorAll('a').forEach(link => {
                                link.addEventListener('click', handlePaginationClick);
                            });
                        }
                        
                        // Reattach delete form handlers
                        document.querySelectorAll('.delete-form').forEach(form => {
                            form.addEventListener('submit', confirmDelete);
                        });
                    } else {
                        throw new Error(data.message || 'Error fetching data');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="7" class="text-center py-4 text-red-500">
                                Terjadi kesalahan saat memuat data: ${error.message}
                            </td>
                        </tr>
                    `;
                });
            }

            function applyFilters() {
                const params = new URLSearchParams({
                    departemen: departemenFilter.value || '',
                    tahun: tahunFilter.value || '',
                    pembimbing: pembimbingFilter.value || '',
                    search: searchInput.value || ''
                });

                // Update URL dengan filter
                window.history.pushState({}, '', `${window.location.pathname}?${params.toString()}`);
                
                fetchFilteredData(params);
            }

            // Inisialisasi event listeners
            const debouncedFilter = debounce(applyFilters, 300);

            [departemenFilter, tahunFilter, pembimbingFilter].forEach(filter => {
                filter.addEventListener('change', debouncedFilter);
            });

            searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                applyFilters();
            }
        });

        // Add immediate search after a delay
        searchInput.addEventListener('input', debounce(() => {
            applyFilters();
        }, 500));

            // Attach pagination handlers on initial load
            if (paginationContainer) {
                paginationContainer.querySelectorAll('a').forEach(link => {
                    link.addEventListener('click', handlePaginationClick);
                });
            }

            // Set initial filter values from URL if any
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('departemen')) departemenFilter.value = urlParams.get('departemen');
            if (urlParams.has('tahun')) tahunFilter.value = urlParams.get('tahun');
            if (urlParams.has('pembimbing')) pembimbingFilter.value = urlParams.get('pembimbing');
            if (urlParams.has('search')) searchInput.value = urlParams.get('search');

            if (urlParams.toString()) {
                applyFilters();
            }
        }); 
    </script>
@endpush
