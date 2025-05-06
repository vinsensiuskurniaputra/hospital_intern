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
                    <div class="bg-gray-50 rounded-lg p-4 text-center flex items-center justify-center h-full">
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
                    <div class="text-sm text-gray-600">${schedule.department}</div>
                </div>
            `).join('');
            
            scheduleContainer.innerHTML = schedulesHTML;
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
</script>
@endpush

                    <!-- Cancel/Done Buttons -->
                    <div class="flex justify-end gap-2">
                        <button class="calendar-cancel px-4 py-2 rounded-lg border border-gray-300 hover:bg-gray-50 transition-colors">Batal</button>
                        <button class="px-4 py-2 rounded-lg bg-[#637F26] text-white hover:bg-[#4B601C] transition-colors calendar-done">Pilih Tanggal</button>
                    </div>
                </div>

                <!-- Right Side - Schedule Cards -->
                <div class="w-[calc(100%-400px-24px)]"> <!-- Hapus h-[400px] dari sini -->
                    <div id="today-schedules" class="space-y-2"> <!-- Tambahkan space-y-2 untuk spacing yang konsisten -->
                        @forelse($todaySchedules as $schedule)
                        <div class="bg-[#F5F7F0] rounded-lg p-3">  <!-- Hapus mb-2 karena sudah menggunakan space-y-2 -->
                            <h6 class="text-base font-medium mb-1">{{ $schedule['class_name'] }}</h6>
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
    <input type="date" class="form-input px-4 py-2 rounded-lg border border-gray-300" placeholder="Rentang Tanggal">
    <div class="relative">
        <select class="form-select w-64 pl-4 pr-10 py-2 rounded-lg border border-gray-300 appearance-none cursor-pointer">
            <option value="" disabled selected>Pilih departemen</option>
            @foreach($departments as $department)
            <option value="{{ $department->id }}">{{ $department->name }}</option>
            @endforeach
        </select>
        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3">
            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </div>
    </div>
                    <button class="px-4 py-2 rounded-lg bg-[#637F26] text-white hover:bg-[#4B601C] transition-colors calendar-done">Terapkan</button>
                </div>
            </div>

            <!-- Filter Pills -->
            <div class="flex gap-2 mb-4">
                <button class="px-3 py-1 text-sm rounded-full bg-gray-100 text-gray-600">Bulan Ini</button>
                <button class="px-3 py-1 text-sm rounded-full bg-gray-100 text-gray-600">Minggu Ini</button>
                <button class="px-3 py-1 text-sm rounded-full bg-gray-100 text-gray-600">Minggu Depan</button>
                <button class="px-3 py-1 text-sm rounded-full bg-gray-100 text-gray-600">Bulan Ini</button>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-center text-xs text-gray-600 min-w-[150px]">Tanggal</th>
                            <th class="px-6 py-3 text-center text-xs text-gray-600">Kelas</th>
                            <th class="px-6 py-3 text-center text-xs text-gray-600">Nama Penanggung Jawab</th>
                            <th class="px-6 py-3 text-center text-xs text-gray-600">Departemen</th>
                            <th class="px-6 py-3 text-center text-xs text-gray-600">Jam</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
    @forelse($schedules as $schedule)
    <tr>
        <td class="px-6 py-4 text-sm text-center">{{ Carbon\Carbon::parse($schedule->date_schedule)->format('M d, Y') }}</td>
        <td class="px-6 py-4 text-sm text-center">{{ $schedule->internshipClass->name ?? 'N/A' }}</td>
        <td class="px-6 py-4 text-sm text-center">{{ $schedule->stase->responsibleUser->name ?? 'N/A' }}</td>
        <td class="px-6 py-4 text-sm text-center">{{ $schedule->stase->departement->name ?? 'N/A' }}</td>
        <td class="px-6 py-4 text-sm text-center">8:00 - 15:00</td>
    </tr>
    @empty
    <tr>
        <td colspan="5" class="px-6 py-4 text-sm text-center text-gray-500">
            Tidak ada jadwal yang ditemukan
        </td>
    </tr>
    @endforelse
</tbody>
                </table>
            </div>

            <!-- Print Button -->
            <div class="flex justify-end mt-4">
                <button class="px-4 py-2 rounded-lg bg-[#637F26] text-white hover:bg-[#4B601C] transition-colors calendar-done">
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
<div class="bg-[#F5F7F0] rounded-lg p-6">
    <div class="space-y-3">
        <h5 class="text-xl font-medium">{{ $currentClass->name }}</h5>
        <p class="text-gray-600">{{ count($students) }} mahasiswa terdaftar</p>
        
        <div class="flex gap-3 mt-4">
            <button class="px-4 py-2 rounded-lg bg-[#637F26] text-white hover:bg-[#4B601C] transition-colors">
                Lihat Mahasiswa
            </button>
        </div>
    </div>

    <div class="mt-8 space-y-4">
        <h6 class="text-lg font-semibold">Daftar Mahasiswa</h6>
        
        @foreach($students as $student)
        <div class="flex items-center justify-between p-4 rounded-lg hover:bg-white transition-colors group">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-gray-200 overflow-hidden">
                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </div>
                <div>
                    <h6 class="font-medium">{{ $student->user->name ?? 'Unnamed Student' }}</h6>
                    <p class="text-gray-600">{{ $student->studyProgram->name ?? 'Unknown Program' }}, Kelas {{ $student->class ?? '3' }}</p>
                </div>
            </div>
            <button class="text-gray-400 hover:text-gray-600">
                kirim pesan
            </button>
        </div>
        @endforeach
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
</style>
@endpush