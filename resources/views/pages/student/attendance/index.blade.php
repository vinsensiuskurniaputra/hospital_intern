@extends('layouts.auth')

@section('title', 'Presensi & Sertifikasi')

@section('content')
<div class="container-fluid py-4">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">
        <!-- Total Kehadiran Card -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h5 class="text-lg font-medium mb-4">Total Kehadiran</h5>
            <div class="flex justify-between items-center">
                <div>
                    <div class="text-2xl font-medium mb-4">{{ $attendanceStats['total'] }} Kehadiran</div>
                    <div class="flex flex-col space-y-2">
                        <div class="flex items-center">
                            <span class="w-2 h-2 rounded-full bg-[#22c55e] mr-2"></span>
                            <span class="text-sm">Hadir</span>
                            <span class="text-sm ml-auto">{{ $attendanceStats['present']['percent'] }}%</span>
                        </div>
                        <div class="flex items-center">
                            <span class="w-2 h-2 rounded-full bg-[#f59e0b] mr-2"></span>
                            <span class="text-sm">Izin</span>
                            <span class="text-sm ml-auto">{{ $attendanceStats['sick']['percent'] }}%</span>
                        </div>
                        <div class="flex items-center">
                            <span class="w-2 h-2 rounded-full bg-[#ef4444] mr-2"></span>
                            <span class="text-sm">Alpha</span>
                            <span class="text-sm ml-auto">{{ $attendanceStats['absent']['percent'] }}%</span>
                        </div>
                    </div>
                </div>
                <div class="w-40">
                    <div id="attendanceChart"></div>
                </div>
            </div>
        </div>

        <!-- Sertifikasi Card -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h5 class="text-lg font-medium mb-2">Sertifikasi</h5>
            <h6 class="text-base font-medium mb-3">Unduh Sertifikat</h6>
            <p class="text-gray-600 text-sm mb-4">
                Silahkan unduh sertifikat magang Anda sebagai bukti resmi telah menyelesaikan program magang. 
                Sertifikat ini dapat digunakan untuk keperluan akademik, portfolio, atau kebutuhan profesional lainnya. 
                Pastikan informasi pada sertifikat sudah sesuai sebelum mengunduh.
            </p>
            <div class="flex justify-end">
                <button 
                    onclick="handleDownload({{ $allStagesCompleted ? 'true' : 'false' }})"
                    class="{{ $allStagesCompleted ? 'bg-[#96D67F] hover:bg-[#85c070]' : 'bg-gray-200' }} 
                        px-6 py-2 text-gray-700 rounded-lg transition-colors">
                    Unduh
                </button>
            </div>
        </div>
    </div>

    <!-- Kehadiran & Calendar Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">
        <!-- Kehadiran Mahasiswa Card -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h5 class="text-lg font-medium mb-4">Kehadiran Mahasiswa</h5>
            <div class="flex items-center gap-4 mb-4">
                <div class="text-lg font-medium">{{ $attendanceStats['total'] }} Kehadiran</div>
                <div class="flex items-center text-sm text-red-600">
                    <span class="inline-block w-2 h-2 rounded-full bg-red-500 mr-1"></span>
                    <span>-2.5%</span>
                </div>
                <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">
                    {{ Carbon\Carbon::now()->format('F') }}
                </span>
            </div>
            <div id="attendanceLineChart" class="w-full h-[240px]"></div>
        </div>

        <!-- Calendar Card -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex justify-between items-center mb-6">
                <select id="month-select" class="border border-gray-200 rounded-lg text-sm px-4 py-2 pr-8">
                    @foreach(range(1, 12) as $month)
                        <option value="{{ $month }}" {{ Carbon\Carbon::now()->month == $month ? 'selected' : '' }}>
                            {{ Carbon\Carbon::create()->month($month)->format('F') }}
                        </option>
                    @endforeach
                </select>
                
                <select id="year-select" class="border border-gray-200 rounded-lg text-sm px-4 py-2 pr-8">
                    @foreach(range(2024, 2025) as $year)
                        <option value="{{ $year }}" {{ Carbon\Carbon::now()->year == $year ? 'selected' : '' }}>
                            {{ $year }}
                        </option>
                    @endforeach
                </select>
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
        </div>
    </div>

    <!-- Presensi Setiap Stase -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h5 class="text-lg font-medium mb-6">Presensi Setiap Stase</h5>
        
        @if(count($stases) > 0)
            <div class="space-y-4">
                @foreach($stases as $stase)
                <div class="p-4 rounded-lg {{ $stase['percentage'] >= 80 ? 'bg-[#F5F7F0]' : 'bg-white' }} border border-gray-100">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <h6 class="font-medium">{{ $stase['name'] }}</h6>
                            <p class="text-sm text-gray-500">{{ $stase['department'] }}</p>
                            <p class="text-xs text-gray-400">{{ $stase['date'] }}</p>
                        </div>
                        <div class="text-right">
                            <span class="text-xl font-bold {{ $stase['percentage'] >= 80 ? 'text-[#637F26]' : 'text-gray-900' }}">
                                {{ number_format($stase['percentage']) }}%
                            </span>
                        </div>
                    </div>

                    <!-- Progress Bar -->
                    <div class="mt-4">
                        <div class="w-full bg-gray-100 rounded-full h-2">
                            <div class="bg-[#637F26] h-2 rounded-full transition-all duration-300" 
                                 style="width: {{ $stase['percentage'] }}%">
                            </div>
                        </div>
                    </div>

                    <!-- Attendance Details -->
                    <div class="mt-4 grid grid-cols-3 gap-4">
                        <div class="flex items-center">
                            <span class="w-2 h-2 rounded-full bg-[#22c55e] mr-2"></span>
                            <span class="text-sm text-gray-600">Hadir</span>
                            <span class="text-sm ml-auto">{{ $stase['attendance']['present'] ?? '46.9' }}%</span>
                        </div>
                        <div class="flex items-center">
                            <span class="w-2 h-2 rounded-full bg-[#f59e0b] mr-2"></span>
                            <span class="text-sm text-gray-600">Izin</span>
                            <span class="text-sm ml-auto">{{ $stase['attendance']['sick'] ?? '21' }}%</span>
                        </div>
                        <div class="flex items-center">
                            <span class="w-2 h-2 rounded-full bg-[#ef4444] mr-2"></span>
                            <span class="text-sm text-gray-600">Alpha</span>
                            <span class="text-sm ml-auto">{{ $stase['attendance']['absent'] ?? '12' }}%</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-8">
                <div class="mb-4">
                    <svg class="w-16 h-16 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Stase</h3>
                <p class="text-gray-500 mb-4">Anda belum memiliki stase yang aktif saat ini.</p>
            </div>
        @endif
    </div>
