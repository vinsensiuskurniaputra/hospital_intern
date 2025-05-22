@extends('layouts.auth')

@section('title', 'Jadwal Mahasiswa')

@section('content')
<div class="p-4">
    <h5 class="text-xl font-semibold mb-4">Jadwal Magang</h5>

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
                            <select id="monthSelect" class="form-select w-36 pl-4 pr-10 py-2 rounded-lg border border-gray-300 appearance-none cursor-pointer">
                                @foreach(['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'] as $index => $month)
                                    <option value="{{ $index }}" {{ $index == date('n') - 1 ? 'selected' : '' }}>{{ $month }}</option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3">
                                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </div>

                        <div class="relative">
                            <select id="yearSelect" class="form-select w-28 pl-4 pr-10 py-2 rounded-lg border border-gray-300 appearance-none cursor-pointer">
                                @for ($year = date('Y') - 2; $year <= date('Y') + 2; $year++)
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

                        <div id="calendarDays" class="grid grid-cols-7 gap-1">
                            <!-- Calendar days will be rendered here -->
                        </div>
                    </div>

                    <!-- Cancel/Done Buttons -->
                    <div class="flex justify-end gap-2">
                        <button class="calendar-cancel px-4 py-2 rounded-lg border border-gray-300 hover:bg-gray-50 transition-colors">Batal</button>
                        <button class="calendar-done px-4 py-2 rounded-lg bg-[#637F26] text-white hover:bg-[#4B601C] transition-colors opacity-50" disabled>Pilih Tanggal</button>
                    </div>
                </div>

                <!-- Right Side - Schedule Cards -->
                <div class="flex-1">
                    <div id="scheduleContainer" class="space-y-2">
                        <!-- Schedule items will be rendered here -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Jadwal Section -->
    <div class="bg-white rounded-lg p-4">
        <h6 class="text-lg font-semibold mb-4">Tabel Jadwal</h6>

        <!-- Update the filter buttons HTML -->
        <div class="flex gap-2 mb-4">
            <button class="schedule-filter px-4 py-2 rounded-lg border border-gray-300 hover:bg-[#637F26] hover:text-white transition-colors" data-filter="this-month">Bulan Ini</button>
            <button class="schedule-filter px-4 py-2 rounded-lg border border-gray-300 hover:bg-[#637F26] hover:text-white transition-colors" data-filter="this-week">Minggu Ini</button>
            <button class="schedule-filter px-4 py-2 rounded-lg border border-gray-300 hover:bg-[#637F26] hover:text-white transition-colors" data-filter="next-week">Minggu Depan</button>
            <button class="schedule-filter px-4 py-2 rounded-lg border border-gray-300 hover:bg-[#637F26] hover:text-white transition-colors" data-filter="next-month">Bulan Depan</button>
        </div>

        <div class="overflow-x-auto mb-3">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-600">Rentang Tanggal</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-600">Stase</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-600">Kelas</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-600">Nama Penanggung Jawab</th>
                    </tr>
                </thead>
                <tbody id="scheduleTableBody" class="divide-y divide-gray-200">
                    @foreach ($schedules as $schedule)
                        <tr class="hover:bg-gray-50">
                            <td class="py-3 px-4 text-sm text-gray-600">
                                {{ \Carbon\Carbon::parse($schedule->start_date)->locale('id')->translatedFormat('j F Y') }} -
                                {{ \Carbon\Carbon::parse($schedule->end_date)->locale('id')->translatedFormat('j F Y') }}
                            </td>
                            <td class="py-3 px-4 text-sm text-gray-600">{{ $schedule->stase->name }}</td>
                            <td class="py-3 px-4 text-sm text-gray-600">{{ $schedule->internshipClass->name }}</td>
                            <td class="py-3 px-4 text-sm text-gray-600">{{ $schedule->stase->responsibleStases->first()?->responsibleUser->user->name ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                   'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    let currentDate = new Date();

    // Initialize calendar
    function initCalendar() {
        const currentMonth = currentDate.getMonth();
        const currentYear = currentDate.getFullYear();

        // Set month and year selects
        $('#monthSelect').val(currentMonth);
        populateYearSelect(currentYear);
        $('#yearSelect').val(currentYear);

        renderCalendar(currentMonth, currentYear);
        loadSchedules(formatDate(currentDate));
    }

    // Update the renderCalendar function
    function renderCalendar(month, year) {
        const firstDay = new Date(year, month, 1).getDay();
        const daysInMonth = new Date(year, month + 1, 0).getDate();
        const today = new Date();
        let calendarHTML = '';

        // Empty cells for days before first of month
        for (let i = 0; i < firstDay; i++) {
            calendarHTML += '<div class="aspect-square"></div>';
        }

        // Days of month
        for (let day = 1; day <= daysInMonth; day++) {
            const currentDateStr = formatDate(new Date(year, month, day));
            const isToday = day === today.getDate() &&
                           month === today.getMonth() &&
                           year === today.getFullYear();
            const isSelected = $('.selected-date').data('date') === currentDateStr;

            calendarHTML += `
                <div class="aspect-square cursor-pointer flex items-center justify-center
                    ${isSelected ? 'bg-[#637F26] text-white' : ''}
                    ${isToday && !isSelected ? 'bg-[#637F26] text-white' : ''}
                    ${isToday && isSelected ? 'border-2 border-[#637F26]' : ''}
                    hover:bg-[#F5F7F0] rounded-lg"
                    data-date="${currentDateStr}"
                    onclick="selectDate(this)">
                    ${day}
                </div>
            `;
        }

        $('#calendarDays').html(calendarHTML);
    }

    function populateYearSelect(currentYear) {
        const yearSelect = $('#yearSelect');
        const startYear = currentYear - 2;
        const endYear = currentYear + 2;

        for (let year = startYear; year <= endYear; year++) {
            yearSelect.append(`<option value="${year}">${year}</option>`);
        }
    }

    // Event handlers
    $('#monthSelect, #yearSelect').on('change', function() {
        const selectedMonth = parseInt($('#monthSelect').val());
        const selectedYear = parseInt($('#yearSelect').val());
        currentDate = new Date(selectedYear, selectedMonth, 1);
        renderCalendar(selectedMonth, selectedYear);
    });

    // Update the selectDate function
    window.selectDate = function(element) {
        $('.temp-selected').removeClass('temp-selected border-[#637F26] border-2');

        const isToday = new Date($(element).data('date')).toDateString() === new Date().toDateString();
        const todayElement = $(`[data-date="${formatDate(new Date())}"]`);

        // Remove highlight from today's date and add outline
        todayElement.removeClass('bg-[#637F26] text-white').addClass('border-2 border-[#637F26]');

        if (!isToday) {
            $(element).addClass('temp-selected border-[#637F26] border-2');
        }

        $('.calendar-done').prop('disabled', false).removeClass('opacity-50');
    }

    // Update the calendar-done click handler
    $('.calendar-done').on('click', function() {
        const selectedElement = $('.temp-selected').first();
        if (selectedElement.length) {
            // Remove all previous selections
            $('.selected-date').removeClass('selected-date bg-[#637F26] text-white');

            // Add highlight to selected date
            selectedElement
                .removeClass('temp-selected border-[#637F26] border-2')
                .addClass('selected-date bg-[#637F26] text-white');

            // Ensure today's date has outline only
            const todayElement = $(`[data-date="${formatDate(new Date())}"]`);
            todayElement
                .removeClass('bg-[#637F26] text-white')
                .addClass('border-2 border-[#637F26]');

            loadSchedules(selectedElement.data('date'));
        }
    });

    // Update the calendar-cancel click handler
    $('.calendar-cancel').on('click', function() {
        // Remove all selections
        $('.temp-selected').removeClass('temp-selected border-[#637F26] border-2');
        $('.selected-date').removeClass('selected-date bg-[#637F26] text-white');

        // Reset to today's date and highlight it
        const today = new Date();
        currentDate = today;

        // Update selects and re-render
        $('#monthSelect').val(today.getMonth());
        $('#yearSelect').val(today.getFullYear());
        renderCalendar(today.getMonth(), today.getFullYear());

        // Ensure today's date is highlighted
        const todayElement = $(`[data-date="${formatDate(today)}"]`);
        todayElement
            .removeClass('border-2 border-[#637F26]')
            .addClass('bg-[#637F26] text-white');

        // Load today's schedules
        loadSchedules(formatDate(today));

        // Disable Pilih Tanggal button
        $('.calendar-done').prop('disabled', true).addClass('opacity-50');
    });

    // Add active state style for filter buttons
    $('.schedule-filter').click(function() {
        $('.schedule-filter').removeClass('bg-[#637F26] text-white').addClass('border-gray-300');
        $(this).removeClass('border-gray-300').addClass('bg-[#637F26] text-white');

        const filter = $(this).data('filter');
        loadFilteredSchedules(filter);
    });

    // Update the document.ready function to show "Bulan Ini" by default
    $('.schedule-filter[data-filter="this-month"]').addClass('bg-[#637F26] text-white');
    loadFilteredSchedules('this-month');

    // Modify loadSchedules to only affect the schedule cards
    function loadSchedules(date) {
        $.ajax({
            url: '/student/schedule/by-date',
            data: { date: date },
            method: 'GET',
            success: function(schedules) {
                // Only update the schedule cards, not the table
                renderSchedules(schedules);
            },
            error: function(error) {
                console.error('Error loading schedules:', error);
            }
        });
    }

    // Keep loadFilteredSchedules separate for table updates only
    function loadFilteredSchedules(filter) {
        $.ajax({
            url: '/student/schedule/filtered',
            data: { filter: filter },
            method: 'GET',
            success: function(schedules) {
                // Only update the table
                renderScheduleTable(schedules);
            },
            error: function(error) {
                console.error('Error loading schedules:', error);
            }
        });
    }

    // Initialize calendar
    initCalendar();

    function formatDate(date) {
        return date.getFullYear() + '-' +
               String(date.getMonth() + 1).padStart(2, '0') + '-' +
               String(date.getDate()).padStart(2, '0');
    }

    function renderSchedules(schedules) {
        const container = $('#scheduleContainer');
        container.empty();

        if (!schedules || schedules.length === 0) {
            container.html(`
                <div class="bg-gray-50 rounded-lg p-4 text-center">
                    <div class="text-gray-500">Tidak ada jadwal untuk tanggal ini</div>
                </div>
            `);
            return;
        }

        schedules.forEach(schedule => {
            container.append(`
                <div class="bg-[#F5F7F0] rounded-lg p-4">
                    <div class="flex justify-between items-center mb-2">
                        <h6 class="font-medium">${schedule.stase}</h6>
                        <span class="text-sm text-gray-600">Kelas ${schedule.class}</span>
                    </div>
                    <div class="text-sm text-gray-600">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#637F26]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <span>Pembimbing: ${schedule.responsibleUser}</span>
                        </div>
                    </div>
                </div>
            `);
        });
    }

    function renderScheduleTable(schedules) {
        const tbody = $('#scheduleTableBody');
        tbody.empty();

        schedules.forEach(schedule => {
            tbody.append(`
                <tr class="hover:bg-gray-50">
                    <td class="py-3 px-4 text-sm text-gray-600">
                        ${schedule.startDateFormatted} - ${schedule.endDateFormatted}
                    </td>
                    <td class="py-3 px-4 text-sm text-gray-600">${schedule.stase}</td>
                    <td class="py-3 px-4 text-sm text-gray-600">${schedule.class}</td>
                    <td class="py-3 px-4 text-sm text-gray-600">${schedule.responsibleUser}</td>
                </tr>
            `);
        });
    }
});
</script>
@endpush

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
        background-color: #4B601C;
    }

    .calendar-day.today {
        border: 2px solid #637F26;
    }

    .calendar-day.selected-date.today {
        border: 2px solid #4B601C;
    }
</style>
@endpush
@endsection
