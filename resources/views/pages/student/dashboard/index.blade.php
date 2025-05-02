@extends('layouts.auth')

@section('content')
<div class="p-6 bg-gray-50">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column -->
        <div class="lg:col-span-1">
            <!-- Quick Action Card -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold mb-4">Quick Action</h2>
                
                <!-- Presensi Section -->
                <div>
                    <h3 class="font-medium text-gray-700 mb-2">Presensi</h3>
                    <div class="flex items-center space-x-2 mb-4">
                        <span class="text-sm">Status:</span>
                        <span class="text-xs bg-orange-100 text-orange-600 px-2 py-0.5 rounded">Belum Presensi</span>
                    </div>
                    
                    <div class="relative mb-2">
                        <input type="text" placeholder="Masukkan Kode Presensi" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#637F26] focus:border-[#637F26]">
                    </div>
                    <button class="w-full bg-[#637F26] hover:bg-[#4e6320] text-white font-medium py-2 px-4 rounded-lg transition duration-200">
                        Submit
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Middle/Right Column -->
        <div class="lg:col-span-2">
            <!-- Attendance Statistics Card -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold mb-2">Total Kehadiran</h2>
                <p class="text-gray-700 font-medium mb-4">{{ $attendanceStats['total'] ?? 0 }} Kehadiran</p>
                
                <div class="flex flex-col md:flex-row items-center">
                    <!-- Pie Chart -->
                    <div class="w-48 h-48">
                        <canvas id="attendanceChart"></canvas>
                    </div>
                    
                    <!-- Chart Legend -->
                    <div class="mt-4 md:mt-0 md:ml-8 flex flex-col space-y-2 flex-1">
                        <div class="flex items-center">
                            <span class="w-3 h-3 bg-green-500 rounded-full mr-2"></span>
                            <span>Hadir</span>
                            <span class="ml-auto font-medium">{{ $attendanceStats['present']['percent'] ?? 0 }}%</span>
                        </div>
                        <div class="flex items-center">
                            <span class="w-3 h-3 bg-yellow-400 rounded-full mr-2"></span>
                            <span>Izin</span>
                            <span class="ml-auto font-medium">{{ $attendanceStats['sick']['percent'] ?? 0 }}%</span>
                        </div>
                        <div class="flex items-center">
                            <span class="w-3 h-3 bg-red-400 rounded-full mr-2"></span>
                            <span>Alpa</span>
                            <span class="ml-auto font-medium">{{ $attendanceStats['absent']['percent'] ?? 0 }}%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Schedule Section -->
    <div class="mt-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold mb-4">Jadwal Hari Ini</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @forelse($todaySchedules as $schedule)
                <div class="bg-gray-100 rounded-lg p-4 shadow-[0_2px_4px_-1px_rgba(0,0,0,0.1)]">
                    @if(isset($schedule->internshipClass))
                    <h3 class="font-medium mb-1">{{ $schedule->internshipClass->name ?? 'Kelas' }}</h3>
                    @endif
                    
                    <div class="text-sm text-gray-600 font-medium">
                        {{ $schedule->stase->name ?? 'Stase tidak tersedia' }}
                    </div>
                    
                    @if(isset($schedule->departement))
                    <div class="text-sm text-gray-500 mb-1">
                        {{ $schedule->departement->name ?? '' }}
                    </div>
                    @endif
                    
                    @if(isset($schedule->responsibleUser) && isset($schedule->responsibleUser->user))
                    <div class="mt-2 text-xs text-gray-500">
                        <i class="bi bi-person-circle mr-1"></i>
                        <span>{{ $schedule->responsibleUser->user->name ?? '' }}</span>
                    </div>
                    @endif
                </div>
                @empty
                <div class="col-span-2 text-center p-6 text-gray-500">
                    <i class="bi bi-calendar-x text-3xl mb-2"></i>
                    <p>Tidak ada jadwal untuk hari ini</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
    
    <!-- Bottom Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
        <!-- Notifications -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold">Notifikasi Terbaru</h2>
                <a href="{{ route('student.notifications') }}" class="text-sm text-[#637F26] hover:underline">
                    Lihat Semua
                </a>
            </div>
            
            <div class="space-y-4">
                @forelse($notifications as $notification)
                <div class="border-b border-gray-200 pb-4 last:border-0 last:pb-0 {{ $notification['is_read'] ? 'opacity-70' : '' }}">
                    <h3 class="font-medium mb-1">{{ $notification['title'] }}</h3>
                    <p class="text-sm text-gray-600 mb-2">
                        {{ $notification['message'] }}
                    </p>
                    <p class="text-xs text-gray-500">{{ $notification['created_at']->format('d M Y - H:i') }}</p>
                </div>
                @empty
                <div class="text-center p-6 text-gray-500">
                    <i class="bi bi-bell-slash text-3xl mb-2"></i>
                    <p>Tidak ada notifikasi</p>
                </div>
                @endforelse
            </div>
        </div>
        
        <!-- Grade History -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold">Riwayat Penilaian</h2>
                <a href="{{ route('student.grades') }}" class="text-sm text-[#637F26] hover:underline">
                    Lihat Semua
                </a>
            </div>
            
            <div class="space-y-4">
                @forelse($recentGrades as $grade)
                @php
                    $gradeClass = 'bg-yellow-500';
                    if ($grade->avg_grades >= 80) {
                        $gradeClass = 'bg-green-500';
                    } elseif ($grade->avg_grades < 60) {
                        $gradeClass = 'bg-red-500';
                    }
                @endphp
                
                <!-- Grade Item -->
                <div class="flex items-center justify-between p-4 bg-gray-100 rounded-lg shadow-[0_2px_4px_-1px_rgba(0,0,0,0.1)]">
                    <div>
                        <h3 class="font-medium">{{ $grade->departement->name ?? 'Departemen' }} - {{ $grade->stase->name ?? 'Stase' }}</h3>
                        <p class="text-sm text-gray-500">{{ $grade->created_at->format('d M Y') }}</p>
                    </div>
                    <div class="flex items-center justify-center w-12 h-12 {{ $gradeClass }} text-white text-2xl font-bold rounded">
                        {{ round($grade->avg_grades) }}
                    </div>
                </div>
                @empty
                <div class="text-center p-6 text-gray-500">
                    <i class="bi bi-card-list text-3xl mb-2"></i>
                    <p>Belum ada data penilaian</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('attendanceChart').getContext('2d');
        
        // Data dari backend disimpan sebagai variabel JavaScript
        const attendanceData = @json([
            $attendanceStats['present']['percent'] ?? 0,
            $attendanceStats['sick']['percent'] ?? 0, 
            $attendanceStats['absent']['percent'] ?? 0
        ]);
        
        // Membuat chart dengan Chart.js
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Hadir', 'Izin', 'Alpa'],
                datasets: [{
                    data: attendanceData,
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
                cutout: '70%',
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