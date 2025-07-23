@extends('layouts.auth')

@section('title', 'Presensi & Sertifikasi')

@section('content')
<div class="container-fluid py-4">
    <!-- Top Cards Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">
        <!-- Attendance Summary Card -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h5 class="text-lg font-bold mb-4">Total Kehadiran</h5>
            @php
                $total = $presences ? $presences->count() : 0;
                $present = $presences ? $presences->where('status', 'present')->count() : 0;
                $sick = $presences ? $presences->where('status', 'sick')->count() : 0;
                $absent = $presences ? $presences->where('status', 'absent')->count() : 0;
            @endphp            
            @if($total > 0)
                <div class="text-lg font-medium mb-4">{{ $total }} Kehadiran</div>
                <div class="flex justify-start items-center gap-12" style="min-height: 160px;">
                    <div class="chart-container flex-shrink-0" style="width:120px; height:120px;">
                        <canvas id="donutChart" class="w-[120px] h-[120px]"></canvas>
                    </div>
                    <div class="flex flex-col space-y-2 items-start w-full">
                        <div class="flex items-center">
                            <span class="w-4 h-4 rounded-full bg-[#22c55e] mr-2"></span>
                            <span class="text-base font-medium">Hadir</span>
                            <span class="text-base font-semibold ml-4">{{ number_format($total > 0 ? ($present / $total * 100) : 0, 1) }}%</span>
                        </div>
                        <div class="flex items-center">
                            <span class="w-4 h-4 rounded-full bg-[#f59e0b] mr-2"></span>
                            <span class="text-base font-medium">Izin</span>
                            <span class="text-base font-semibold ml-4">{{ number_format($total > 0 ? ($sick / $total * 100) : 0, 1) }}%</span>
                        </div>
                        <div class="flex items-center">
                            <span class="w-4 h-4 rounded-full bg-[#ef4444] mr-2"></span>
                            <span class="text-base font-medium">Alpha</span>
                            <span class="text-base font-semibold ml-4">{{ number_format($total > 0 ? ($absent / $total * 100) : 0, 1) }}%</span>
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center py-8">
                    <div class="mb-4">
                        <svg class="w-16 h-16 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Presensi</h3>
                    <p class="text-gray-500">Anda belum memiliki catatan presensi untuk bulan ini.</p>
                </div>
            @endif
        </div>

        <!-- Certificate Card -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h5 class="text-lg font-bold mb-2">Sertifikasi</h5>
            <h6 class="text-base font-medium mb-3">Unduh Sertifikat</h6>
            <p class="text-gray-600 text-sm mb-4">
                Silahkan unduh sertifikat magang Anda sebagai bukti resmi telah menyelesaikan program magang. 
                Sertifikat ini dapat digunakan untuk keperluan akademik, portfolio, atau kebutuhan profesional lainnya. 
                Pastikan informasi pada sertifikat sudah sesuai sebelum mengunduh.
            </p>
            <div class="flex justify-end">
                <button 
                    onclick="downloadCertificate({{ auth()->user()->student->id }}, {{ $allStagesCompleted && $certificate ? 'true' : 'false' }})"
                    class="{{ $allStagesCompleted && $certificate ? 'bg-[#96D67F] hover:bg-[#85c070] text-gray-700' : 'bg-gray-300 text-gray-500 cursor-pointer' }} px-6 py-2 rounded-lg text-sm"
                    style="transition: background 0.2s;">
                    Unduh Sertifikat
                </button>
            </div>
        </div>
    </div>

    <!-- Daily Attendance Section -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-4">
        <div class="flex justify-between items-center mb-6">
            <h5 class="text-lg font-bold">Presensi Harian</h5>
            <div class="flex items-center space-x-4">
                <!-- Dropdown Bulan -->
                <select id="monthFilter" class="border rounded px-4 py-2">
                    @foreach([
                        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                    ] as $num => $name)
                        <option value="{{ $num }}" {{ request('month', now()->month) == $num ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
                <!-- Dropdown Tahun -->
                <select id="yearFilter" class="border rounded px-4 py-2">
                    @for($y = now()->year; $y >= now()->year - 5; $y--)
                        <option value="{{ $y }}" {{ request('year', now()->year) == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
        </div>

        @if($presences->count() > 0)
            <div class="overflow-x-auto">
                <table id="presenceTable" class="w-full text-center">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Jam Masuk</th>
                            <th>Jam Keluar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($presences as $i => $presence)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ \Carbon\Carbon::parse($presence->date_entry)->format('d M Y') }}</td>
                                <td>
                                    @php
                                        $status = strtolower($presence->status);
                                        $statusClass = match($status) {
                                            'present' => 'bg-green-100 text-green-800',
                                            'sick' => 'bg-yellow-100 text-yellow-800',
                                            'absent' => 'bg-red-100 text-red-800',
                                            default => 'bg-gray-100 text-gray-800'
                                        };
                                        $statusText = match($status) {
                                            'present' => 'Hadir',
                                            'sick' => 'Izin',
                                            'absent' => 'Alpha',
                                            default => ucfirst($status)
                                        };
                                    @endphp
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $statusClass }}">
                                        {{ $statusText }}
                                    </span>
                                </td>
                                <td>{{ $presence->check_in ? \Carbon\Carbon::parse($presence->check_in)->format('H:i') : '-' }}</td>
                                <td>{{ $presence->check_out ? \Carbon\Carbon::parse($presence->check_out)->format('H:i') : '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-8">
                <p class="text-gray-500">Tidak ada data presensi untuk bulan ini.</p>
            </div>
        @endif
    </div>

    <!-- Stase Attendance Section -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-4">
        <h5 class="text-lg font-bold mb-6">Presensi Setiap Stase</h5>
        @if(count($stases) > 0)
            <div id="stase-list">
                @php
                    // Urutkan berdasarkan start_date DESC (terbaru di atas)
                    $sortedStases = collect($stases)->sortByDesc(function($stase) {
                        return $stase['start_date'] ?? '';
                    })->values();
                @endphp
                @foreach($sortedStases as $idx => $stase)
                    @php
                        $start = \Carbon\Carbon::parse($stase['start_date'] ?? null);
                        $end = \Carbon\Carbon::parse($stase['end_date'] ?? null);
                        $today = \Carbon\Carbon::today();
                        $isCurrent = $start && $end && $today->between($start, $end);
                        $isFinished = $end && $today->gt($end);
                        $isUpcoming = $start && $today->lt($start);

                        $cardClass = $isCurrent ? 'border-green-500 bg-green-100'
                            : ($isFinished ? 'border-gray-500 bg-gray-100'
                            : 'border-gray-300 bg-white');
                    @endphp
                    <div class="rounded-lg border {{ $cardClass }} p-4 mb-4 relative transition-all">
                        <div class="flex justify-between items-center">
                            <div>
                                <div class="font-semibold text-base">{{ $stase['name'] }}</div>
                                <div class="text-gray-500 text-sm">{{ $stase['department'] }}</div>
                                <div class="text-gray-400 text-xs">{{ $stase['date'] }}</div>
                            </div>
                            <div>
                                <span class="inline-flex items-center justify-center rounded-full border border-gray-200 bg-white shadow"
                                      style="width: 64px; height: 64px;">
                                    <span class="text-lg font-bold">{{ $stase['percentage'] }}%</span>
                                </span>
                            </div>
                        </div>
                        <div id="stase-detail-{{ $idx }}" class="mt-3">
                            <div class="flex gap-6 text-sm">
                                <div class="flex items-center gap-1">
                                    <span class="inline-block w-3 h-3 rounded-full bg-green-600"></span>
                                    Hadir: <b>{{ $stase['attendance']['present'] }}%</b>
                                </div>
                                <div class="flex items-center gap-1">
                                    <span class="inline-block w-3 h-3 rounded-full bg-yellow-500"></span>
                                    Izin: <b>{{ $stase['attendance']['sick'] }}%</b>
                                </div>
                                <div class="flex items-center gap-1">
                                    <span class="inline-block w-3 h-3 rounded-full bg-red-500"></span>
                                    Alpha: <b>{{ $stase['attendance']['absent'] }}%</b>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-gray-400">Belum ada stase yang dijadwalkan.</div>
        @endif
    </div>

    
  
</div>

<!-- Notification Popup -->
<div id="notification" 
    class="fixed top-4 right-4 px-6 py-4 rounded-lg shadow-lg transform transition-all duration-300 opacity-0 translate-y-[-1rem] pointer-events-none">
</div>

@push('styles')
<!-- Existing styles -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/datetime/1.5.1/css/dataTables.dateTime.min.css">
<style>
    body, .container-fluid, table, th, td, input, select, button, .rounded-lg, .text-sm, .text-base, .text-lg, .font-medium, .font-semibold {
        font-family: 'Inter', Arial, sans-serif !important;
    }

    #presenceTable {
        border-collapse: collapse;
        width: 100%;
    }

    #presenceTable th, #presenceTable td {
        text-align: center !important;
        vertical-align: middle !important;
        border: 1px solid #e5e7eb; /* abu-abu muda */
    }

    #presenceTable thead th {
        background-color: #f8fafc;
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

    #donutChart {
        max-width: 120px !important;
        max-height: 120px !important;
    }

    .chart-container {
        width: 120px !important;
        height: 120px !important;
    }

    /* DataTables custom styles */
    .dataTables_wrapper .dataTables_length select {
        @apply border border-gray-300 rounded px-2 py-1;
    }
    
    .dataTables_wrapper .dataTables_filter input {
        @apply border border-gray-300 rounded px-2 py-1;
    }
    
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        @apply px-3 py-1 mx-1 rounded;
    }
    
    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        @apply bg-[#96D67F] text-white;
    }
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/datetime/1.5.1/js/dataTables.dateTime.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Donut Chart
        const ctx = document.getElementById('donutChart').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Hadir', 'Izin', 'Alpha'],
                datasets: [{
                    data: [
                        {{ number_format($total > 0 ? ($present / $total * 100) : 0, 1) }},
                        {{ number_format($total > 0 ? ($sick / $total * 100) : 0, 1) }},
                        {{ number_format($total > 0 ? ($absent / $total * 100) : 0, 1) }}
                    ],
                    backgroundColor: ['#22c55e', '#f59e0b', '#ef4444'],
                    borderWidth: 0
                }]
            },
            options: {
                cutout: '75%',
                responsive: true,
                maintainAspectRatio: true,
                aspectRatio: 1,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                layout: {
                    padding: 0
                }
            }
        });
    });

    $(document).ready(function() {
        // DataTables
        $('#presenceTable').DataTable({
            pageLength: 10,
            lengthMenu: [[10, 20, -1], [10, 20, 'Semua']],
            order: [[0, 'desc']],
            language: {
                lengthMenu: "Tampilkan _MENU_ data",
                zeroRecords: "Data tidak ditemukan",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                infoFiltered: "(difilter dari _MAX_ total data)",
                search: "Cari:",
                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: ">",
                    previous: "<"
                }
            }
        });

        // Filter bulan/tahun: reload page dengan query string
        $('#monthFilter, #yearFilter').change(function() {
            let month = $('#monthFilter').val();
            let year = $('#yearFilter').val();
            let url = new URL(window.location.href);
            url.searchParams.set('month', month);
            url.searchParams.set('year', year);
            window.location.href = url.toString();
        });
    });

    function handleDownload(isCompleted) {
        const notification = document.getElementById('notification');
        
        if (!isCompleted) {
            // Error notification
            notification.className = 'fixed top-4 right-4 px-6 py-4 rounded-lg shadow-lg bg-[#FF6B6B] text-white transform transition-all duration-300';
            notification.innerHTML = `
                <div class="text-center">
                    <p class="font-medium mb-1">Gagal mengunduh sertifikat!</p>
                    <p class="text-sm">Magang anda belum selesai atau hubungi admin jika ada masalah lain</p>
                </div>`;
        } else {
            // Success notification
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
        
        // Hide after 3 seconds
        setTimeout(() => {
            notification.style.opacity = '0';
            notification.style.transform = 'translateY(-1rem)';
        }, 3000);
    }

    function downloadCertificate(studentId, isCompleted) {
        if (!isCompleted) {
            // Tampilkan popup error seperti gambar 2
            handleDownload(false);
            return;
        }
        fetch(`{{ url('/student/certificate/download') }}/${studentId}`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (response.status === 200) {
                return response.blob();
            } else {
                handleDownload(false);
                throw new Error('Gagal mengunduh sertifikat');
            }
        })
        .then(blob => {
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = "Sertifikat_Magang.pdf";
            document.body.appendChild(a);
            a.click();
            a.remove();
            handleDownload(true);
        })
        .catch(error => {
            // Sudah ditangani di atas
        });
    }

    function toggleStaseDetail(idx) {
        const el = document.getElementById('stase-detail-' + idx);
        if (el) el.classList.toggle('hidden');
    }
</script>
@endpush
@endsection
