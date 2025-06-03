@extends('layouts.auth')

@section('title', 'Laporan dan Monitoring')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.min.css">
@endpush

@section('content')
    <div class="p-6 space-y-6">
        <!-- Header Section -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-800">Laporan & Monitoring</h1>
                    <p class="mt-1 text-sm text-gray-500">Pantau kinerja dan kehadiran mahasiswa</p>
                </div>
                <div class="flex gap-3">
                    <form action="{{ route('admin.reportAndMonitorings.index') }}" method="GET"
                        class="flex items-center gap-3">
                        <select name="internship_class_id" class="px-4 py-2 text-sm border border-gray-200 rounded-lg">
                            <option value="">Semua Kelas</option>
                            @foreach ($internshipClasses as $class)
                                <option value="{{ $class->id }}"
                                    {{ request('internship_class_id') == $class->id ? 'selected' : '' }}>
                                    {{ $class->name }}
                                </option>
                            @endforeach
                        </select>
                        <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-gray-50">
                            <i class="bi bi-filter mr-2"></i>Filter Data
                        </button>
                    </form>
                    <form action="{{ route('admin.reportAndMonitorings.export') }}" method="GET">
                        <input type="hidden" name="internship_class_id" value="{{ request('internship_class_id') }}">
                        <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-[#637F26] rounded-lg hover:bg-[#85A832]">
                            <i class="bi bi-download mr-2"></i>Unduh Laporan
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Mahasiswa</p>
                        <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $countStudent }}</h3>
                    </div>
                    <div class="p-3 bg-[#F5F7F0] rounded-lg">
                        <i class="bi bi-people text-xl text-[#637F26]"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Dokter</p>
                        <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $countPIC }}</h3>
                    </div>
                    <div class="p-3 bg-[#F5F7F0] rounded-lg">
                        <i class="bi bi-person-badge text-xl text-[#637F26]"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Admin</p>
                        <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $countAdmin }}</h3>
                    </div>
                    <div class="p-3 bg-[#F5F7F0] rounded-lg">
                        <i class="bi bi-person text-xl text-[#637F26]"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Attendance Chart -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="p-6 border-b border-gray-100">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">Kehadiran Mahasiswa</h3>
                            <p class="text-sm text-gray-500">Ikhtisar kehadiran bulanan</p>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <canvas id="attendanceChart" height="300"></canvas>
                </div>
            </div>

            <!-- Performance Chart -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="p-6 border-b border-gray-100">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">Kinerja Mahasiswa</h3>
                            <p class="text-sm text-gray-500">Rata-rata nilai per departemen</p>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <canvas id="performanceChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Attendance Chart
                const attendanceChart = new Chart(document.getElementById('attendanceChart'), {
                    type: 'line',
                    data: {
                        labels: @json($attendanceChart['labels']),
                        datasets: [{
                            label: 'Kehadiran',
                            data: @json($attendanceChart['data']),
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

                // Performance Chart
                const performanceChart = new Chart(document.getElementById('performanceChart'), {
                    type: 'bar',
                    data: {
                        labels: @json($performanceChart['labels']),
                        datasets: [{
                            label: 'Rata-rata Nilai',
                            data: @json($performanceChart['data']),
                            backgroundColor: '#85A832',
                            borderColor: '#637F26',
                            borderWidth: 1
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
            });
        </script>
    @endpush
@endsection
