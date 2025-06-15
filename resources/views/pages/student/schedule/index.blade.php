@extends('layouts.auth')

@section('title', 'Jadwal Mahasiswa')

@section('content')
<div class="px-6 py-4">
    <h4 class="text-xl font-semibold mb-6">Jadwal Magang</h4>

    <!-- Jadwal Hari Ini Section -->
    <div class="bg-white rounded-lg shadow-sm mb-6">
        <div class="p-6">
            <h5 class="text-lg font-medium mb-4">Jadwal Hari Ini</h5>

            <div class="flex gap-6">
                <!-- Left Side - Calendar -->
                <div class="w-[400px]">
                    <!-- Calendar Controls -->
                    <div class="flex gap-4 mb-6">
                        <div class="relative">
                            <select id="month-select" class="form-select w-36 pl-4 pr-10 py-2 rounded-lg border border-gray-300 appearance-none cursor-pointer">
                                @foreach(['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'] as $index => $month)
                                    <option value="{{ $index + 1 }}" {{ $index + 1 == date('n') ? 'selected' : '' }}>{{ $month }}</option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3">
                                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </div>

                        <div class="relative">
                            <select id="year-select" class="form-select w-28 pl-4 pr-10 py-2 rounded-lg border border-gray-300 appearance-none cursor-pointer">
                                @for ($year = 2020; $year <= 2030; $year++)
                                    <option value="{{ $year }}" {{ $year == date('Y') ? 'selected' : '' }}>{{ $year }}</option>
                                @endfor
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3">
                                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Calendar Grid -->
                    <div class="mb-6">
                        <div class="grid grid-cols-7 mb-2">
                            @foreach(['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'] as $day)
                                <div class="text-center text-sm text-gray-500">{{ $day }}</div>
                            @endforeach
                        </div>

                        <div id="calendar-grid" class="grid grid-cols-7 gap-1">
                            <!-- Calendar days will be inserted here by JavaScript -->
                        </div>
                    </div>

                    <!-- Cancel/Done Buttons -->
                    <div class="flex justify-end gap-2">
                        <button class="calendar-cancel px-4 py-2 rounded-lg border border-gray-300 hover:bg-gray-50 transition-colors">Batal</button>
                        <button class="px-4 py-2 rounded-lg bg-[#637F26] text-white hover:bg-[#4B601C] transition-colors calendar-done">Pilih Tanggal</button>
                    </div>
                </div>

                <!-- Right Side - Schedule Cards -->
                <div class="w-[calc(100%-400px-24px)] flex items-start"> 
                    <div id="today-schedules" class="w-full mt-14 h-[calc(100%-8rem)]">
                        @forelse($todaySchedules as $schedule)
                        <div class="bg-[#F5F7F0] rounded-lg p-3 mb-2">
                            <h6 class="text-base font-medium mb-1">{{ $schedule->internshipClass->name ?? 'N/A' }}</h6>
                            <div class="text-sm text-gray-600 mb-1">{{ $schedule->stase->name ?? 'N/A' }}</div>
                            <div class="text-sm text-gray-600">
                                Pembimbing: {{ $schedule->stase->responsibleUsers->first()?->user->name ?? '-' }}
                            </div>
                        </div>
                        @empty
                        <div class="bg-gray-50 rounded-lg h-[280px] flex items-center justify-center">
                            <div class="text-center">
                                <div class="text-gray-400 mb-2">
                                    <svg class="w-8 h-8 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 002 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div class="text-sm text-gray-500">Tidak ada jadwal untuk tanggal ini</div>
                            </div>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Jadwal Section -->
    <div class="bg-white rounded-lg shadow-sm mb-6">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h5 class="text-lg font-medium">Tabel Jadwal</h5>
            </div>
            
            <!-- Hidden inputs for date range (not visible but used by JS) -->
            <input type="hidden" id="start-date" name="start_date">
            <input type="hidden" id="end-date" name="end_date">
            
            <!-- Filter Pills -->
            <div class="flex gap-2 mb-4">
                <button class="px-3 py-1 text-sm rounded-full bg-gray-100 text-gray-600" id="pill-current-month">Bulan Ini</button>
                <button class="px-3 py-1 text-sm rounded-full bg-gray-100 text-gray-600" id="pill-current-week">Minggu Ini</button>
                <button class="px-3 py-1 text-sm rounded-full bg-gray-100 text-gray-600" id="pill-next-week">Minggu Depan</button>
                <button class="px-3 py-1 text-sm rounded-full bg-gray-100 text-gray-600" id="pill-next-month">Bulan Depan</button>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-8 py-4 text-center text-base font-bold text-gray-700 border border-gray-200">Tanggal</th>
                            <th class="px-8 py-4 text-center text-base font-bold text-gray-700 border border-gray-200">Stase</th>
                            <th class="px-8 py-4 text-center text-base font-bold text-gray-700 border border-gray-200">Kelas</th>
                            <th class="px-8 py-4 text-center text-base font-bold text-gray-700 border border-gray-200">Nama Penanggung Jawab</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($schedules as $schedule)
                        <tr class="hover:bg-gray-50">
                            <td class="px-8 py-4 text-sm text-center border border-gray-200">
                                {{ Carbon\Carbon::parse($schedule->start_date)->locale('id')->isoFormat('D MMMM Y') }} - 
                                {{ Carbon\Carbon::parse($schedule->end_date)->locale('id')->isoFormat('D MMMM Y') }}
                            </td>
                            <td class="px-8 py-4 text-sm text-center border border-gray-200">{{ $schedule->stase->name ?? 'N/A' }}</td>
                            <td class="px-8 py-4 text-sm text-center border border-gray-200">{{ $schedule->internshipClass->name ?? 'N/A' }}</td>
                            <td class="px-8 py-4 text-sm text-center border border-gray-200">{{ $schedule->stase->responsibleUsers->first()?->user->name ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-8 py-4 text-sm text-center text-gray-500 border border-gray-200">
                                Tidak ada jadwal yang ditemukan
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Print Button -->
            <div class="flex justify-end mt-4">
                <button 
                    onclick="printSchedule()"
                    class="px-4 py-2 rounded-lg bg-[#637F26] text-white hover:bg-[#4B601C] transition-colors">
                    Cetak Jadwal
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function formatDate(dateString) {
    if (!dateString) return 'N/A';
    
    const date = new Date(dateString);
    const options = { 
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    };
    
    return date.toLocaleDateString('id-ID', options);
}

let selectedDate = new Date().toISOString().split('T')[0]; // Set default selected date to today

function generateCalendar(year, month) {
    const firstDay = new Date(year, month - 1, 1);
    const daysInMonth = new Date(year, month, 0).getDate();
    const startingDay = firstDay.getDay();
    const today = new Date().toISOString().split('T')[0];
    
    // Get previous month's days
    const prevMonth = new Date(year, month - 2, 0);
    const prevMonthDays = prevMonth.getDate();
    
    let calendarHTML = '';
    
    // Previous month days
    for (let i = startingDay - 1; i >= 0; i--) {
        calendarHTML += `
            <div class="text-center text-gray-400 calendar-day">
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
                class="calendar-day text-center rounded-lg
                    ${isToday ? 'today' : ''}
                    ${isSelected ? 'selected-date' : ''}"
                onclick="selectDate('${date}', this)">
                ${day}
            </div>`;
    }
    
    // Calculate remaining days for next month
    const totalDays = startingDay + daysInMonth;
    const remainingDays = 42 - totalDays; // 42 = 6 rows × 7 days
    
    // Next month days
    for (let i = 1; i <= remainingDays; i++) {
        calendarHTML += `
            <div class="text-center text-gray-400 calendar-day">
                ${i}
            </div>`;
    }
    
    document.getElementById('calendar-grid').innerHTML = calendarHTML;
}

function selectDate(date, element) {
    // Remove previous selection
    const previousSelected = document.querySelector('.calendar-day.selected-date');
    if (previousSelected) {
        previousSelected.classList.remove('selected-date');
        // Keep the today class if it exists
        if (previousSelected.classList.contains('today')) {
            previousSelected.classList.add('today');
        }
    }
    
    // Add selection to clicked date
    element.classList.add('selected-date');
    selectedDate = date;
}

function loadSchedules(date) {
    const scheduleContainer = document.querySelector('#today-schedules');
    
    fetch(`/student/schedule/by-date?date=${date}`)
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                throw new Error(data.message || 'Terjadi kesalahan');
            }

            if (!data.schedules || data.schedules.length === 0) {
                scheduleContainer.innerHTML = `
                    <div class="bg-gray-50 rounded-lg p-4 text-center h-full flex items-center justify-center">
                        <div>
                            <div class="text-gray-400 mb-2">
                                <svg class="w-8 h-8 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 002 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div class="text-sm text-gray-500">Tidak ada jadwal untuk tanggal ini</div>
                        </div>
                    </div>`;
                return;
            }

            const schedulesHTML = data.schedules.map(schedule => `
                <div class="bg-[#F5F7F0] rounded-lg p-3 mb-2">
                    <h6 class="text-base font-medium mb-1">${schedule.class}</h6>
                    <div class="text-sm text-gray-600 mb-1">${schedule.stase}</div>
                    <div class="text-sm text-gray-600">Pembimbing: ${schedule.responsibleUser}</div>
                </div>
            `).join('');
            
            scheduleContainer.innerHTML = `<div class="flex flex-col justify-center">${schedulesHTML}</div>`;
        })
        .catch(error => {
            scheduleContainer.innerHTML = `
                <div class="text-red-500 text-center py-4">
                    ${error.message || 'Terjadi kesalahan saat memuat jadwal'}
                </div>`;
        });
}

