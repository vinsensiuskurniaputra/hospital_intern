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
let selectedDate = null;

function generateCalendar(year, month) {
    const firstDay = new Date(year, month - 1, 1);
    const daysInMonth = new Date(year, month, 0).getDate();
    const startingDay = firstDay.getDay();
    
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
        
        calendarHTML += `
            <div 
                data-date="${date}"
                class="text-center p-2 cursor-pointer transition-all duration-200 hover:bg-gray-50 rounded-lg calendar-day
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
    }
    
    // Add selection to clicked date
    element.classList.add('selected-date');
    selectedDate = date;
    
    // You can handle the selected date here
    console.log('Selected date:', date);
}

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
                        <button class="px-4 py-2 rounded-lg border border-gray-300">Cancel</button>
                        <button class="px-4 py-2 rounded-lg bg-[#637F26] text-white">Done</button>
                    </div>
                </div>

                <!-- Right Side - Schedule Cards -->
                <div class="flex-1 space-y-4">
                    <!-- Schedule Card 1 -->
                    <div class="bg-[#F5F7F0] rounded-lg p-4">
                        <h6 class="text-lg font-medium">Kelas FK-01</h6>
                        <div class="flex items-center gap-2 mt-2 text-gray-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>10:00 - 14:00</span>
                        </div>
                        <div class="text-gray-600 mt-1">Departemen Kulit</div>
                    </div>

                    <!-- Schedule Card 2 -->
                    <div class="bg-[#F5F7F0] rounded-lg p-4">
                        <h6 class="text-lg font-medium">Kelas FK-01</h6>
                        <div class="flex items-center gap-2 mt-2 text-gray-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>10:00 - 14:00</span>
                        </div>
                        <div class="text-gray-600 mt-1">Departemen THT</div>
                    </div>

                    <!-- Schedule Card 3 -->
                    <div class="bg-[#F5F7F0] rounded-lg p-4">
                        <h6 class="text-lg font-medium">Kelas FK-01</h6>
                        <div class="flex items-center gap-2 mt-2 text-gray-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>10:00 - 14:00</span>
                        </div>
                        <div class="text-gray-600 mt-1">Departemen Mata</div>
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
            <option value="kulit">Departemen Kulit</option>
            <option value="tht">Departemen THT</option>
            <option value="mata">Departemen Mata</option>
            <option value="jantung">Departemen Jantung</option>
            <option value="saraf">Departemen Saraf</option>
            <option value="gigi">Departemen Gigi</option>
            <option value="anak">Departemen Anak</option>
            <option value="paru">Departemen Paru</option>
            <option value="bedah">Departemen Bedah</option>
            <option value="ortopedi">Departemen Ortopedi</option>
            <option value="urologi">Departemen Urologi</option>
            <option value="obgyn">Departemen Obstetri & Ginekologi</option>
        </select>
        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3">
            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </div>
    </div>
                    <button class="px-4 py-2 rounded-lg bg-[#637F26] text-white">Terapkan</button>
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
                        @foreach(range(1, 5) as $i)
                        <tr>
                            <td class="px-6 py-4 text-sm text-center">Mei {{ 14 + $i }}, 2023</td>
                            <td class="px-6 py-4 text-sm text-center">Kelas {{ chr(64 + $i) }}-2023</td>
                            <td class="px-6 py-4 text-sm text-center">dr. Jeki Indomie</td>
                            <td class="px-6 py-4 text-sm text-center">Cardiology</td>
                            <td class="px-6 py-4 text-sm text-center">8:00 - 15:00</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Print Button -->
            <div class="flex justify-end mt-4">
                <button class="px-4 py-2 text-sm rounded-lg border bg-[#637F26] text-white">
                    Cetak Jadwal
                </button>
            </div>
        </div>
    </div>

    <!-- Detail Kelas Section -->
    <div class="bg-white rounded-lg shadow-sm mb-6">
        <div class="p-6">
            <h5 class="text-lg font-medium mb-4">Detail Kelas</h5>
        
        <div class="bg-[#F5F7F0] rounded-lg p-6">
            <div class="space-y-3">
                <h5 class="text-xl font-medium">Kelas A-2023</h5>
                <p class="text-gray-600">8 mahasiswa terdaftar di rotasi Kardiologi pada Mei 15, 2023</p>
                
                <div class="flex gap-3 mt-4">
                    <button class="px-4 py-2 rounded-lg bg-[#637F26] text-white hover:bg-[#4B601C] transition-colors">
                        Lihat Mahasiswa
                    </button>
                    <button class="px-4 py-2 rounded-lg bg-[#637F26] text-white hover:bg-[#4B601C] transition-colors">
                        Edit Jadwal
                    </button>
                </div>
            </div>
                    <!-- Students List -->
                    <div class="mt-8 space-y-4">
                <h6 class="text-lg font-semibold">Daftar Mahasiswa</h6>
                
                <!-- Student Item 1 -->
                <div class="flex items-center justify-between p-4 rounded-lg hover:bg-white transition-colors group">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-gray-200 overflow-hidden">
                            <!-- Calendar icon as placeholder -->
                            <div class="w-full h-full flex items-center justify-center text-gray-400">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h6 class="font-medium">Yanual Arvin</h6>
                            <p class="text-gray-600">Kedokteran, Kelas 3, Universitas Diponegoro</p>
                        </div>
                    </div>
                    <button class="text-gray-400 hover:text-gray-600">
                        kirim pesan
                    </button>
                </div>

                <!-- Student Item 2 -->
                <div class="flex items-center justify-between p-4 rounded-lg hover:bg-white transition-colors group">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-gray-200 overflow-hidden">
                            <!-- Calendar icon as placeholder -->
                            <div class="w-full h-full flex items-center justify-center text-gray-400">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h6 class="font-medium">Angga Penghuni Kidemang</h6>
                            <p class="text-gray-600">Kedokteran, Kelas 3, Universitas Udayana</p>
                        </div>
                    </div>
                    <button class="text-gray-400 hover:text-gray-600">
                        kirim pesan
                    </button>
                </div>
            </div>
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
</style>
@endpush