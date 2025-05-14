@extends('layouts.auth')

@section('title', 'Jadwal Magang')

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
            @for ($year = 2000; $year <= 2026; $year++)
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

@push('scripts')
<script>
// Tambahkan fungsi formatDate sebelum fungsi printSchedule
function formatDate(dateString) {
    if (!dateString) return '';
    
    const options = { 
        day: 'numeric', 
        month: 'long', 
        year: 'numeric'
    };
    
    const date = new Date(dateString);
    // Format date to Indonesian locale
    const formattedDate = date.toLocaleDateString('id-ID', options);
    
    return formattedDate;
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
            <div class="text-center p-2 text-gray-400">
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
                class="text-center p-2 cursor-pointer transition-all duration-200 hover:bg-gray-50 rounded-lg calendar-day
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
            <div class="text-center p-2 text-gray-400">
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
    
    fetch(`/responsible/schedule/get-schedules?date=${date}`)
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
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div class="text-sm text-gray-500">Tidak ada jadwal untuk tanggal ini</div>
                        </div>
                    </div>`;
                return;
            }

            const schedulesHTML = data.schedules.map(schedule => `
                <div class="bg-[#F5F7F0] rounded-lg p-3 mb-2">
                    <h6 class="text-base font-medium mb-1">${schedule.class_name}</h6>
                    <div class="text-sm text-gray-600 mb-1">${schedule.stase_name}</div>
                    <div class="text-sm text-gray-600">${schedule.department}</div>
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

// Handle Done button click
document.addEventListener('DOMContentLoaded', function() {
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

    // Perbaikan selector tombol Batal
    const cancelButton = document.querySelector('.calendar-cancel');
    if (cancelButton) {
        cancelButton.addEventListener('click', function() {
            const today = new Date();
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

    // Load today's schedules and highlight today's date on calendar
    const today = new Date();
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

function printSchedule() {
    const startDate = document.getElementById('start-date').value;
    const endDate = document.getElementById('end-date').value;

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
                .page-header {
                    display: flex;
                    justify-content: space-between;
                    align-items: top;
                    margin-bottom: 10px;
                }
                .page-title {
                    font-size: 12px;
                    color: #666;
                    text-align: right;
                }
                .main-header {
                    text-align: center;
                    margin: 20px 0 30px;
                }
                .main-header h2 {
                    margin: 0;
                    font-size: 18px;
                }
                .date-range {
                    text-align: center;
                    margin-bottom: 20px;
                    color: #666;
                    font-size: 14px.
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
                .page-footer {
                    position: fixed;
                    bottom: 20px;
                    left: 20px;
                    font-size: 12px;
                    color: #666;
                }
                .page-number {
                    position: fixed;
                    bottom: 20px;
                    right: 20px;
                    font-size: 12px;
                    color: #666.
                }
            </style>
        </head>
        <body>
            <div class="page-header">
                <div style="width: 50%;">
                    <img src="/images/logo.png" height="50" style="visibility: hidden;">
                </div>
                <div class="page-title">Jadwal Magang</div>
            </div>

            <div class="main-header">
                <h2>Jadwal Magang</h2>
            </div>

            <div class="date-range">
                Periode: ${formatDate(startDate)} - ${formatDate(endDate)}
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Stase</th>
                        <th>Kelas</th>
                        <th>Departemen</th>
                    </tr>
                </thead>
                <tbody>
    `;

    // Get table rows
    const rows = document.querySelectorAll('table tbody tr');
    rows.forEach(row => {
        printContent += row.outerHTML;
    });

    printContent += `
                </tbody>
            </table>
            <div class="page-footer">RSUD dr. Adhyatma, MPH</div>
            <div class="page-number">1/1</div>
        </body>
        </html>
    `;

    // Create print window
    const printWindow = window.open('about:blank', '_blank');
    
    // Set content and trigger print
    printWindow.document.write(printContent);
    printWindow.document.close();
    
    // Add event listener for after content loads
    printWindow.onload = function() {
        printWindow.focus(); // Focus the new window
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

    // Update URL to use the correct route
    fetch(`/responsible/schedule/filter?start_date=${startDate}&end_date=${endDate}`)
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
                    <td class="px-6 py-4 text-sm text-center">${formatDate(schedule.start_date)}</td>
                    <td class="px-6 py-4 text-sm text-center">${schedule.stase?.name || 'N/A'}</td>
                    <td class="px-6 py-4 text-sm text-center">${schedule.internship_class?.name || 'N/A'}</td>
                    <td class="px-6 py-4 text-sm text-center">${schedule.stase?.departement?.name || 'N/A'}</td>
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

// Update filter pill buttons to trigger date range updates
document.addEventListener('DOMContentLoaded', function() {
    const today = new Date();
    
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

    // Set default dates (current month)
    document.getElementById('start-date').value = new Date(today.getFullYear(), today.getMonth(), 1)
        .toISOString().split('T')[0];
    document.getElementById('end-date').value = new Date(today.getFullYear(), today.getMonth() + 1, 0)
        .toISOString().split('T')[0];
});
function toggleStudentList() {
    const studentList = document.getElementById('student-list');
    const toggleBtn = document.getElementById('toggle-students-btn');
    
    if (studentList.classList.contains('hidden')) {
        // Show student list
        studentList.classList.remove('hidden');
        toggleBtn.textContent = 'Tutup';
    } else {
        // Hide student list
        studentList.classList.add('hidden');
        toggleBtn.textContent = 'Lihat Mahasiswa';
    }
}
</script>
@endpush

                    <!-- Cancel/Done Buttons -->
                    <div class="flex justify-end gap-2">
                        <button class="calendar-cancel px-4 py-2 rounded-lg border border-gray-300 hover:bg-gray-50 transition-colors">Batal</button>
                        <button class="px-4 py-2 rounded-lg bg-[#637F26] text-white hover:bg-[#4B601C] transition-colors calendar-done">Pilih Tanggal</button>
                    </div>
                </div>

                <!-- Right Side - Schedule Cards -->
                <div class="w-[calc(100%-400px-24px)] flex items-center">
                    <div id="today-schedules" class="w-full space-y-2">
                        @forelse($todaySchedules as $schedule)
                        <div class="bg-[#F5F7F0] rounded-lg p-3">
                            <h6 class="text-base font-medium mb-1">{{ $schedule['class_name'] }}</h6>
                            <div class="text-sm text-gray-600 mb-1">{{ $schedule['stase_name'] }}</div>
                            <div class="text-sm text-gray-600">{{ $schedule['department'] }}</div>
                        </div>
                        @empty
                        <div class="bg-gray-50 rounded-lg p-4 text-center">
                            <div>
                                <div class="text-gray-400 mb-2">
                                    <svg class="w-8 h-8 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div class="text-sm text-gray-500">Tidak ada jadwal untuk hari ini</div>
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
                <div class="flex gap-4">
                    <div class="flex items-center gap-2">
                        <input 
                            type="date" 
                            id="start-date"
                            class="form-input px-4 py-2 rounded-lg border border-gray-300" 
                            placeholder="Tanggal Mulai">
                        <span class="text-gray-500">s/d</span>
                        <input 
                            type="date" 
                            id="end-date"
                            class="form-input px-4 py-2 rounded-lg border border-gray-300" 
                            placeholder="Tanggal Selesai">
                    </div>
                    <div class="relative">
                    </div>
                    <button 
                        onclick="applyFilter()"
                        class="px-4 py-2 rounded-lg bg-[#637F26] text-white hover:bg-[#4B601C] transition-colors">
                        Terapkan
                    </button>
                </div>
            </div>
            
            <!-- Filter Pills -->
            <div class="flex gap-2 mb-4">
                <button class="px-3 py-1 text-sm rounded-full bg-gray-100 text-gray-600">Bulan Ini</button>
                <button class="px-3 py-1 text-sm rounded-full bg-gray-100 text-gray-600">Minggu Ini</button>
                <button class="px-3 py-1 text-sm rounded-full bg-gray-100 text-gray-600">Minggu Depan</button>
                <button class="px-3 py-1 text-sm rounded-full bg-gray-100 text-gray-600">Bulan Depan</button>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-center text-sm font-bold text-gray-700 min-w-[150px]">Tanggal</th>
                            <th class="px-6 py-3 text-center text-sm font-bold text-gray-700">Stase</th>
                            <th class="px-6 py-3 text-center text-sm font-bold text-gray-700">Kelas</th>
                            <th class="px-6 py-3 text-center text-sm font-bold text-gray-700">Departemen</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($schedules as $schedule)
                        <tr>
                            <td class="px-6 py-4 text-sm text-center">{{ Carbon\Carbon::parse($schedule->start_date)->format('M d, Y') }}</td>
                            <td class="px-6 py-4 text-sm text-center">{{ $schedule->stase->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-sm text-center">{{ $schedule->internshipClass->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-sm text-center">{{ $schedule->stase->departement->name ?? 'N/A' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-sm text-center text-gray-500">
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

    <!-- Detail Kelas Section -->
    <div class="bg-white rounded-lg shadow-sm mb-6">
        <div class="p-6">
            <h5 class="text-lg font-medium mb-4">Detail Kelas</h5>
        
        @if($currentClass)
            <div class="bg-[#F5F7F0] rounded-lg" id="class-container">
                <!-- Class Header -->
                <div class="p-6 border-b border-gray-200" id="class-detail">
                    <div class="flex items-center justify-between mb-2">
                        <h5 class="text-xl font-medium">{{ $currentClass->name }} - {{ $currentSchedule->stase->name ?? 'N/A' }}</h5>
                    </div>
                    <p class="text-gray-600 mb-4">{{ $students->count() }} mahasiswa terdaftar</p>
                    
                    <button 
                        onclick="toggleStudentList()" 
                        class="px-4 py-2 rounded-lg bg-[#637F26] text-white hover:bg-[#4B601C] transition-colors"
                        id="toggle-students-btn">
                        Lihat Mahasiswa
                    </button>
                </div>

                <!-- Student List (Hidden by default) -->
                <div id="student-list" class="hidden border-t border-gray-200">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h6 class="text-lg font-semibold">Daftar Mahasiswa</h6>
                            <button 
                                onclick="toggleStudentList()" 
                                class="text-gray-500 hover:text-gray-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        
                        <!-- Student List item -->
                        <div class="space-y-4">
                            @foreach($students as $student)
                            <div class="flex items-center justify-between p-4 rounded-lg bg-white transition-colors student-card">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-full bg-gray-200 overflow-hidden flex items-center justify-center">
                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h6 class="font-medium">{{ $student->user->name ?? 'Unnamed Student' }}</h6>
                                        <p class="text-gray-600">{{ $student->studyProgram->name ?? 'Unknown Program' }}, Kelas {{ $student->class ?? '3' }}</p>
                                        <p class="text-gray-600">{{ $student->studyProgram->campus->name ?? 'Unknown Campus' }}</p>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="text-gray-500 text-center py-4">
                Tidak ada kelas yang dipilih
            </div>
        @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .form-select:focus, .form-input:focus {
        outline: none;
        border-color: #637F26;
        ring-color: #F5F7F0;
    }

    .calendar-day {
        transition: all 0.2s ease-in-out;
    }

    .calendar-day:hover {
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .calendar-day.selected-date {
        background-color: #637F26;
        color: white;
    }

    .calendar-day.selected-date:hover {
        background-color: #637F26;
    }

    .calendar-day.today {
        border: 2px solid #637F26;
    }

    .calendar-day.selected-date.today {
        border: 2px solid #637F26;
    }

    /* Hover effect untuk daftar mahasiswa */
    .student-card {
        transition: all 0.2s ease-in-out;
    }

    .student-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        background-color: #fafafa;
    }

    @media print {
        body * {
            visibility: hidden;
        }
        
        #print-content, #print-content * {
            visibility: visible;
        }
        
        #print-content {
            position: absolute;
            left: 0;
            top: 0;
        }
        
        .no-print {
            display: none;
        }
    }
</style>
@endpush