</div>

<!-- Add notification popup -->
<div id="notification" 
    class="fixed top-4 right-4 px-6 py-4 rounded-lg shadow-lg transform transition-all duration-300 opacity-0 translate-y-[-1rem] pointer-events-none">
</div>

<!-- Detail Presensi Modal -->
<div id="detailModal" 
    class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-lg p-6 w-full max-w-md mx-4">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium" id="modalTitle">Detail Presensi</h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <div class="space-y-4">
            <div id="selectedDate" class="text-2xl font-bold text-center mb-6"></div>
            
            <!-- Empty state message -->
            <div id="empty-state" class="hidden text-center py-8">
                <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Presensi</h3>
                <p class="text-gray-500">Tidak ada data presensi pada tanggal ini.</p>
            </div>
            
            <!-- Detail fields -->
            <div class="detail-field">
                <label class="block text-sm text-gray-700 mb-1">Status</label>
                <input type="text" id="status" class="w-full p-2 border rounded-lg bg-gray-50" readonly>
            </div>

            <div class="detail-field">
                <label class="block text-sm text-gray-700 mb-1">Keterangan</label>
                <input type="text" id="keterangan" class="w-full p-2 border rounded-lg bg-gray-50" readonly>
            </div>

            <div class="detail-field">
                <label class="block text-sm text-gray-700 mb-1">Bukti</label>
                <input type="text" id="bukti" class="w-full p-2 border rounded-lg bg-gray-50" readonly>
            </div>

            <div class="detail-field grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm text-gray-700 mb-1">Jam Masuk</label>
                    <input type="text" id="jamMasuk" class="w-full p-2 border rounded-lg bg-gray-50 text-green-600" readonly>
                </div>
                <div>
                    <label class="block text-sm text-gray-700 mb-1">Jam Pulang</label>
                    <input type="text" id="jamPulang" class="w-full p-2 border rounded-lg bg-gray-50 text-red-600" readonly>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
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

    #notification {
        z-index: 50;
        max-width: 24rem;
        pointer-events: none;
    }

    .notification-show {
        opacity: 1 !important;
        transform: translateY(0) !important;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    // Single DOMContentLoaded event listener
    document.addEventListener('DOMContentLoaded', function() {
        // Charts initialization
        initializeCharts();
        
        // Calendar initialization
        initializeCalendar();
    });

    function initializeCharts() {
        // Donut Chart
        const donutOptions = {
            series: [
                {{ $attendanceStats['present']['percent'] }}, 
                {{ $attendanceStats['sick']['percent'] }}, 
                {{ $attendanceStats['absent']['percent'] }}
            ],
            chart: {
                type: 'donut',
                height: 130,
                sparkline: {
                    enabled: true
                }
            },
            colors: ['#22c55e', '#f59e0b', '#ef4444'],
            labels: ['Hadir', 'Izin', 'Alpha'],
            legend: {
                show: false
            },
            tooltip: {
                custom: function({series, seriesIndex, dataPointIndex}) {
                    const labels = ['Hadir', 'Izin', 'Alpha'];
                    const counts = [
                        {{ $attendanceStats['present']['count'] }},
                        {{ $attendanceStats['sick']['count'] }},
                        {{ $attendanceStats['absent']['count'] }}
                    ];
                    return `<div class="p-2">
                        <div>${labels[seriesIndex]}</div>
                        <div>${counts[seriesIndex]} presensi (${series[seriesIndex]}%)</div>
                    </div>`;
                }
            },
            plotOptions: {
                pie: {
                    donut: {
                        size: '75%'
                    }
                }
            },
            stroke: {
                width: 2
            }
        };
        new ApexCharts(document.querySelector("#attendanceChart"), donutOptions).render();

        // Line Chart
        const lineOptions = {
            series: [{
                name: 'Kehadiran',
                data: [30, 40, 35, 50, 49, 60, 45]
            }],
            chart: {
                type: 'line',
                height: 240,
                toolbar: {
                    show: false
                }
            },
            stroke: {
                curve: 'smooth',
                width: 3,
            },
            colors: ['#637F26'],
            xaxis: {
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
                labels: {
                    style: {
                        colors: '#6B7280',
                        fontSize: '12px',
                    }
                }
            },
            yaxis: {
                labels: {
                    style: {
                        colors: '#6B7280',
                        fontSize: '12px',
                    }
                }
            },
            grid: {
                borderColor: '#f1f1f1',
                strokeDashArray: 4,
                xaxis: {
                    lines: {
                        show: true
                    }
                }
            },
            markers: {
                size: 5,
                colors: ['#637F26'],
                strokeColors: '#fff',
                strokeWidth: 2
            }
        };
        new ApexCharts(document.querySelector("#attendanceLineChart"), lineOptions).render();
    }

    function initializeCalendar() {
        const monthSelect = document.getElementById('month-select');
        const yearSelect = document.getElementById('year-select');
        let selectedDate = new Date().toISOString().split('T')[0]; // Set default selected date to today
        
        if (!monthSelect || !yearSelect) return;

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
                        onclick="selectDate('${date}', this)"
                        ondblclick="showDetailModal('${date}')">
                        ${day}
                    </div>`;
            }
            
            // Next month days
            const totalDays = startingDay + daysInMonth;
            const remainingDays = 42 - totalDays;
            
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
                if (previousSelected.classList.contains('today')) {
                    previousSelected.classList.add('today');
                }
            }
            
            // Add selection to clicked date
            element.classList.add('selected-date');
            selectedDate = date;
        }

        // Add event listeners
        monthSelect.addEventListener('change', function() {
            generateCalendar(
                parseInt(yearSelect.value),
                parseInt(this.value)
            );
        });

        yearSelect.addEventListener('change', function() {
            generateCalendar(
                parseInt(this.value),
                parseInt(monthSelect.value)
            );
        });

        // Initial calendar generation
        generateCalendar(
            parseInt(yearSelect.value),
            parseInt(monthSelect.value)
        );
    }

    // Make selectDate function globally available
    window.selectDate = function(date, element) {
        const previousSelected = document.querySelector('.calendar-day.selected-date');
        if (previousSelected) {
            previousSelected.classList.remove('selected-date');
            if (previousSelected.classList.contains('today')) {
                previousSelected.classList.add('today');
            }
        }
        
        element.classList.add('selected-date');
        selectedDate = date;
    }

    function handleDownload(allStagesCompleted) {
        const notification = document.getElementById('notification');
        if (allStagesCompleted) {
            notification.innerHTML = 'Sertifikat berhasil diunduh!';
            notification.classList.add('bg-green-500', 'text-white');
        } else {
            notification.innerHTML = 'Anda belum menyelesaikan semua stase.';
            notification.classList.add('bg-red-500', 'text-white');
        }
        notification.classList.remove('opacity-0', 'translate-y-[-1rem]', 'pointer-events-none');
        setTimeout(() => {
            notification.classList.add('opacity-0', 'translate-y-[-1rem]', 'pointer-events-none');
        }, 3000);
    }

    function handleDownload(isCompleted) {
        const notification = document.getElementById('notification');
        
        if (!isCompleted) {
            // Show error notification
            notification.className = 'fixed top-4 right-4 px-6 py-4 rounded-lg shadow-lg bg-[#FF6B6B] text-white transform transition-all duration-300';
            notification.innerHTML = `
                <div class="text-center">
                    <p class="font-medium mb-1">Gagal mengunduh sertifikat!</p>
                    <p class="text-sm">Magang anda belum selesai atau hubungi admin jika ada masalah lain</p>
                </div>`;
        } else {
            // Show success notification
            notification.className = 'fixed top-4 right-4 px-6 py-4 rounded-lg shadow-lg bg-[#96D67F] text-white transform transition-all duration-300';
            notification.innerHTML = `
                <div class="text-center">
                    <p class="font-medium mb-1">SELAMAT!</p>
                    <p class="text-sm">Sertifikat Magang telah BERHASIL diunduh.<br>Silahkan periksa detail nama Anda!</p>
                </div>`;
        }

        // Show notification
        notification.style.opacity = '1';
        notification.style.transform = 'translateY(0)';
        
        // Hide notification after 3 seconds
        setTimeout(() => {
            notification.style.opacity = '0';
            notification.style.transform = 'translateY(-1rem)';
        }, 3000);
    }

    function showDetailModal(date) {
        // Format date for display
        const formattedDate = new Date(date).toLocaleDateString('id-ID', {
            weekday: 'long',
            day: 'numeric',
            month: 'long',
            year: 'numeric'
        });

        // Get presence data from server
        fetch(`/student/presence-detail/${date}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('selectedDate').textContent = formattedDate;
                
                if (data.presence) {
                    // Jika ada data presensi
                    document.getElementById('status').value = data.presence.status || '';
                    document.getElementById('keterangan').value = data.presence.keterangan || '';
                    document.getElementById('bukti').value = data.presence.bukti || '';
                    document.getElementById('jamMasuk').value = data.presence.check_in || '';
                    document.getElementById('jamPulang').value = data.presence.check_out || '';

                    // Tampilkan semua field
                    document.querySelectorAll('.detail-field').forEach(field => {
                        field.classList.remove('hidden');
                    });
                    document.getElementById('empty-state').classList.add('hidden');
                } else {
                    // Jika tidak ada data presensi
                    // Sembunyikan semua field
                    document.querySelectorAll('.detail-field').forEach(field => {
                        field.classList.add('hidden');
                    });
                    
                    // Tampilkan pesan kosong
                    document.getElementById('empty-state').classList.remove('hidden');
                }
                
                document.getElementById('detailModal').classList.remove('hidden');
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }

    function closeModal() {
        document.getElementById('detailModal').classList.add('hidden');
    }
</script>
@endpush
@endsection