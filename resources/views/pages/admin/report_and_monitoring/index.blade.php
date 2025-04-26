@extends('layouts.auth')

@section('title', 'Report and Monitoring')

@section('content')
<div class="p-6 space-y-6">
    <!-- Header Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white p-6 rounded-2xl shadow hover:shadow-lg transition-shadow">
            <h2 class="text-sm font-medium text-gray-600">Total Mahasiswa</h2>
            <p class="text-3xl font-bold text-gray-800 mt-2">565</p>
            <span class="text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded">YEAR 2024</span>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow hover:shadow-lg transition-shadow">
            <h2 class="text-sm font-medium text-gray-600">Total Dokter</h2>
            <p class="text-3xl font-bold text-gray-800 mt-2">54</p>
            <span class="text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded">YEAR 2024</span>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow hover:shadow-lg transition-shadow">
            <h2 class="text-sm font-medium text-gray-600">Total Admin</h2>
            <p class="text-3xl font-bold text-gray-800 mt-2">25</p>
            <span class="text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded">YEAR 2024</span>
        </div>
    </div>

    <!-- Kehadiran dan Penilaian -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Kehadiran -->
        <div class="bg-white p-6 rounded-2xl shadow hover:shadow-lg transition-shadow">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-base font-semibold text-gray-800">Kehadiran Mahasiswa</h3>
                <span class="text-sm text-gray-500">1 Minggu Terakhir</span>
            </div>
            <p class="text-xl font-bold text-gray-800">X Kehadiran</p>
            <p class="text-sm text-red-500 mt-1">Senin ↓ 2.5%</p>
            <div class="mt-4 h-48 bg-gray-100 rounded">
                <!-- Chart Canvas for Attendance -->
                <canvas id="attendanceChart"></canvas>
            </div>
        </div>

        <!-- Penilaian -->
        <div class="bg-white p-6 rounded-2xl shadow hover:shadow-lg transition-shadow">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-base font-semibold text-gray-800">Penilaian Kinerja Mahasiswa</h3>
                <span class="text-sm text-gray-500">1 Minggu Terakhir</span>
            </div>
            <div class="mt-4 h-48 bg-gray-100 rounded">
                <!-- Chart Canvas for Performance -->
                <canvas id="performanceChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Tabel Mahasiswa Magang -->
    <div class="bg-white p-6 rounded-2xl shadow">
        <h3 class="text-base font-semibold text-gray-800 mb-4">Jumlah Mahasiswa Magang (Department)</h3>
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
                            <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs" title="Currently studying">On Study</span>
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
                    <!-- Add more rows as needed -->
                </tbody>
            </table>
        </div>
        <div class="mt-4 flex justify-between items-center">
            <span class="text-sm text-gray-500">Showing 1 to 10 of 50 entries</span>
            <div class="flex items-center space-x-2">
                <button class="bg-gray-100 text-gray-800 px-3 py-1 rounded hover:bg-gray-200">Previous</button>
                <button class="bg-gray-100 text-gray-800 px-3 py-1 rounded hover:bg-gray-200">Next</button>
            </div>
        </div>
        <div class="mt-4 flex justify-end">
            <button class="bg-green-500 text-white font-medium px-4 py-2 rounded hover:bg-green-600">
                Download Laporan
            </button>
        </div>
    </div>
</div>

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Chart Initialization -->
<script>
    // Attendance Chart
    const attendanceCtx = document.getElementById('attendanceChart').getContext('2d');
    new Chart(attendanceCtx, {
        type: 'line',
        data: {
            labels: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],
            datasets: [{
                label: 'Attendance',
                data: [12, 19, 3, 5, 2],
                backgroundColor: 'rgba(99, 127, 38, 0.2)',
                borderColor: 'rgba(99, 127, 38, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Performance Chart
    const performanceCtx = document.getElementById('performanceChart').getContext('2d');
    new Chart(performanceCtx, {
        type: 'bar',
        data: {
            labels: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],
            datasets: [{
                label: 'Performance',
                data: [8, 15, 10, 12, 7],
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endsection