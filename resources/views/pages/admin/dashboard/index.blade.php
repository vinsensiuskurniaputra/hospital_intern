@extends('layouts.auth')

@section('title', 'Dashboard')

@section('content')
    <div class="p-6 space-y-6">

        <!-- Stats Section -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Students Stats -->
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Mahasiswa</p>
                        <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $stats['students'] }}</h3>
                    </div>
                    <div class="p-3 bg-[#F5F7F0] rounded-lg">
                        <i class="bi bi-mortarboard text-xl text-[#637F26]"></i>
                    </div>
                </div>
            </div>

            <!-- Responsible Users Stats -->
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Penanggung Jawab</p>
                        <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $stats['pics'] }}</h3>
                    </div>
                    <div class="p-3 bg-[#F5F7F0] rounded-lg">
                        <i class="bi bi-file-earmark-person text-xl text-[#637F26]"></i>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-4">{{ now()->year }}</p>
            </div>

            <!-- Admins Stats -->
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Admin</p>
                        <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $stats['admins'] }}</h3>
                    </div>
                    <div class="p-3 bg-[#F5F7F0] rounded-lg">
                        <i class="bi bi-shield-check text-xl text-[#637F26]"></i>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-4">{{ now()->year }}</p>
            </div>
        </div>

        <!-- Attendance Chart -->
        <div class="lg:col-span-2 bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Kehadiran Mahasiswa</h3>
                    <p class="text-sm text-gray-500">Januari - {{ now()->format('F Y') }}</p>
                </div>
                <div class="text-right">
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['presences'] }}</p>
                </div>
            </div>
            <div class="h-[300px]">
                <!-- Add your chart component here -->
                <canvas id="attendanceChart"></canvas>
            </div>
        </div>

        <!-- Upcoming Schedules -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-800">Jadwal Mendatang</h3>
                <a href="#" class="text-sm text-[#637F26] hover:text-[#85A832]">Lihat Semua</a>
            </div>
            <div class="space-y-4">
                @foreach ($upcomingSchedules as $schedule)
                    <div class="p-4 rounded-lg bg-[#F5F7F0] border border-[#637F26]/10">
                        <div class="flex items-center justify-between">
                            <div>
                                <span
                                    class="text-xs font-medium text-[#637F26] bg-white px-2 py-1 rounded">{{ $schedule->stase->name }}</span>
                                <h4 class="mt-2 font-medium text-gray-800">{{ $schedule->internshipClass->name }}</h4>
                                <p class="text-sm text-gray-500">{{ $schedule->stase->departement->name }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-gray-600">{{ $schedule->start_date->format('H:i') }} -
                                    {{ $schedule->end_date->format('H:i') }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const ctx = document.getElementById('attendanceChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($chartData['labels']),
                    datasets: [{
                        label: 'Kehadiran',
                        data: @json($chartData['data']),
                        borderColor: '#637F26',
                        backgroundColor: '#F5F7F0',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                display: true,
                                drawBorder: false
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
        </script>
    @endpush
@endsection
