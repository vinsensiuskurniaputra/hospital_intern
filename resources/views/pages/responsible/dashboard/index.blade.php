@extends('layouts.auth')

@section('content')
    <div class="p-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Left Column -->
            <div class="space-y-6">
                <!-- Quick Action -->
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <h2 class="text-lg font-semibold mb-4">Quick Action</h2>
                    
                    <div class="mb-6">
                        <h3 class="text-base font-medium mb-2">Presensi</h3>
                        <div class="flex items-center text-sm mb-3">
                            <span class="mr-2">Status Kelas:</span>
                            <span class="bg-amber-100 text-amber-800 px-2 py-1 rounded-md">Belum Aktif</span>
                        </div>
                        <button class="w-full bg-[#637F26] hover:bg-[#566d1e] text-white py-3 rounded-md transition-colors">
                            Generate Kode
                        </button>
                        <p class="text-sm text-gray-500 mt-2">Kode akan aktif selama 1 hari</p>
                    </div>
                </div>

                <!-- Mahasiswa yang dibimbing -->
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <h2 class="text-lg font-semibold mb-1">Mahasiswa yang dibimbing</h2>
                    <div class="text-2xl font-bold mb-1">12</div>
                    <p class="text-sm text-gray-500 mb-4">Mahasiswa dalam bimbingan anda</p>
                    <div class="flex justify-end">
                        <button class="bg-[#637F26] hover:bg-[#566d1e] text-white px-4 py-2 rounded-md text-sm flex items-center transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            Lihat Semua
                        </button>
                    </div>
                </div>

                <!-- Jadwal Hari Ini -->
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <h2 class="text-lg font-semibold mb-4">Jadwal Hari Ini</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Jadwal 1 -->
                        <div class="bg-gray-100 rounded-lg p-4 shadow">
                            <h3 class="font-medium">Kelas FK-01</h3>
                            <div class="flex items-center mt-1 text-sm text-gray-500">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                09:00 - 11:00
                            </div>
                            <div class="text-xs text-gray-500 mt-1">Departemen Kesehatan</div>
                        </div>

                        <!-- Jadwal 2 -->
                        <div class="bg-gray-100 rounded-lg p-4 shadow">
                            <h3 class="font-medium">Kelas FK-01</h3>
                            <div class="flex items-center mt-1 text-sm text-gray-500">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                10:00 - 12:00
                            </div>
                            <div class="text-xs text-gray-500 mt-1">Departemen Kesehatan</div>
                        </div>

                        <!-- Jadwal 3 -->
                        <div class="bg-gray-100 rounded-lg p-4 shadow">
                            <h3 class="font-medium">Kelas FK-01</h3>
                            <div class="flex items-center mt-1 text-sm text-gray-500">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                10:00 - 11:00
                            </div>
                            <div class="text-xs text-gray-500 mt-1">Departemen Kesehatan</div>
                        </div>

                        <!-- Jadwal 4 -->
                        <div class="bg-gray-100 rounded-lg p-4 shadow">
                            <h3 class="font-medium">Kelas FK-01</h3>
                            <div class="flex items-center mt-1 text-sm text-gray-500">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                10:00 - 11:00
                            </div>
                            <div class="text-xs text-gray-500 mt-1">Departemen Kesehatan</div>
                        </div>

                        <!-- Jadwal 5 -->
                        <div class="bg-gray-100 rounded-lg p-4 shadow">
                            <h3 class="font-medium">Kelas FK-01</h3>
                            <div class="flex items-center mt-1 text-sm text-gray-500">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                08:00 - 11:00
                            </div>
                            <div class="text-xs text-gray-500 mt-1">Departemen Kesehatan</div>
                        </div>

                        <!-- Jadwal 6 -->
                        <div class="bg-gray-100 rounded-lg p-4 shadow">
                            <h3 class="font-medium">Kelas FK-01</h3>
                            <div class="flex items-center mt-1 text-sm text-gray-500">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                10:00 - 11:00
                            </div>
                            <div class="text-xs text-gray-500 mt-1">Departemen Kesehatan</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-6">
                <!-- Kehadiran Mahasiswa Card -->
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <h2 class="text-lg font-semibold mb-3">Kehadiran Mahasiswa</h2>
                    
                    <div class="mb-3">
                        <div class="text-sm text-gray-600">Total Kehadiran</div>
                        <div class="text-2xl font-bold">1000 Kehadiran</div>
                        <div class="flex items-center mt-1">
                            <span class="bg-amber-100 text-amber-800 text-xs px-2 py-1 rounded-md mr-2">Januari</span>
                            <span class="flex items-center text-red-500 text-sm">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                                2,5%
                            </span>
                        </div>
                    </div>
                    
                    <!-- Chart Canvas dengan padding dan border atas yang tipis -->
                    <div class="h-40 mt-6 mb-4 pt-4 border-t border-gray-100">
                        <canvas id="attendanceChart"></canvas>
                    </div>
                </div>

                <!-- Mahasiswa yang harus dinilai -->
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-semibold">Mahasiswa yang harus dinilai</h2>
                        <button class="bg-[#637F26] hover:bg-[#566d1e] text-white px-4 py-2 rounded-md text-sm flex items-center transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            Lihat Semua
                        </button>
                    </div>
                    
                    <div class="space-y-4">
                        <!-- Student 1 -->
                        <div class="flex items-center py-3 border-b border-gray-100">
                            <div class="w-10 h-10 rounded-full bg-gray-200 overflow-hidden mr-3">
                                <img src="https://ui-avatars.com/api/?name=Nama+Mahasiswa" alt="Student" class="w-full h-full object-cover">
                            </div>
                            <div class="flex-grow">
                                <p class="font-medium">Nama Mahasiswa</p>
                            </div>
                            <div class="text-sm text-gray-500 mr-4">Ujian 1</div>
                            <div class="text-sm text-gray-500">Kelas FK-01</div>
                        </div>

                        <!-- Student 2 -->
                        <div class="flex items-center py-3 border-b border-gray-100">
                            <div class="w-10 h-10 rounded-full bg-gray-200 overflow-hidden mr-3">
                                <img src="https://ui-avatars.com/api/?name=Nama+Mahasiswa" alt="Student" class="w-full h-full object-cover">
                            </div>
                            <div class="flex-grow">
                                <p class="font-medium">Nama Mahasiswa</p>
                            </div>
                            <div class="text-sm text-gray-500 mr-4">Ujian 1</div>
                            <div class="text-sm text-gray-500">Kelas FK-01</div>
                        </div>

                        <!-- Student 3 -->
                        <div class="flex items-center py-3 border-b border-gray-100">
                            <div class="w-10 h-10 rounded-full bg-gray-200 overflow-hidden mr-3">
                                <img src="https://ui-avatars.com/api/?name=Nama+Mahasiswa" alt="Student" class="w-full h-full object-cover">
                            </div>
                            <div class="flex-grow">
                                <p class="font-medium">Nama Mahasiswa</p>
                            </div>
                            <div class="text-sm text-gray-500 mr-4">Ujian 1</div>
                            <div class="text-sm text-gray-500">Kelas FK-01</div>
                        </div>

                        <!-- Student 4 -->
                        <div class="flex items-center py-3 border-b border-gray-100">
                            <div class="w-10 h-10 rounded-full bg-gray-200 overflow-hidden mr-3">
                                <img src="https://ui-avatars.com/api/?name=Nama+Mahasiswa" alt="Student" class="w-full h-full object-cover">
                            </div>
                            <div class="flex-grow">
                                <p class="font-medium">Nama Mahasiswa</p>
                            </div>
                            <div class="text-sm text-gray-500 mr-4">Ujian 1</div>
                            <div class="text-sm text-gray-500">Kelas FK-01</div>
                        </div>

                        <!-- Student 5 -->
                        <div class="flex items-center py-3">
                            <div class="w-10 h-10 rounded-full bg-gray-200 overflow-hidden mr-3">
                                <img src="https://ui-avatars.com/api/?name=Nama+Mahasiswa" alt="Student" class="w-full h-full object-cover">
                            </div>
                            <div class="flex-grow">
                                <p class="font-medium">Nama Mahasiswa</p>
                            </div>
                            <div class="text-sm text-gray-500 mr-4">Ujian 1</div>
                            <div class="text-sm text-gray-500">Kelas FK-01</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notifikasi / Pengumuman Penting (Full width) -->
        <div class="bg-white p-6 rounded-lg shadow-sm mt-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold">Notifikasi / Pengumuman Penting</h2>
                <button class="bg-[#637F26] hover:bg-[#566d1e] text-white px-4 py-2 rounded-md text-sm flex items-center transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    Lihat Semua
                </button>
            </div>

            <div class="space-y-4">
                <!-- Announcement 1 -->
                <div class="border-b border-gray-100 pb-4">
                    <h3 class="font-medium mb-1">Pergantian Jadwal</h3>
                    <p class="text-sm text-gray-600 mb-1">Kelas FK-01 pada departemen kesehatan terjadi perubahan jadwal menjadi tanggal 12 januari</p>
                    <div class="text-xs text-gray-400 text-right">09 Jul 2024 - 08:03</div>
                </div>

                <!-- Announcement 2 -->
                <div class="border-b border-gray-100 pb-4">
                    <h3 class="font-medium mb-1">Pergantian Jadwal</h3>
                    <p class="text-sm text-gray-600 mb-1">Kelas FK-01 pada departemen kesehatan terjadi perubahan jadwal menjadi tanggal 12 januari</p>
                    <div class="text-xs text-gray-400 text-right">09 Jul 2024 - 08:03</div>
                </div>

                <!-- Announcement 3 -->
                <div>
                    <h3 class="font-medium mb-1">Pergantian Jadwal</h3>
                    <p class="text-sm text-gray-600 mb-1">Kelas FK-01 pada departemen kesehatan terjadi perubahan jadwal menjadi tanggal 12 januari</p>
                    <div class="text-xs text-gray-400 text-right">09 Jul 2024 - 08:03</div>
                </div>
            </div>
        </div>
        
        <div class="text-center text-gray-500 text-xs mt-8">@2025 IK Polines</div>
    </div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('attendanceChart');
        
        if (ctx) {
            try {
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
                        datasets: [{
                            label: 'Kehadiran Mahasiswa',
                            data: [800, 750, 880, 920, 870, 830, 900],
                            borderColor: '#637F26',
                            backgroundColor: 'rgba(240, 240, 245, 0.7)', // Warna abu-abu muda sesuai design
                            pointBackgroundColor: function(context) {
                                // Hanya titik terakhir yang berwarna hijau
                                return context.dataIndex === 6 ? '#637F26' : 'transparent';
                            },
                            pointBorderColor: function(context) {
                                // Titik terakhir dengan border putih, sisanya transparan
                                return context.dataIndex === 6 ? '#fff' : 'transparent';
                            },
                            pointBorderWidth: 2,
                            pointRadius: function(context) {
                                // Titik terakhir lebih besar
                                return context.dataIndex === 6 ? 6 : 0;
                            },
                            fill: true,
                            tension: 0.4,
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                enabled: true,
                                backgroundColor: 'rgba(255, 255, 255, 0.9)',
                                titleColor: '#333',
                                bodyColor: '#666',
                                titleFont: {
                                    size: 12,
                                    weight: 'bold'
                                },
                                bodyFont: {
                                    size: 11
                                },
                                padding: 10,
                                borderColor: '#ddd',
                                borderWidth: 1,
                                displayColors: false,
                                callbacks: {
                                    title: function(tooltipItems) {
                                        return tooltipItems[0].label;
                                    },
                                    label: function(context) {
                                        return context.raw + ' Kehadiran';
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                display: false, // Sembunyikan sumbu Y sesuai design
                                beginAtZero: false,
                                grid: {
                                    display: false
                                }
                            },
                            x: {
                                grid: {
                                    display: false,
                                    drawBorder: false
                                },
                                ticks: {
                                    color: '#9CA3AF', // Warna abu-abu untuk label bulan
                                    font: {
                                        size: 11
                                    },
                                    padding: 10
                                }
                            }
                        },
                        elements: {
                            line: {
                                borderJoinStyle: 'round'
                            },
                            point: {
                                hoverRadius: 8,
                                hoverBorderWidth: 2
                            }
                        },
                        layout: {
                            padding: {
                                top: 10,
                                right: 10, 
                                bottom: 0
                            }
                        }
                    }
                });
            } catch (e) {
                console.error('Error creating chart:', e);
            }
        } else {
            console.error('Canvas element not found');
        }
    });

</script>
@endsection