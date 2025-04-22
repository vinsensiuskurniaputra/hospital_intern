@extends('layouts.auth')

@section('content')
<div class="p-6">
    <!-- Main Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Quick Action Card -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold mb-4">Quick Action</h2>
                
                <!-- Presensi Section -->
                <div class="space-y-4">
                    <h3 class="font-medium text-gray-700">Presensi</h3>
                    <div class="flex items-center space-x-2">
                        <span class="text-sm">Status:</span>
                        <span class="text-xs bg-orange-100 text-orange-600 px-2 py-0.5 rounded">Belum Presensi</span>
                    </div>
                    
                    <div class="mt-4">
                        <div class="relative">
                            <input type="text" placeholder="Masukkan Kode Presensi" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#637F26] focus:border-[#637F26]">
                        </div>
                        <button class="mt-2 w-full bg-[#637F26] hover:bg-[#4e6320] text-white font-medium py-2 px-4 rounded-lg transition duration-200">
                            Submit
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Middle/Right Column -->
        <div class="lg:col-span-2">
            <!-- Attendance Statistics Card -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold mb-4">Total Kehadiran</h2>
                <p class="text-gray-700 font-medium mb-6">1000 Kehadiran</p>
                
                <div class="flex flex-col md:flex-row items-center">
                    <!-- Pie Chart -->
                    <div class="w-48 h-48 md:mr-8">
                        <canvas id="attendanceChart"></canvas>
                    </div>
                    
                    <!-- Chart Legend -->
                    <div class="mt-4 md:mt-0 flex flex-col space-y-2">
                        <div class="flex items-center">
                            <span class="w-3 h-3 bg-green-500 rounded-full mr-2"></span>
                            <span>Hadir</span>
                            <span class="ml-auto font-medium">52.1%</span>
                        </div>
                        <div class="flex items-center">
                            <span class="w-3 h-3 bg-yellow-400 rounded-full mr-2"></span>
                            <span>Izin</span>
                            <span class="ml-auto font-medium">22.8%</span>
                        </div>
                        <div class="flex items-center">
                            <span class="w-3 h-3 bg-red-400 rounded-full mr-2"></span>
                            <span>Alpa</span>
                            <span class="ml-auto font-medium">13.9%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Schedule Section -->
    <div class="mt-6">
        <h2 class="text-lg font-semibold mb-4">Jadwal Hari Ini</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Schedule Cards -->
            @for($i = 0; $i < 4; $i++)
            <div class="bg-white rounded-lg shadow p-4">
                <h3 class="font-medium mb-1">Kelas FK-01</h3>
                <div class="flex items-center text-sm text-gray-500 mb-1">
                    <i class="bi bi-clock mr-1"></i>
                    <span>11:00 - 14:00</span>
                </div>
                <div class="text-sm text-gray-600">Departemen Kesehatan</div>
            </div>
            @endfor
        </div>
    </div>
    
    <!-- Bottom Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
        <!-- Notifications -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold mb-4">Notifikasi / Pengumuman Penting</h2>
            
            <div class="space-y-4">
                @for($i = 0; $i < 3; $i++)
                <div class="border-b border-gray-200 pb-4 last:border-0 last:pb-0">
                    <h3 class="font-medium mb-1">Pergantian Jadwal</h3>
                    <p class="text-sm text-gray-600 mb-2">
                        Kelas FK-01 pada departemen kesehatan terjadi perubahan jadwal/tempat tanggal 15 januari
                    </p>
                    <p class="text-xs text-gray-500">03 Jul 2024 - 13:01</p>
                </div>
                @endfor
            </div>
        </div>
        
        <!-- Grade History -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold mb-4">Riwayat Penilaian</h2>
            
            <div class="space-y-4">
                <!-- Grade Item -->
                <div class="flex items-center justify-between p-4 border-b border-gray-200">
                    <div>
                        <h3 class="font-medium">Ujian Poli Mata</h3>
                        <p class="text-sm text-gray-500">09 Juli 2024</p>
                    </div>
                    <div class="flex items-center justify-center w-12 h-12 bg-green-500 text-white font-bold rounded">
                        90
                    </div>
                </div>
                
                <!-- Grade Item -->
                <div class="flex items-center justify-between p-4 border-b border-gray-200">
                    <div>
                        <h3 class="font-medium">Ujian Poli THT</h3>
                        <p class="text-sm text-gray-500">11 Juli 2024</p>
                    </div>
                    <div class="flex items-center justify-center w-12 h-12 bg-red-500 text-white font-bold rounded">
                        50
                    </div>
                </div>
                
                <!-- Grade Item -->
                <div class="flex items-center justify-between p-4">
                    <div>
                        <h3 class="font-medium">Ujian Poli Kulit</h3>
                        <p class="text-sm text-gray-500">20 Juli 2024</p>
                    </div>
                    <div class="flex items-center justify-center w-12 h-12 bg-yellow-500 text-white font-bold rounded">
                        75
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<div class="py-4 text-center text-sm text-gray-500 bg-white border-t mt-6">
    @2025 IK Polines
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('attendanceChart').getContext('2d');
        
        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Hadir', 'Izin', 'Alpa'],
                datasets: [{
                    data: [52.1, 22.8, 13.9],
                    backgroundColor: [
                        '#4ADE80', // green
                        '#FBBF24', // yellow
                        '#F87171'  // red
                    ],
                    borderColor: '#ffffff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    });
</script>
@endpush