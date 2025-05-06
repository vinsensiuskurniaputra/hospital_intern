@extends('layouts.auth')

@section('content')
    @if(isset($error))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
        <p>{{ $error }}</p>
    </div>
    @endif

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
                    <div class="text-2xl font-bold mb-1">{{ $studentCount ?? 0 }}</div>
                    <p class="text-sm text-gray-500 mb-4">Mahasiswa dalam bimbingan anda</p>
                    <div class="flex justify-end">
                        <a href="{{ route('responsible.attendance') }}" class="bg-[#637F26] hover:bg-[#566d1e] text-white px-4 py-2 rounded-md text-sm flex items-center transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            Lihat Semua
                        </a>
                    </div>
                </div>

                <!-- Jadwal Hari Ini -->
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <h2 class="text-lg font-semibold mb-4">Jadwal Hari Ini</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @forelse($todaySchedules as $schedule)
                        <div class="bg-gray-100 rounded-lg p-4 shadow">
                            <h3 class="font-medium">{{ $schedule->internshipClass->name ?? 'Kelas' }}</h3>
                            <div class="text-xs text-gray-500 mt-1">{{ $schedule->stase->name ?? 'Departemen' }}</div>
                        </div>
                        @empty
                        <div class="col-span-2 text-center p-6 text-gray-500">
                            <p>Tidak ada jadwal untuk hari ini</p>
                        </div>
                        @endforelse
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
                        <div class="text-2xl font-bold">{{ array_sum($chartData['data'] ?? [0]) }} Kehadiran</div>
                        <div class="flex items-center mt-1">
                            @php
                                $labels = $chartData['labels'] ?? ['Jan'];
                                $lastMonthIndex = count($labels) - 1;
                                $lastMonth = $lastMonthIndex >= 0 ? $labels[$lastMonthIndex] : 'Jan';
                            @endphp
                            <span class="bg-amber-100 text-amber-800 text-xs px-2 py-1 rounded-md mr-2">{{ $lastMonth }}</span>
                            @php
                                $dataArray = $chartData['data'] ?? [0, 0];
                                $currentMonthIndex = count($dataArray) - 1;
                                $prevMonthIndex = $currentMonthIndex - 1;
                                
                                $currentMonth = $currentMonthIndex >= 0 ? $dataArray[$currentMonthIndex] : 0;
                                $previousMonth = $prevMonthIndex >= 0 ? $dataArray[$prevMonthIndex] : 0;
                                
                                $change = $previousMonth > 0 ? (($currentMonth - $previousMonth) / $previousMonth) * 100 : 0;
                            @endphp
                            <span class="flex items-center {{ $change >= 0 ? 'text-green-500' : 'text-red-500' }} text-sm">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M{{ $change >= 0 ? '13 7l5 5m0 0l-5 5m5-5H6' : '11 17l-5-5m0 0l5-5m-5 5h12' }}"></path>
                                </svg>
                                {{ number_format(abs($change), 1) }}%
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
                        <a href="{{ route('responsible.grades') }}" class="bg-[#637F26] hover:bg-[#566d1e] text-white px-4 py-2 rounded-md text-sm flex items-center transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            Lihat Semua
                        </a>
                    </div>
                    
                    <div class="space-y-4">
                        @forelse($studentsToGrade as $student)
                        <div class="flex items-center py-3 border-b border-gray-100">
                            <div class="w-10 h-10 rounded-full bg-gray-200 overflow-hidden mr-3">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($student->user->name ?? 'Student') }}" alt="Student" class="w-full h-full object-cover">
                            </div>
                            <div class="flex-grow">
                                <p class="font-medium">{{ $student->user->name ?? 'Nama Mahasiswa' }}</p>
                            </div>
                            <div class="text-sm text-gray-500 mr-4">{{ $student->nim ?? 'NIM' }}</div>
                            <div class="text-sm text-gray-500">{{ $student->internshipClass->name ?? 'Kelas' }}</div>
                        </div>
                        @empty
                        <div class="text-center py-4 text-gray-500">
                            <p>Tidak ada mahasiswa yang perlu dinilai</p>
                        </div>
                        @endforelse
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
                @forelse($notifications as $notification)
                <div class="border-b border-gray-100 pb-4 {{ $loop->last ? '' : 'border-b' }}">
                    <h3 class="font-medium mb-1">{{ $notification->title ?? 'Notifikasi' }}</h3>
                    <p class="text-sm text-gray-600 mb-1">{{ $notification->message ?? 'Tidak ada pesan' }}</p>
                    <div class="text-xs text-gray-400 text-right">
                        @if($notification->created_at)
                            {{ $notification->created_at->format('d M Y - H:i') }}
                        @else
                            {{ now()->format('d M Y - H:i') }}
                        @endif
                    </div>
                </div>
                @empty
                <div class="text-center py-4 text-gray-500">
                    <p>Tidak ada notifikasi</p>
                </div>
                @endforelse
            </div>
        </div>
        
        <div class="text-center text-gray-500 text-xs mt-8">@2025 IK Polines</div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('attendanceChart');
        
        if (ctx) {
            try {
                // Data dari controller
                const labels = {!! json_encode($chartData['labels'] ?? ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul']) !!};
                const chartData = {!! json_encode($chartData['data'] ?? [0, 0, 0, 0, 0, 0, 0]) !!};
                
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Kehadiran Mahasiswa',
                            data: chartData,
                            borderColor: '#637F26',
                            backgroundColor: 'rgba(240, 240, 245, 0.7)',
                            pointBackgroundColor: function(context) {
                                return context.dataIndex === labels.length - 1 ? '#637F26' : 'transparent';
                            },
                            pointBorderColor: function(context) {
                                return context.dataIndex === labels.length - 1 ? '#fff' : 'transparent';
                            },
                            pointBorderWidth: 2,
                            pointRadius: function(context) {
                                return context.dataIndex === labels.length - 1 ? 6 : 0;
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
                                callbacks: {
                                    // Menampilkan jumlah kehadiran alih-alih persentase
                                    label: function(context) {
                                        return `${context.parsed.y} kehadiran`;
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    display: false
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });
            } catch (e) {
                console.error('Error creating chart:', e);
                ctx.parentNode.innerHTML = '<div class="p-4 text-center text-gray-500">Grafik tidak dapat ditampilkan: ' + e.message + '</div>';
            }
        }
    });
</script>
    @endpush
@endsection