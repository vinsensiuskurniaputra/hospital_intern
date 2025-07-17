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
                                <select id="month-select"
                                    class="form-select w-36 pl-4 pr-10 py-2 rounded-lg border border-gray-300 appearance-none cursor-pointer">
                                    @foreach (['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'] as $index => $month)
                                        <option value="{{ $index + 1 }}" {{ $index + 1 == date('n') ? 'selected' : '' }}>
                                            {{ $month }}</option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3">
                                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </div>

                            <div class="relative">
                                <select id="year-select"
                                    class="form-select w-28 pl-4 pr-10 py-2 rounded-lg border border-gray-300 appearance-none cursor-pointer">
                                    @for ($year = 2000; $year <= 2026; $year++)
                                        <option value="{{ $year }}" {{ $year == date('Y') ? 'selected' : '' }}>
                                            {{ $year }}</option>
                                    @endfor
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3">
                                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Calendar Grid -->
                        <div class="mb-6">
                            <div class="grid grid-cols-7 mb-2">
                                @foreach (['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'] as $day)
                                    <div class="text-center text-sm text-gray-500">{{ $day }}</div>
                                @endforeach
                            </div>

                            <div id="calendar-grid" class="grid grid-cols-7 gap-1">
                                <!-- Calendar days will be inserted here by JavaScript -->
                            </div>
                        </div>

                        <!-- Cancel/Done Buttons -->
                        <div class="flex justify-end gap-2">
                            <button
                                class="calendar-cancel px-4 py-2 rounded-lg border border-gray-300 hover:bg-gray-50 transition-colors">Batal</button>
                            <button
                                class="px-4 py-2 rounded-lg bg-[#637F26] text-white hover:bg-[#4B601C] transition-colors calendar-done">Pilih
                                Tanggal</button>
                        </div>
                    </div>

                    <!-- Right Side - Schedule Cards -->
                    <div class="w-[calc(100%-400px-24px)] flex items-start">
                        <div id="today-schedules" class="w-full mt-14 h-[calc(100%-8rem)]">
                            @forelse($todaySchedules as $schedule)
                                <div class="bg-[#F5F7F0] rounded-lg p-3 mb-2">
                                    <h6 class="text-base font-medium mb-1">
                                        {{ $schedule->internshipClass->name ?? 'N/A' }}
                                        @if ($schedule->internshipClass && $schedule->internshipClass->classYear)
                                            ({{ $schedule->internshipClass->classYear->class_year }})
                                        @endif
                                    </h6>
                                    <div class="text-sm text-gray-600 mb-1">{{ $schedule->stase->name ?? 'N/A' }}</div>
                                    <div class="text-sm text-gray-600">{{ $schedule->stase->departement->name ?? 'N/A' }}
                                    </div>
                                </div>
                            @empty
                                <div class="bg-gray-50 rounded-lg h-[280px] flex items-center justify-center">
                                    <div class="text-center">
                                        <div class="text-gray-400 mb-2">
                                            <svg class="w-8 h-8 mx-auto" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
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

        <!-- Tabel Jadwal Section with DataTables -->
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
                    <button class="px-3 py-1 text-sm rounded-full bg-gray-100 text-gray-600" id="pill-current-month">Bulan
                        Ini</button>
                    <button class="px-3 py-1 text-sm rounded-full bg-gray-100 text-gray-600" id="pill-current-week">Minggu
                        Ini</button>
                    <button class="px-3 py-1 text-sm rounded-full bg-gray-100 text-gray-600" id="pill-next-week">Minggu
                        Depan</button>
                    <button class="px-3 py-1 text-sm rounded-full bg-gray-100 text-gray-600" id="pill-next-month">Bulan
                        Depan</button>
                </div>

                <!-- DataTable -->
                <div class="overflow-x-auto">
                    <table id="schedules-table" class="w-full border-collapse">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-8 py-4 text-center text-base font-bold text-gray-700 border border-gray-200">
                                    Tanggal</th>
                                <th class="px-8 py-4 text-center text-base font-bold text-gray-700 border border-gray-200">
                                    Stase</th>
                                <th class="px-8 py-4 text-center text-base font-bold text-gray-700 border border-gray-200">
                                    Kelas</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>

                <!-- Print Button -->
                <div class="flex justify-end mt-4">
                    <button onclick="printSchedule()"
                        class="px-4 py-2 rounded-lg bg-[#637F26] text-white hover:bg-[#4B601C] transition-colors">
                        Cetak Jadwal
                    </button>
                </div>
            </div>
        </div>

        <!-- Detail Kelas Section -->
        <div class="bg-white rounded-lg shadow-sm mb-6">
            <div class="p-6">
                <!-- Header with filters -->
                <div class="flex justify-between items-center mb-4">
                    <h5 class="text-lg font-medium">Detail Kelas</h5>

                    <!-- Filters -->
                    <div class="flex gap-4">
                        <!-- Stase Filter -->
                        <select id="stase-filter"
                            class="form-select w-48 pl-4 pr-10 py-2 rounded-lg border border-gray-300 appearance-none cursor-pointer"
                            onchange="onStaseChange()">
                            <option value="">Pilih Stase</option>
                            @foreach ($stases as $stase)
                                <option value="{{ $stase->id }}">{{ $stase->name }}</option>
                            @endforeach
                        </select>

                        <!-- Kelas Filter -->
                        <select id="class-filter"
                            class="form-select w-48 pl-4 pr-10 py-2 rounded-lg border border-gray-300 appearance-none cursor-pointer hidden"
                            onchange="updateFilters()">
                            <option value="">Pilih Kelas</option>
                        </select>
                    </div>
                </div>

                <!-- Default Container -->
                <div class="bg-[#F5F7F0] rounded-lg" id="class-container">
                    <div class="p-6 text-center text-gray-500">
                        Pilih Stase dan Kelas untuk melihat daftar mahasiswa
                    </div>
                </div>
            </div>
        </div>
    @endsection

    <!-- Student Detail Modal (Hidden by default) -->
    <div id="student-modal" class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50 hidden">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl mx-4">
            <div class="flex justify-between items-center border-b p-4">
                <h3 class="text-lg font-medium">Detail Mahasiswa</h3>
                <button onclick="closeStudentModal()" class="text-gray-500 hover:text-gray-700 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div id="student-modal-content">
                <!-- Content will be loaded dynamically -->
            </div>
            <div class="border-t p-4 flex justify-end">
                <button onclick="closeStudentModal()"
                    class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 focus:outline-none">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    @push('scripts')
        <!-- DataTables CSS and JS -->
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

        <script>
            let schedulesTable;
            let studentsTable; // Tambahan untuk DataTable mahasiswa

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
                    <h6 class="text-base font-medium mb-1">${schedule.class_name}${schedule.academic_year ? ` (${schedule.academic_year})` : ''}</h6>
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
                // Initialize DataTable
                schedulesTable = $('#schedules-table').DataTable({
                    processing: true,
                    serverSide: false,
                    dom: 'frtip',
                    language: {
                        url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
                    },
                    columnDefs: [{
                        className: "text-center",
                        targets: "_all"
                    }],
                    pageLength: 10,
                    responsive: true
                });

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

                // Set default dates (current month) - PERBAIKAN DI SINI
                const firstDayOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
                const lastDayOfMonth = new Date(today.getFullYear(), today.getMonth() + 1, 0);

                document.getElementById('start-date').value = firstDayOfMonth.toISOString().split('T')[0];
                document.getElementById('end-date').value = lastDayOfMonth.toISOString().split('T')[0];

                // Load initial DataTable data - PASTIKAN INI DIPANGGIL
                setTimeout(() => {
                    applyFilter();
                }, 100);
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

                if (!startDate || !endDate) {
                    alert('Pilih rentang tanggal terlebih dahulu');
                    return;
                }

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
                    <img src="/images/logorevisi.png" height="50" style="visibility: hidden;">
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
                    </tr>
                </thead>
                <tbody>
    `;

                // Get DataTable data for printing
                const tableData = schedulesTable.data().toArray();
                tableData.forEach(row => {
                    printContent += `
            <tr>
                <td>${row[0]}</td>
                <td>${row[1]}</td>
                <td>${row[2]}</td>
            </tr>
        `;
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

                // Debug logging
                console.log('Apply Filter - Start Date:', startDate, 'End Date:', endDate);

                if (!startDate || !endDate) {
                    console.error('Date range not set properly');
                    // Set default dates if not available
                    const today = new Date();
                    const firstDay = new Date(today.getFullYear(), today.getMonth(), 1).toISOString().split('T')[0];
                    const lastDay = new Date(today.getFullYear(), today.getMonth() + 1, 0).toISOString().split('T')[0];

                    document.getElementById('start-date').value = firstDay;
                    document.getElementById('end-date').value = lastDay;

                    // Retry with new dates
                    setTimeout(() => applyFilter(), 100);
                    return;
                }

                if (new Date(startDate) > new Date(endDate)) {
                    alert('Tanggal mulai tidak boleh lebih besar dari tanggal selesai');
                    return;
                }

                // Clear DataTable and show loading
                schedulesTable.clear().draw();

                console.log('Fetching data from:', `/responsible/schedule/filter?start_date=${startDate}&end_date=${endDate}`);

                fetch(`/responsible/schedule/filter?start_date=${startDate}&end_date=${endDate}`)
                    .then(response => {
                        console.log('Response status:', response.status);
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Response data:', data);

                        if (!data.success) {
                            throw new Error(data.message || 'Terjadi kesalahan');
                        }

                        // Clear previous data
                        schedulesTable.clear();

                        if (data.schedules && data.schedules.length > 0) {
                            console.log('Adding schedules to table:', data.schedules.length);
                            // Add new data to DataTable
                            data.schedules.forEach(schedule => {
                                const dateRange =
                                    `${formatDate(schedule.start_date)} - ${formatDate(schedule.end_date)}`;
                                const staseName = schedule.stase?.name || 'N/A';

                                // Format class name with academic year - PERBAIKAN DI SINI
                                let className = schedule.internship_class?.name || 'N/A';
                                if (schedule.internship_class?.class_year) {
                                    className += ` (${schedule.internship_class.class_year})`;
                                }

                                schedulesTable.row.add([
                                    dateRange,
                                    staseName,
                                    className
                                ]);
                            });
                        } else {
                            console.log('No schedules found for the date range');
                            // Add a "no data" row
                            schedulesTable.row.add([
                                'Tidak ada data',
                                'untuk rentang tanggal ini',
                                ''
                            ]);
                        }

                        // Redraw table
                        schedulesTable.draw();
                    })
                    .catch(error => {
                        console.error('Error fetching schedules:', error);
                        // Show error in DataTable
                        schedulesTable.clear();
                        schedulesTable.row.add([
                            'Error loading data',
                            error.message || 'Terjadi kesalahan saat memuat data',
                            ''
                        ]);
                        schedulesTable.draw();
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

                        switch (this.textContent.trim()) {
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
                                const nextMonth = new Date(today.getFullYear(), today.getMonth() + 1,
                                1);
                                const lastDayOfNextMonth = new Date(today.getFullYear(), today
                                .getMonth() + 2, 0);

                                startDate.value = nextMonth.toISOString().split('T')[0];
                                endDate.value = lastDayOfNextMonth.toISOString().split('T')[0];
                                break;
                        }

                        // Trigger filter after setting dates
                        applyFilter();
                    });
                });
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

            function showStudentDetail(studentId) {
                // Show loading state in modal
                document.getElementById('student-modal').classList.remove('hidden');
                document.getElementById('student-modal-content').innerHTML = `
        <div class="flex items-center justify-center p-6">
            <svg class="animate-spin h-5 w-5 mr-3 text-gray-500" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span>Memuat data mahasiswa...</span>
        </div>
    `;

                // Fetch student details
                fetch(`/responsible/schedule/student-detail?student_id=${studentId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (!data.success) {
                            throw new Error(data.message || 'Terjadi kesalahan');
                        }

                        const student = data.student;

                        // Populate modal with student details - fixed telp property reference
                        document.getElementById('student-modal-content').innerHTML = `
                <div class="p-6">
                    <div class="flex items-start mb-6">
                        <div class="mr-4">
                            <img src="${student.user?.photo_profile_url || '/images/default-avatar.png'}" 
                                alt="Profile" 
                                class="w-20 h-20 rounded-full object-cover border border-gray-200">
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-gray-800">${student.user?.name || 'N/A'}</h3>
                            <p class="text-gray-600">${student.nim || 'N/A'}</p>
                            <p class="text-gray-600">${student.study_program?.name || 'N/A'} • ${student.study_program?.campus?.name || 'N/A'}</p>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 p-4 rounded-lg mb-4">
                        <h4 class="font-medium text-gray-700 mb-2">Informasi Akademik</h4>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-500">Kelas</p>
                                <p class="text-gray-700">${student.internship_class?.name || 'N/A'}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Tahun Akademik</p>
                                <p class="text-gray-700">${student.internship_class?.class_year?.class_year || 'N/A'}</p>
                            </div>
                            <div class="col-span-2">
                                <p class="text-sm text-gray-500 mb-2">Status</p>
                                <span class="inline-block px-3 py-1.5 text-xs rounded-full ${student.status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                                    ${student.status === 'active' ? 'Aktif' : 'Tidak Aktif'}
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="font-medium text-gray-700 mb-2">Informasi Kontak</h4>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-500">Email</p>
                                <p class="text-gray-700">${student.user?.email || 'N/A'}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">No. Telepon</p>
                                <p class="text-gray-700">${student.telp || 'N/A'}</p>
                            </div>
                        </div>
                    </div>
                </div>
            `;

                        // For debugging
                        console.log("Student data:", student);
                    })
                    .catch(error => {
                        document.getElementById('student-modal-content').innerHTML = `
                <div class="p-6 text-center text-red-500">
                    ${error.message || 'Terjadi kesalahan saat memuat data mahasiswa'}</div>
            `;
                    });
            }

            function closeStudentModal() {
                document.getElementById('student-modal').classList.add('hidden');
            }

            // Update loadClassDetails function untuk membuat tabel responsive
            function loadClassDetails(staseId, classId) {
                if (!staseId || !classId) return;

                const container = document.getElementById('class-container');
                container.innerHTML = `
        <div class="flex items-center justify-center p-6">
            <svg class="animate-spin h-5 w-5 mr-3 text-gray-500" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span>Memuat data...</span>
        </div>
    `;

                fetch(`/responsible/schedule/class-details?stase_id=${staseId}&class_id=${classId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (!data.success) {
                            throw new Error(data.message || 'Terjadi kesalahan');
                        }

                        // Create responsive DataTable structure
                        container.innerHTML = `
                <div class="p-6 bg-white rounded-lg">
                    <div class="flex justify-between items-center mb-4">
                        <h6 class="text-base font-medium">Daftar Mahasiswa</h6>
                        <span class="text-sm text-gray-500">${data.students.length} mahasiswa</span>
                    </div>
                    
                    <!-- Responsive table container -->
                    <div class="w-full">
                        <table id="students-table" class="w-full table-auto">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-4 text-center text-base font-bold text-gray-700 border border-gray-200 w-1/4">Nama</th>
                                    <th class="px-4 py-4 text-center text-base font-bold text-gray-700 border border-gray-200 w-1/4">NIM</th>
                                    <th class="px-4 py-4 text-center text-base font-bold text-gray-700 border border-gray-200 w-1/4">Program Studi</th>
                                    <th class="px-4 py-4 text-center text-base font-bold text-gray-700 border border-gray-200 w-1/4">Kampus</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            `;

                        // Destroy existing DataTable if it exists
                        if (studentsTable) {
                            studentsTable.destroy();
                        }

                        // Initialize responsive DataTable for students
                        studentsTable = $('#students-table').DataTable({
                            processing: true,
                            serverSide: false,
                            dom: 'lfrtip',
                            language: {
                                url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/id.json',
                                search: "Cari:",
                                lengthMenu: "Tampilkan _MENU_ data per halaman",
                                paginate: {
                                    first: "Pertama",
                                    last: "Terakhir",
                                    next: "Selanjutnya",
                                    previous: "Sebelumnya"
                                },
                                emptyTable: "Tidak ada data mahasiswa",
                                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ mahasiswa",
                                infoEmpty: "Menampilkan 0 sampai 0 dari 0 mahasiswa",
                                infoFiltered: "(disaring dari _MAX_ total mahasiswa)",
                                zeroRecords: "Tidak ditemukan mahasiswa yang sesuai"
                            },
                            columnDefs: [{
                                    className: "text-left px-2",
                                    targets: 0,
                                    orderable: true,
                                    width: "25%",
                                    // Tambahkan type untuk memastikan pengurutan string yang benar
                                    type: "string"
                                },
                                {
                                    className: "text-center px-2",
                                    targets: [1, 2, 3],
                                    orderable: true,
                                    width: "25%"
                                }
                            ],
                            lengthMenu: [
                                [5, 10, 25, 50, 100, -1],
                                [5, 10, 25, 50, 100, "Semua"]
                            ],
                            pageLength: 10,
                            responsive: {
                                details: {
                                    type: 'column',
                                    target: 0
                                }
                            },
                            ordering: true,
                            order: [
                                [0, 'asc']
                            ], // Urutkan berdasarkan kolom nama secara ascending
                            autoWidth: false,
                            scrollX: false,
                            data: [],
                            columns: [{
                                    data: null,
                                    className: 'text-left',
                                    render: function(data, type, row) {
                                        // Untuk keperluan sorting dan searching, kembalikan nama saja TANPA format angka
                                        if (type === 'sort' || type === 'type') {
                                            const name = row.user?.name || 'N/A';
                                            // Ekstrak nama tanpa angka untuk sorting yang benar
                                            // Contoh: "Mahasiswa Stase Neurologi 1" -> "Mahasiswa Stase Neurologi"
                                            return name.replace(/\s+\d+$/, '').trim() + ' ' + (name.match(
                                                /\d+$/) || ['0'])[0].padStart(3, '0');
                                        }

                                        // Untuk display, kembalikan HTML dengan foto
                                        const photoUrl = row.user?.photo_profile_url ||
                                            '/images/default-avatar.png';
                                        const name = row.user?.name || 'N/A';
                                        return `
                                <div class="flex items-center min-w-0">
                                    <img 
                                        src="${photoUrl}" 
                                        alt="Profile" 
                                        class="w-8 h-8 rounded-full object-cover border border-gray-200 mr-2 flex-shrink-0"
                                    >
                                    <span class="truncate">${name}</span>
                                </div>
                            `;
                                    },
                                    // Tambahkan untuk memastikan pengurutan berdasarkan nama
                                    type: "string"
                                },
                                {
                                    data: 'nim',
                                    className: 'text-center',
                                    render: function(data, type, row) {
                                        return `<span class="truncate">${data || 'N/A'}</span>`;
                                    }
                                },
                                {
                                    data: null,
                                    className: 'text-center',
                                    render: function(data, type, row) {
                                        // Untuk sorting, kembalikan nama program studi saja
                                        if (type === 'sort' || type === 'type') {
                                            return row.study_program?.name || 'N/A';
                                        }
                                        return `<span class="truncate">${row.study_program?.name || 'N/A'}</span>`;
                                    }
                                },
                                {
                                    data: null,
                                    className: 'text-center',
                                    render: function(data, type, row) {
                                        let campusName = 'N/A';

                                        if (row.study_program && row.study_program.campus) {
                                            campusName = row.study_program.campus.name;
                                        } else if (row.study_program && row.study_program.campus_id) {
                                            const campusMap = {
                                                1: 'Politeknik Negeri Semarang',
                                                2: 'Universitas Diponegoro',
                                                3: 'Universitas Negeri Semarang',
                                            };
                                            campusName = campusMap[row.study_program.campus_id] || 'N/A';
                                        }

                                        // Untuk sorting, kembalikan nama kampus saja
                                        if (type === 'sort' || type === 'type') {
                                            return campusName;
                                        }

                                        return `<span class="truncate">${campusName}</span>`;
                                    }
                                }
                            ],
                            drawCallback: function(settings) {
                                // Add click handlers to rows after each draw
                                $('#students-table tbody tr').off('click').on('click', function() {
                                    const data = studentsTable.row(this).data();
                                    if (data && data.id) {
                                        showStudentDetail(data.id);
                                    }
                                });

                                // Add hover effect
                                $('#students-table tbody tr').hover(
                                    function() {
                                        $(this).addClass('bg-gray-50 cursor-pointer');
                                    },
                                    function() {
                                        $(this).removeClass('bg-gray-50');
                                    }
                                );
                            }
                        });

                        // Add data to DataTable
                        studentsTable.clear();
                        studentsTable.rows.add(data.students);
                        studentsTable.draw();

                    })
                    .catch(error => {
                        container.innerHTML = `
                <div class="p-6 text-center text-red-500">
                    ${error.message || 'Terjadi kesalahan saat memuat data'}
                </div>
            `;
                    });
            }

            function updateFilters() {
                const staseId = document.getElementById('stase-filter').value;
                const classId = document.getElementById('class-filter').value;

                if (staseId && classId) {
                    loadClassDetails(staseId, classId);
                } else {
                    document.getElementById('class-container').innerHTML = `
            <div class="p-6 text-center text-gray-500">
                ${!staseId ? 'Pilih Stase terlebih dahulu' : 'Pilih Kelas untuk melihat daftar mahasiswa'}
            </div>
        `;
                }
            }

            function onStaseChange() {
                const staseId = document.getElementById('stase-filter').value;
                const classFilter = document.getElementById('class-filter');

                // Reset class filter and hide it if no stase is selected
                classFilter.innerHTML = '<option value="">Pilih Kelas</option>';

                if (!staseId) {
                    classFilter.classList.add('hidden');
                    document.getElementById('class-container').innerHTML = `
            <div class="p-6 text-center text-gray-500">
                Pilih Stase dan Kelas untuk melihat daftar mahasiswa
            </div>
        `;
                    return;
                }

                // Show loading state in class dropdown
                classFilter.innerHTML = '<option value="">Loading...</option>';
                classFilter.classList.remove('hidden');

                // Fetch classes for selected stase
                fetch(`/responsible/schedule/get-classes?stase_id=${staseId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (!data.success) {
                            throw new Error(data.message || 'Terjadi kesalahan');
                        }

                        // Populate class dropdown with fetched classes
                        classFilter.innerHTML = '<option value="">Pilih Kelas</option>';

                        if (data.classes.length === 0) {
                            classFilter.innerHTML += '<option value="" disabled>Tidak ada kelas di stase ini</option>';
                            return;
                        }

                        data.classes.forEach(cls => {
                            // Include the academic year in the format requested
                            const academicYear = cls.class_year?.class_year || '';
                            const displayName = academicYear ? `${cls.name} (${academicYear})` : cls.name;

                            classFilter.innerHTML += `<option value="${cls.id}">${displayName}</option>`;
                        });
                    })
                    .catch(error => {
                        classFilter.innerHTML = '<option value="">Error loading classes</option>';
                        console.error('Error:', error);
                    });
            }
        </script>
    @endpush

    @push('styles')
        <style>
            /* DataTables wrapper styling to match original design */
            .dataTables_wrapper {
                font-family: inherit;
            }

            /* HAPUS ATAU UBAH STYLING YANG MENYEMBUNYIKAN LENGTH MENU */
            /* .dataTables_length {
            display: none;  <-- HAPUS BARIS INI
        } */

            /* Styling untuk length menu di tabel mahasiswa - PERBAIKI POSITIONING ARROW */
            #students-table_wrapper .dataTables_length select {
                border: 1px solid #d1d5db;
                border-radius: 0.375rem;
                padding: 0.375rem 2rem 0.375rem 0.75rem;
                /* Tambah padding kanan untuk arrow */
                font-size: 14px;
                background: white;
                color: #374151;
                min-width: 80px;
                /* Hilangkan arrow default browser */
                -webkit-appearance: none;
                -moz-appearance: none;
                appearance: none;
                /* Tambah custom arrow dengan positioning yang lebih baik */
                background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
                background-position: right 0.75rem center;
                /* Geser arrow lebih ke kiri */
                background-repeat: no-repeat;
                background-size: 1.25em 1.25em;
                /* Ukuran arrow sedikit lebih kecil */
            }

            #students-table_wrapper .dataTables_length select:focus {
                outline: none;
                border-color: #637F26;
                box-shadow: 0 0 0 1px rgba(99, 127, 38, 0.2);
            }

            #students-table_wrapper .dataTables_length {
                float: left;
                margin-bottom: 1rem;
                margin-left: 1rem;
                /* Geser sedikit dari tepi kiri */
                display: block;
            }

            #students-table_wrapper .dataTables_length label {
                font-weight: normal;
                color: #6b7280;
                font-size: 14px;
                display: flex;
                align-items: center;
                gap: 0.5rem;
            }

            /* Info styling untuk tabel mahasiswa */
            #students-table_wrapper .dataTables_info {
                float: left;
                margin-top: 0.75rem;
                color: #6b7280;
                font-size: 14px;
                display: block;
                /* Pastikan ditampilkan */
            }

            /* Search box positioning untuk tabel mahasiswa */
            #students-table_wrapper .dataTables_filter {
                float: right;
                margin-bottom: 1rem;
            }

            #students-table_wrapper .dataTables_filter label {
                font-weight: normal;
                color: #6b7280;
                font-size: 14px;
            }

            #students-table_wrapper .dataTables_filter input {
                border: 1px solid #d1d5db;
                border-radius: 0.375rem;
                padding: 0.5rem 0.75rem;
                margin-left: 0.5rem;
                font-size: 14px;
                width: 200px;
            }

            #students-table_wrapper .dataTables_filter input:focus {
                outline: none;
                border-color: #637F26;
                box-shadow: 0 0 0 1px rgba(99, 127, 38, 0.2);
            }

            /* Untuk tabel jadwal tetap sembunyikan length menu */
            #schedules-table_wrapper .dataTables_length {
                display: none;
            }

            #schedules-table_wrapper .dataTables_info {
                display: none;
            }

            /* Custom search styling untuk tabel jadwal */
            #schedules-table_wrapper .dataTables_filter {
                float: right;
                margin-bottom: 1rem;
            }

            #schedules-table_wrapper .dataTables_filter label {
                font-weight: normal;
                color: #6b7280;
                font-size: 14px;
            }

            #schedules-table_wrapper .dataTables_filter input {
                border: 1px solid #d1d5db;
                border-radius: 0.375rem;
                padding: 0.5rem 0.75rem;
                margin-left: 0.5rem;
                font-size: 14px;
                width: 200px;
            }

            #schedules-table_wrapper .dataTables_filter input:focus {
                outline: none;
                border-color: #637F26;
                box-shadow: 0 0 0 1px rgba(99, 127, 38, 0.2);
            }

            /* Table styling to match original design exactly */
            table.dataTable {
                width: 100%;
                border-collapse: collapse;
                margin-top: 0;
                border: none;
            }

            table.dataTable thead th {
                background-color: #f9fafb;
                padding: 1rem 2rem;
                text-align: center;
                font-size: 1rem;
                font-weight: 700;
                color: #374151;
                border: 1px solid #e5e7eb;
            }

            table.dataTable tbody td {
                padding: 1rem 2rem;
                font-size: 0.875rem;
                color: #374151;
                border: 1px solid #e5e7eb;
                background-color: white;
            }

            table.dataTable tbody tr:hover td {
                background-color: #f9fafb !important;
                cursor: pointer;
            }

            /* Responsive table styling untuk tabel mahasiswa */
            #students-table {
                width: 100% !important;
                table-layout: fixed;
                border-collapse: collapse;
            }

            #students-table thead th {
                background-color: #f9fafb;
                padding: 0.75rem 0.5rem;
                text-align: center;
                font-size: 0.875rem;
                font-weight: 700;
                color: #374151;
                border: 1px solid #e5e7eb;
                word-wrap: break-word;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            #students-table tbody td {
                padding: 0.75rem 0.5rem;
                font-size: 0.875rem;
                color: #374151;
                border: 1px solid #e5e7eb;
                background-color: white;
                word-wrap: break-word;
                overflow: hidden;
                text-overflow: ellipsis;
                max-width: 0;
            }

            #students-table tbody tr:hover td {
                background-color: #f9fafb !important;
                cursor: pointer;
            }

            /* Remove default DataTables sorting icons */
            table.dataTable thead .sorting:before,
            table.dataTable thead .sorting:after,
            table.dataTable thead .sorting_asc:before,
            table.dataTable thead .sorting_asc:after,
            table.dataTable thead .sorting_desc:before,
            table.dataTable thead .sorting_desc:after {
                display: none;
            }

            /* Custom sorting indicators to match the original design */
            table.dataTable thead th.sorting {
                position: relative;
                cursor: pointer;
            }

            table.dataTable thead th.sorting:after {
                content: "⇅";
                position: absolute;
                right: 8px;
                top: 50%;
                transform: translateY(-50%);
                color: #9ca3af;
                font-size: 12px;
                display: inline;
            }

            table.dataTable thead th.sorting_asc:after {
                content: "↑";
                color: #637F26;
            }

            table.dataTable thead th.sorting_desc:after {
                content: "↓";
                color: #637F26;
            }

            /* Pagination styling untuk students table */
            #students-table_wrapper .dataTables_paginate {
                float: right;
                margin-top: 1rem;
            }

            #students-table_wrapper .dataTables_paginate .paginate_button {
                display: inline-block;
                padding: 0.5rem 0.75rem;
                margin: 0 0.125rem;
                border: 1px solid #d1d5db;
                border-radius: 0.375rem;
                background: white;
                color: #374151;
                text-decoration: none;
                font-size: 14px;
                cursor: pointer;
            }

            #students-table_wrapper .dataTables_paginate .paginate_button:hover {
                background: #f3f4f6;
                border-color: #9ca3af;
                text-decoration: none;
            }

            #students-table_wrapper .dataTables_paginate .paginate_button.current {
                background: #637F26;
                border-color: #637F26;
                color: white;
            }

            #students-table_wrapper .dataTables_paginate .paginate_button.current:hover {
                background: #4B601C;
                border-color: #4B601C;
                color: white;
            }

            #students-table_wrapper .dataTables_paginate .paginate_button.disabled {
                color: #9ca3af;
                cursor: not-allowed;
                background: #f9fafb;
            }

            #students-table_wrapper .dataTables_paginate .paginate_button.disabled:hover {
                background: #f9fafb;
                border-color: #d1d5db;
                color: #9ca3af;
            }

            /* Pagination styling untuk schedules table */
            .dataTables_paginate {
                float: right;
                margin-top: 1rem;
            }

            .dataTables_paginate .paginate_button {
                display: inline-block;
                padding: 0.5rem 0.75rem;
                margin: 0 0.125rem;
                border: 1px solid #d1d5db;
                border-radius: 0.375rem;
                background: white;
                color: #374151;
                text-decoration: none;
                font-size: 14px;
                cursor: pointer;
            }

            .dataTables_paginate .paginate_button:hover {
                background: #f3f4f6;
                border-color: #9ca3af;
                text-decoration: none;
            }

            .dataTables_paginate .paginate_button.current {
                background: #637F26;
                border-color: #637F26;
                color: white;
            }

            .dataTables_paginate .paginate_button.current:hover {
                background: #4B601C;
                border-color: #4B601C;
                color: white;
            }

            .dataTables_paginate .paginate_button.disabled {
                color: #9ca3af;
                cursor: not-allowed;
                background: #f9fafb;
            }

            .dataTables_paginate .paginate_button.disabled:hover {
                background: #f9fafb;
                border-color: #d1d5db;
                color: #9ca3af;
            }

            /* Mobile responsive adjustments */
            @media (max-width: 768px) {

                #students-table_wrapper .dataTables_length,
                #students-table_wrapper .dataTables_filter {
                    float: none;
                    text-align: center;
                    margin: 0.5rem 0;
                }

                #students-table_wrapper .dataTables_info,
                #students-table_wrapper .dataTables_paginate {
                    float: none;
                    text-align: center;
                    margin: 0.5rem 0;
                }

                #students-table thead th,
                #students-table tbody td {
                    padding: 0.5rem 0.25rem;
                    font-size: 0.75rem;
                }

                #students-table .flex img {
                    width: 1.5rem;
                    height: 1.5rem;
                }
            }

            /* Truncate text in cells */
            .truncate {
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
                max-width: 100%;
                display: inline-block;
            }

            /* Ensure flex items don't overflow */
            .min-w-0 {
                min-width: 0;
            }

            /* Clear floats */
            .dataTables_wrapper:after {
                content: "";
                display: table;
                clear: both;
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

            #calendar-grid {
                display: grid;
                grid-template-columns: repeat(7, 1fr);
                gap: 1px;
                background-color: #fff;
                padding: 4px;
            }

            /* Student profile image styles */
            .student-profile-img {
                object-fit: cover;
                transition: transform 0.2s;
            }

            .student-profile-img:hover {
                transform: scale(1.05);
            }

            /* List item hover effect */
            .student-list-item {
                transition: all 0.2s ease;
            }

            .student-list-item:hover {
                background-color: #F8FAF5;
            }

            /* Styling untuk dropdown stase dan kelas - KONSISTEN DENGAN STUDENTS TABLE */
            #stase-filter,
            #class-filter {
                border: 1px solid #d1d5db;
                border-radius: 0.375rem;
                padding: 0.375rem 2rem 0.375rem 0.75rem;
                /* Tambah padding kanan untuk arrow */
                font-size: 14px;
                background: white;
                color: #374151;
                min-width: 192px;
                /* w-48 = 12rem = 192px */
                /* Hilangkan arrow default browser */
                -webkit-appearance: none;
                -moz-appearance: none;
                appearance: none;
                /* Tambah custom arrow dengan positioning yang sama */
                background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
                background-position: right 0.75rem center;
                /* Geser arrow 0.75rem dari kanan */
                background-repeat: no-repeat;
                background-size: 1.25em 1.25em;
                /* Ukuran arrow yang sama */
                cursor: pointer;
            }

            #stase-filter:focus,
            #class-filter:focus {
                outline: none;
                border-color: #637F26;
                box-shadow: 0 0 0 1px rgba(99, 127, 38, 0.2);
            }

            #stase-filter:hover,
            #class-filter:hover {
                border-color: #9ca3af;
            }

            /* Styling untuk month dan year select di kalender - KONSISTEN */
            #month-select,
            #year-select {
                border: 1px solid #d1d5db;
                border-radius: 0.375rem;
                padding: 0.375rem 2rem 0.375rem 0.75rem;
                /* Tambah padding kanan untuk arrow */
                font-size: 14px;
                background: white;
                color: #374151;
                cursor: pointer;
                /* Hilangkan arrow default browser */
                -webkit-appearance: none;
                -moz-appearance: none;
                appearance: none;
                /* Tambah custom arrow dengan positioning yang sama */
                background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
                background-position: right 0.75rem center;
                /* Geser arrow 0.75rem dari kanan */
                background-repeat: no-repeat;
                background-size: 1.25em 1.25em;
                /* Ukuran arrow yang sama */
            }

            #month-select:focus,
            #year-select:focus {
                outline: none;
                border-color: #637F26;
                box-shadow: 0 0 0 1px rgba(99, 127, 38, 0.2);
            }

            #month-select:hover,
            #year-select:hover {
                border-color: #9ca3af;
            }

            /* Hapus styling lama yang ada di template untuk dropdown kalender */
            .form-select {
                /* Override existing form-select styles */
                border: 1px solid #d1d5db !important;
                border-radius: 0.375rem !important;
                padding: 0.375rem 2rem 0.375rem 0.75rem !important;
                font-size: 14px !important;
                background: white !important;
                color: #374151 !important;
                cursor: pointer !important;
                -webkit-appearance: none !important;
                -moz-appearance: none !important;
                appearance: none !important;
                background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e") !important;
                background-position: right 0.75rem center !important;
                background-repeat: no-repeat !important;
                background-size: 1.25em 1.25em !important;
            }

            .form-select:focus {
                outline: none !important;
                border-color: #637F26 !important;
                box-shadow: 0 0 0 1px rgba(99, 127, 38, 0.2) !important;
            }

            .form-select:hover {
                border-color: #9ca3af !important;
            }
        </style>
    @endpush
