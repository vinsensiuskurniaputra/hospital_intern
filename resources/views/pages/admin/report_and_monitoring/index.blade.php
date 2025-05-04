@extends('layouts.auth')

@section('title', 'Report and Monitoring')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.min.css">
@endpush

@section('content')
    <div class="p-6 space-y-6">
        <!-- Header Section -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-800">Report & Monitoring</h1>
                    <p class="mt-1 text-sm text-gray-500">Monitor student performance and attendance</p>
                </div>
                <div class="flex gap-3">
                    <button
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-gray-50">
                        <i class="bi bi-filter mr-2"></i>Filter Data
                    </button>
                    <button class="px-4 py-2 text-sm font-medium text-white bg-[#637F26] rounded-lg hover:bg-[#85A832]">
                        <i class="bi bi-download mr-2"></i>Download Report
                    </button>
                </div>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Students</p>
                        <h3 class="text-2xl font-bold text-gray-800 mt-1">565</h3>
                    </div>
                    <div class="p-3 bg-[#F5F7F0] rounded-lg">
                        <i class="bi bi-people text-xl text-[#637F26]"></i>
                    </div>
                </div>
                <div class="mt-4 flex items-center gap-2">
                    <span class="text-xs bg-[#F5F7F0] text-[#637F26] px-2 py-1 rounded-full">2024</span>
                    <span class="text-xs text-green-600 flex items-center">
                        <i class="bi bi-arrow-up-right"></i>
                        <span class="ml-1">+12.5%</span>
                    </span>
                </div>
            </div>
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Doctors</p>
                        <h3 class="text-2xl font-bold text-gray-800 mt-1">54</h3>
                    </div>
                    <div class="p-3 bg-[#F5F7F0] rounded-lg">
                        <i class="bi bi-person-badge text-xl text-[#637F26]"></i>
                    </div>
                </div>
                <div class="mt-4 flex items-center gap-2">
                    <span class="text-xs bg-[#F5F7F0] text-[#637F26] px-2 py-1 rounded-full">2024</span>
                </div>
            </div>
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Admins</p>
                        <h3 class="text-2xl font-bold text-gray-800 mt-1">25</h3>
                    </div>
                    <div class="p-3 bg-[#F5F7F0] rounded-lg">
                        <i class="bi bi-person text-xl text-[#637F26]"></i>
                    </div>
                </div>
                <div class="mt-4 flex items-center gap-2">
                    <span class="text-xs bg-[#F5F7F0] text-[#637F26] px-2 py-1 rounded-full">2024</span>
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
                            <h3 class="text-lg font-semibold text-gray-800">Student Attendance</h3>
                            <p class="text-sm text-gray-500">Last 7 days attendance overview</p>
                        </div>
                        <select class="px-3 py-2 border border-gray-200 rounded-lg text-sm">
                            <option>Last 7 days</option>
                            <option>Last 30 days</option>
                            <option>Last 90 days</option>
                        </select>
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
                            <h3 class="text-lg font-semibold text-gray-800">Student Performance</h3>
                            <p class="text-sm text-gray-500">Average scores by department</p>
                        </div>
                        <select class="px-3 py-2 border border-gray-200 rounded-lg text-sm">
                            <option>All Departments</option>
                            <option>Surgery</option>
                            <option>Internal Medicine</option>
                        </select>
                    </div>
                </div>
                <div class="p-6">
                    <canvas id="performanceChart" height="300"></canvas>
                </div>
            </div>
        </div>

        <!-- Table Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="p-6 border-b border-gray-100">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">Internship Students</h3>
                        <p class="text-sm text-gray-500">Complete list of current internship students</p>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-3">
                        <div class="relative">
                            <input type="text" placeholder="Search students..."
                                class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#637F26]">
                            <i class="bi bi-search absolute left-3 top-2.5 text-gray-400"></i>
                        </div>
                        <select class="px-3 py-2 border border-gray-200 rounded-lg">
                            <option>All Departments</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead>
                        <tr class="bg-gray-100 text-gray-600">
                            <th class="py-3 px-4">Fakultas</th>
                            <th class="py-3 px-4">Kelompok</th>
                            <th class="py-3 px-4">Kode</th>
                            <th class="py-3 px-4">Jurusan</th>
                            <th class="py-3 px-4">Universitas</th>
                            <th class="py-3 px-4">Tahun</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700">
                        <tr class="border-b hover:bg-gray-50 transition-colors">
                            <td class="py-3 px-4">FK</td>
                            <td class="py-3 px-4 flex items-center gap-2">
                                <img src="https://via.placeholder.com/24" class="w-6 h-6 rounded-full" alt="Kelompok 1">
                                KELOMPOK 1
                            </td>
                            <td class="py-3 px-4">FK - 01</td>
                            <td class="py-3 px-4">Informatika</td>
                            <td class="py-3 px-4">Politeknik Negeri Sematang</td>
                            <td class="py-3 px-4">2025/2026</td>
                            <td class="py-3 px-4">
                                <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs"
                                    title="Currently studying">On Study</span>
                            </td>
                            <td class="py-3 px-4 space-x-2">
                                <button class="text-blue-500 hover:text-blue-700 flex items-center gap-1">
                                    ✏️ <span>Edit</span>
                                </button>
                                <button class="text-red-500 hover:text-red-700 flex items-center gap-1">
                                    🗑️ <span>Delete</span>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Enhanced Pagination -->
            <div class="px-6 py-4 border-t border-gray-100">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <p class="text-sm text-gray-500">
                        Showing <span class="font-medium">1</span> to <span class="font-medium">10</span> of
                        <span class="font-medium">50</span> results
                    </p>
                    <div class="flex items-center gap-2">
                        <button class="px-3 py-1 text-sm text-gray-500 hover:text-[#637F26]">Previous</button>
                        <div class="flex gap-1">
                            <button class="px-3 py-1 text-sm font-medium text-white bg-[#637F26] rounded-lg">1</button>
                            <button class="px-3 py-1 text-sm text-gray-700 hover:bg-gray-100 rounded-lg">2</button>
                            <button class="px-3 py-1 text-sm text-gray-700 hover:bg-gray-100 rounded-lg">3</button>
                        </div>
                        <button class="px-3 py-1 text-sm text-gray-500 hover:text-[#637F26]">Next</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Enhanced chart configurations
                const attendanceChart = new Chart(document.getElementById('attendanceChart'), {
                    type: 'line',
                    data: {
                        labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                        datasets: [{
                            label: 'Attendance',
                            data: [95, 88, 92, 85, 89, 90, 88],
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
                                beginAtZero: false,
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

                const performanceChart = new Chart(document.getElementById('performanceChart'), {
                    type: 'bar',
                    data: {
                        labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                        datasets: [{
                            label: 'Performance',
                            data: [80, 85, 90, 88, 92, 87, 89],
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
