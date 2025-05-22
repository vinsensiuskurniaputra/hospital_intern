@extends('layouts.auth')

@section('title', 'Presensi & Sertifikasi')

@section('content')
<div class="container-fluid py-4">
    <!-- Top Cards Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">
        <!-- Attendance Summary Card -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h5 class="text-lg font-medium mb-4">Total Kehadiran</h5>
            @php
                $total = $presences ? $presences->count() : 0;
                $present = $presences ? $presences->where('status', 'present')->count() : 0;
                $sick = $presences ? $presences->where('status', 'sick')->count() : 0;
                $absent = $presences ? $presences->where('status', 'absent')->count() : 0;
            @endphp            
            @if($total > 0)
                <div class="text-2xl font-medium mb-4">{{ $total }} Kehadiran</div>
                <div class="flex justify-between items-center">
                    <div class="chart-container">
                        <canvas id="donutChart" class="w-10 h-14"></canvas>
                    </div>
                    <div class="flex flex-col space-y-2">
                        <div class="flex items-center">
                            <span class="w-2 h-2 rounded-full bg-[#22c55e] mr-2"></span>
                            <span class="text-sm">Hadir</span>
                            <span class="text-sm ml-4">{{ number_format($total > 0 ? ($present / $total * 100) : 0, 1) }}%</span>
                        </div>
                        <div class="flex items-center">
                            <span class="w-2 h-2 rounded-full bg-[#f59e0b] mr-2"></span>
                            <span class="text-sm">Izin</span>
                            <span class="text-sm ml-4">{{ number_format($total > 0 ? ($sick / $total * 100) : 0, 1) }}%</span>
                        </div>
                        <div class="flex items-center">
                            <span class="w-2 h-2 rounded-full bg-[#ef4444] mr-2"></span>
                            <span class="text-sm">Alpha</span>
                            <span class="text-sm ml-4">{{ number_format($total > 0 ? ($absent / $total * 100) : 0, 1) }}%</span>
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
                    class="{{ $allStagesCompleted ? 'bg-[#96D67F] hover:bg-[#85c070]' : 'bg-gray-200 cursor-not-allowed' }} 
                        px-6 py-2 text-gray-700 rounded-lg transition-colors">
                    Unduh
                </button>
            </div>
        </div>
    </div>

    <!-- Daily Attendance Section -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-4">
        <div class="flex justify-between items-center mb-6">
            <h5 class="text-lg font-medium">Presensi Harian</h5>
            <div class="text-sm font-medium">Bulan {{ Carbon\Carbon::now()->format('F Y') }}</div>
        </div>

        @if($presences->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stase</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jam Masuk</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jam Keluar</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($presences->sortByDesc('date_entry') as $presence)
                        <tr>
                            <td class="px-4 py-2">{{ Carbon\Carbon::parse($presence->date_entry)->format('M d, Y') }}</td>
                            <td class="px-4 py-2">{{ $presence->presenceSession->schedule->internshipClass->name ?? 'FK-01' }}</td>
                            <td class="px-4 py-2">{{ $presence->presenceSession->schedule->stase->name ?? 'Tidak Diketahui' }}</td>
                            <td class="px-4 py-2">{{ $presence->check_in }}</td>
                            <td class="px-4 py-2">{{ $presence->check_out ?? '-' }}</td>
                            <td class="px-4 py-2">
                                <span class="px-2 py-1 text-xs rounded-full
                                    {{ $presence->status === 'present' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $presence->status === 'sick' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $presence->status === 'absent' ? 'bg-red-100 text-red-800' : '' }}">
                                    {{ ucfirst($presence->status) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-8">
                <div class="mb-4">
                    <svg class="w-16 h-16 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Presensi</h3>
                <p class="text-gray-500 mb-4">Anda belum memiliki catatan presensi harian untuk bulan ini.</p>
            </div>
        @endif
    </div>

    <!-- Stase Attendance Section -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-4">
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

                    <div class="mt-4">
                        <div class="w-full bg-gray-100 rounded-full h-2">
                            <div class="bg-[#637F26] h-2 rounded-full transition-all duration-300" 
                                 style="width: {{ $stase['percentage'] }}%">
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 grid grid-cols-3 gap-4">
                        <div class="flex items-center">
                            <span class="w-2 h-2 rounded-full bg-[#22c55e] mr-2"></span>
                            <span class="text-sm text-gray-600">Hadir</span>
                            <span class="text-sm ml-auto">{{ $stase['attendance']['present'] }}%</span>
                        </div>
                        <div class="flex items-center">
                            <span class="w-2 h-2 rounded-full bg-[#f59e0b] mr-2"></span>
                            <span class="text-sm text-gray-600">Izin</span>
                            <span class="text-sm ml-auto">{{ $stase['attendance']['sick'] }}%</span>
                        </div>
                        <div class="flex items-center">
                            <span class="w-2 h-2 rounded-full bg-[#ef4444] mr-2"></span>
                            <span class="text-sm text-gray-600">Alpha</span>
                            <span class="text-sm ml-auto">{{ $stase['attendance']['absent'] }}%</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
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

    <!-- Certificate Simulation Section -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex justify-between items-center mb-4">
            <h5 class="text-lg font-medium">Demo Sertifikasi</h5>
            <span class="text-xs bg-[#E8F5E9] text-[#637F26] px-2 py-1 rounded">Contoh jika semua stase selesai</span>
        </div>
        <p class="text-gray-600 text-sm mb-4">
            Tombol ini hanya untuk demonstrasi. Klik untuk melihat notifikasi bagaimana tampilannya ketika semua stase telah selesai.
        </p>
        <div class="flex justify-end">
            <button onclick="handleDownload(true)" 
                class="bg-[#96D67F] hover:bg-[#85c070] px-6 py-2 rounded-lg text-sm text-gray-700">
                Simulasi Unduh
            </button>
        </div>
    </div>
</div>

<!-- Notification Popup -->
<div id="notification" 
    class="fixed top-4 right-4 px-6 py-4 rounded-lg shadow-lg transform transition-all duration-300 opacity-0 translate-y-[-1rem] pointer-events-none">
</div>

@push('styles')
<style>
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
        max-width: 80px;
        max-height: 80px;
    }

    .chart-container {
        position: relative;
        width: 80px !important;
        height: 80px !important;
    }
</style>
@endpush

@push('scripts')
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
</script>
@endpush
@endsection