// Function to load all schedules (default view)
function loadAllSchedules() {
    const tableBody = document.querySelector('table tbody');
    
    // Show loading state
    tableBody.innerHTML = `
        <tr>
            <td colspan="4" class="px-6 py-4 text-sm text-center text-gray-500">
                <div class="flex items-center justify-center">
                    <svg class="animate-spin h-5 w-5 mr-3 text-gray-500" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Memuat data...
                </div>
            </td>
        </tr>
    `;

    fetch('/student/schedule/all')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (!data.success) {
                throw new Error(data.message || 'Terjadi kesalahan');
            }

            if (!data.schedules || data.schedules.length === 0) {
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-sm text-center text-gray-500">
                            Tidak ada jadwal yang ditemukan
                        </td>
                    </tr>`;
                return;
            }

            tableBody.innerHTML = data.schedules.map(schedule => `
                <tr>
                    <td class="px-8 py-4 text-sm text-center border border-gray-200">
                        ${schedule.startDateFormatted} - ${schedule.endDateFormatted}
                    </td>
                    <td class="px-8 py-4 text-sm text-center border border-gray-200">${schedule.stase}</td>
                    <td class="px-8 py-4 text-sm text-center border border-gray-200">${schedule.class}</td>
                    <td class="px-8 py-4 text-sm text-center border border-gray-200">${schedule.responsibleUser}</td>
                </tr>
            `).join('');
        })
        .catch(error => {
            console.error('Error:', error);
            tableBody.innerHTML = `
                <tr>
                    <td colspan="4" class="px-6 py-4 text-sm text-center text-red-500">
                        ${error.message || 'Terjadi kesalahan saat memuat data'}
                    </td>
                </tr>`;
        });
}

// Print function
function printSchedule() {
    // Create print content
    let printContent = `
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Jadwal Magang - RSUD dr. Adhyatma, MPH</title>
            <style>
                @page {
                    margin: 20px;
                    size: A4 portrait;
                }
                body { 
                    font-family: Arial, sans-serif;
                    margin: 0;
                    padding: 20px;
                }
                .main-header {
                    text-align: center;
                    margin: 20px 0 30px;
                }
                .main-header h2 {
                    margin: 0;
                    font-size: 18px;
                }
                table { 
                    width: 100%;
                    border-collapse: collapse;
                    margin-top: 20px;
                }
                th, td { 
                    border: 1px solid #ddd;
                    padding: 8px;
                    text-align: center;
                    font-size: 12px;
                }
                th { 
                    background-color: #f8f9fa;
                    font-weight: bold;
                }
            </style>
        </head>
        <body>
            <div class="main-header">
                <h2>Jadwal Magang</h2>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Stase</th>
                        <th>Kelas</th>
                        <th>Penanggung Jawab</th>
                    </tr>
                </thead>
                <tbody>
    `;

    // Get current table content
    const rows = document.querySelectorAll('table tbody tr');
    rows.forEach(row => {
        const cells = Array.from(row.querySelectorAll('td'));
        if (cells.length === 4) {
            printContent += `
                <tr>
                    <td>${cells[0].textContent}</td>
                    <td>${cells[1].textContent}</td>
                    <td>${cells[2].textContent}</td>
                    <td>${cells[3].textContent}</td>
                </tr>
            `;
        }
    });

    printContent += `
                </tbody>
            </table>
        </body>
        </html>
    `;

    // Create print window
    const printWindow = window.open('', '_blank');
    printWindow.document.write(printContent);
    printWindow.document.close();

    // Print after content loads
    printWindow.onload = function() {
        printWindow.focus();
        setTimeout(() => {
            printWindow.print();
            printWindow.close();
        }, 250);
    };
}

function applyFilter() {
    const startDate = document.getElementById('start-date').value;
    const endDate = document.getElementById('end-date').value;

    if (!startDate || !endDate) {
        alert('Mohon pilih rentang tanggal');
        return;
    }

    if (new Date(startDate) > new Date(endDate)) {
        alert('Tanggal mulai tidak boleh lebih besar dari tanggal selesai');
        return;
    }

    // Show loading state
    const tableBody = document.querySelector('table tbody');
    tableBody.innerHTML = `
        <tr>
            <td colspan="4" class="px-6 py-4 text-sm text-center text-gray-500">
                <div class="flex items-center justify-center">
                    <svg class="animate-spin h-5 w-5 mr-3 text-gray-500" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Memuat data...
                </div>
            </td>
        </tr>
    `;

    fetch(`/student/schedule/filtered?filter=${getCurrentFilter()}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (!data.success) {
                throw new Error(data.message || 'Terjadi kesalahan');
            }

            if (!data.schedules || data.schedules.length === 0) {
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-sm text-center text-gray-500">
                            Tidak ada jadwal yang ditemukan
                        </td>
                    </tr>`;
                return;
            }

            tableBody.innerHTML = data.schedules.map(schedule => `
                <tr>
                    <td class="px-8 py-4 text-sm text-center border border-gray-200">
                        ${schedule.startDateFormatted} - ${schedule.endDateFormatted}
                    </td>
                    <td class="px-8 py-4 text-sm text-center border border-gray-200">${schedule.stase}</td>
                    <td class="px-8 py-4 text-sm text-center border border-gray-200">${schedule.class}</td>
                    <td class="px-8 py-4 text-sm text-center border border-gray-200">${schedule.responsibleUser}</td>
                </tr>
            `).join('');
        })
        .catch(error => {
            console.error('Error:', error);
            tableBody.innerHTML = `
                <tr>
                    <td colspan="4" class="px-6 py-4 text-sm text-center text-red-500">
                        ${error.message || 'Terjadi kesalahan saat memuat data'}
                    </td>
                </tr>`;
        });
}

function getCurrentFilter() {
    const activeButton = document.querySelector('.flex.gap-2.mb-4 button.bg-\\[\\#637F26\\]');
    if (activeButton) {
        switch(activeButton.textContent.trim()) {
            case 'Bulan Ini': return 'this-month';
            case 'Minggu Ini': return 'this-week';
            case 'Minggu Depan': return 'next-week';
            case 'Bulan Depan': return 'next-month';
        }
    }
    return 'this-month';
}

// Document ready
document.addEventListener('DOMContentLoaded', function() {
    const today = new Date();
    
    // Handle Done button click
    const doneButton = document.querySelector('.calendar-done');
    if (doneButton) {
        doneButton.addEventListener('click', function() {
            if (selectedDate) {
                loadSchedules(selectedDate);
            } else {
                alert('Silakan pilih tanggal terlebih dahulu');
            }
        });
    }

    // Handle Cancel button click
    const cancelButton = document.querySelector('.calendar-cancel');
    if (cancelButton) {
        cancelButton.addEventListener('click', function() {
            const todayString = today.toISOString().split('T')[0];
            
            // Update month and year selects to today first
            document.getElementById('month-select').value = today.getMonth() + 1;
            document.getElementById('year-select').value = today.getFullYear();
            
            // Update selectedDate to today
            selectedDate = todayString;
            
            // Generate calendar with today highlighted
            generateCalendar(today.getFullYear(), today.getMonth() + 1);
            
            // Load today's schedules
            loadSchedules(todayString);

            // Find and update visual selection
            const todayElement = document.querySelector(`[data-date="${todayString}"]`);
            if (todayElement) {
                const previousSelected = document.querySelector('.calendar-day.selected-date');
                if (previousSelected) {
                    previousSelected.classList.remove('selected-date');
                }
                todayElement.classList.add('selected-date');
            }
        });
    }

    // Add click handlers for filter pills
    document.querySelectorAll('.flex.gap-2.mb-4 button').forEach(button => {
        button.addEventListener('click', function() {
            // Remove active state from all pills
            document.querySelectorAll('.flex.gap-2.mb-4 button').forEach(btn => {
                btn.classList.remove('bg-[#637F26]', 'text-white');
                btn.classList.add('bg-gray-100', 'text-gray-600');
            });
            
            // Add active state to clicked pill
            this.classList.remove('bg-gray-100', 'text-gray-600');
            this.classList.add('bg-[#637F26]', 'text-white');

            const startDate = document.getElementById('start-date');
            const endDate = document.getElementById('end-date');
            
            switch(this.textContent.trim()) {
                case 'Bulan Ini':
                    startDate.value = new Date(today.getFullYear(), today.getMonth(), 1)
                        .toISOString().split('T')[0];
                    endDate.value = new Date(today.getFullYear(), today.getMonth() + 1, 0)
                        .toISOString().split('T')[0];
                    break;
                case 'Minggu Ini':
                    const thisWeekStart = new Date(today);
                    thisWeekStart.setDate(today.getDate() - today.getDay());
                    const thisWeekEnd = new Date(thisWeekStart);
                    thisWeekEnd.setDate(thisWeekStart.getDate() + 6);
                    
                    startDate.value = thisWeekStart.toISOString().split('T')[0];
                    endDate.value = thisWeekEnd.toISOString().split('T')[0];
                    break;
                case 'Minggu Depan':
                    const nextWeekStart = new Date(today);
                    nextWeekStart.setDate(today.getDate() - today.getDay() + 7);
                    const nextWeekEnd = new Date(nextWeekStart);
                    nextWeekEnd.setDate(nextWeekStart.getDate() + 6);
                    
                    startDate.value = nextWeekStart.toISOString().split('T')[0];
                    endDate.value = nextWeekEnd.toISOString().split('T')[0];
                    break;
                case 'Bulan Depan':
                    const nextMonth = new Date(today.getFullYear(), today.getMonth() + 1, 1);
                    const lastDayOfNextMonth = new Date(today.getFullYear(), today.getMonth() + 2, 0);
                    
                    startDate.value = nextMonth.toISOString().split('T')[0];
                    endDate.value = lastDayOfNextMonth.toISOString().split('T')[0];
                    break;
            }
            
            // Trigger filter after setting dates
            applyFilter();
        });
    });

    // Load today's schedules and highlight today's date on calendar
    const currentYear = today.getFullYear();
    const currentMonth = today.getMonth() + 1;
    
    // Set initial year and month selects
    document.getElementById('year-select').value = currentYear;
    document.getElementById('month-select').value = currentMonth;
    
    // Generate calendar with today highlighted
    generateCalendar(currentYear, currentMonth);
    
    // Load today's schedules
    const todayString = today.toISOString().split('T')[0];
    loadSchedules(todayString);

    // REMOVED: Default filter pill activation and date setting
    // REMOVED: applyFilter() call
    
    // Load all schedules by default (no filter)
    loadAllSchedules();
});

// Event listeners for month and year select
document.getElementById('month-select').addEventListener('change', function() {
    generateCalendar(
        parseInt(document.getElementById('year-select').value),
        parseInt(this.value)
    );
});

document.getElementById('year-select').addEventListener('change', function() {
    generateCalendar(
        parseInt(this.value),
        parseInt(document.getElementById('month-select').value)
    );
});

// Initial calendar generation
generateCalendar(
    parseInt(document.getElementById('year-select').value),
    parseInt(document.getElementById('month-select').value)
);
</script>
@endpush

@push('styles')
<style>
    /* Dropdown styles */
    .form-select {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
        background-position: right 0.5rem center;
        background-repeat: no-repeat;
        background-size: 1.5em 1.5em;
    }

    .form-select:focus {
        border-color: #637F26;
        outline: none;
        box-shadow: 0 0 0 1px rgba(99, 127, 38, 0.2);
    }

    /* Calendar day styles */
    .calendar-day {
        cursor: pointer;
        transition: all 0.2s ease;
        height: 38px;
        width: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 2px;
        font-size: 14px;
    }

    /* Styling untuk hari-hari dari bulan sebelum/sesudah */
    .calendar-day.text-gray-400 {
        pointer-events: none;
        height: 38px;
        width: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 2px;
    }

    .calendar-day.today {
        border: 2px solid #637F26;
        font-weight: 500;
    }

    .calendar-day.selected-date {
        background-color: #637F26;
        color: white;
    }

    .calendar-day.selected-date.today {
        border-color: #637F26;
    }

    .calendar-day:hover:not(.selected-date) {
        background-color: rgb(219, 224, 209);
    }

    /* Update calendar grid styling */
    #calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 1px;
        background-color: #fff;
        padding: 4px;
    }
</style>
@endpush
@endsection