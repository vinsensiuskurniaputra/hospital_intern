@extends('layouts.auth')

@section('title', 'Presensi & Sertifikasi')

@section('content')
<div class="container-fluid py-4">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">
        <!-- Presensi Card -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex flex-col">
                <h5 class="text-lg font-medium mb-4">Presensi</h5>
                <div class="mb-4">
                    <span class="px-3 py-1.5 bg-yellow-100 text-yellow-800 rounded-full text-sm">
                        Belum Presensi
                    </span>
                </div>
                <div class="flex">
                    <input type="text" 
                        placeholder="Masukkan Kode Presensi" 
                        class="flex-1 rounded-l-lg border border-gray-300 px-4 py-2 focus:ring-0 focus:border-[#637F26]">
                    <button class="px-6 py-2 bg-[#637F26] text-white rounded-r-lg hover:bg-[#4B601C] transition-colors">
                        Submit
                    </button>
                </div>
            </div>
        </div>

        <!-- Total Kehadiran Card -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h5 class="text-lg font-medium mb-4">Total Kehadiran</h5>
            <div class="flex justify-between items-center">
                <div class="text-2xl font-medium">1000 Kehadiran</div>
                <div class="w-32">
                    <div id="attendanceChart"></div>
                    <div class="grid grid-cols-3 gap-2 text-xs mt-2">
                        <div class="flex items-center">
                            <span class="w-2 h-2 rounded-full bg-[#22c55e] mr-1"></span>
                            <span>52.1%</span>
                        </div>
                        <div class="flex items-center">
                            <span class="w-2 h-2 rounded-full bg-[#3b82f6] mr-1"></span>
                            <span>22.8%</span>
                        </div>
                        <div class="flex items-center">
                            <span class="w-2 h-2 rounded-full bg-[#ef4444] mr-1"></span>
                            <span>15.9%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Kehadiran Mahasiswa & Calendar Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">
        <!-- Kehadiran Mahasiswa Card -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h5 class="text-lg font-medium mb-4">Kehadiran Mahasiswa</h5>
            <div class="flex items-center gap-4 mb-4">
                <div class="text-lg font-medium">1000 Kehadiran</div>
                <div class="flex items-center text-sm text-red-600">
                    <span class="inline-block w-2 h-2 rounded-full bg-red-500 mr-1"></span>
                    <span>-2.5%</span>
                </div>
                <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">Januari</span>
            </div>
            <div id="attendanceLineChart" class="w-full h-[180px]"></div>
        </div>

        <!-- Calendar Card -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex justify-between items-center mb-4">
                <select class="border-gray-300 rounded-lg text-sm">
                    <option>March</option>
                </select>
                <select class="border-gray-300 rounded-lg text-sm">
                    <option>2024</option>
                </select>
            </div>
            <div class="grid grid-cols-7 gap-1 text-center text-sm">
                <div class="py-1 text-gray-500">Su</div>
                <div class="py-1 text-gray-500">Mo</div>
                <div class="py-1 text-gray-500">Tu</div>
                <div class="py-1 text-gray-500">We</div>
                <div class="py-1 text-gray-500">Th</div>
                <div class="py-1 text-gray-500">Fr</div>
                <div class="py-1 text-gray-500">Sa</div>
                
                @foreach(range(1, 31) as $day)
                    <div class="py-1 @if($day == 10) bg-[#637F26] text-white rounded-full @endif">
                        {{ $day }}
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Sertifikasi Card -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-4">
        <div class="flex justify-between items-start">
            <div>
                <h5 class="text-lg font-medium mb-2">Sertifikasi</h5>
                <h6 class="text-base font-medium mb-3">Unduh Sertifikat</h6>
                <p class="text-gray-600 text-sm max-w-3xl">
                    Silahkan unduh sertifikat magang Anda sebagai bukti resmi telah menyelesaikan program magang. 
                    Sertifikat ini dapat digunakan untuk keperluan akademik, portfolio, atau kebutuhan profesional lainnya.
                </p>
            </div>
            <button class="px-4 py-2 bg-[#96D67F] text-gray-700 rounded-lg hover:bg-[#85c070] transition-colors">
                Unduh
            </button>
        </div>
    </div>

    <!-- Presensi Setiap Stase -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h5 class="text-lg font-medium mb-6">Presensi Setiap Stase</h5>
        <div class="space-y-4">
            <!-- Stase with 80% -->
            <div class="p-4 bg-green-50 rounded-lg">
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <h6 class="font-medium">Stase Dokter Umum</h6>
                        <p class="text-sm text-gray-500">Departemen Umum</p>
                        <p class="text-xs text-gray-400">1 Jan - 31 Maret 2025</p>
                    </div>
                    <span class="text-xl font-bold">80%</span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-2">
                    <div class="bg-[#637F26] h-2 rounded-full" style="width: 80%"></div>
                </div>
            </div>

            <!-- Stase with 0% -->
            <div class="p-4 rounded-lg">
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <h6 class="font-medium">Stase Dokter Gigi</h6>
                        <p class="text-sm text-gray-500">Departemen Umum</p>
                        <p class="text-xs text-gray-400">1 Apr - 30 Juni 2025</p>
                    </div>
                    <span class="text-xl font-bold">0%</span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-2">
                    <div class="bg-[#637F26] h-2 rounded-full" style="width: 0%"></div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    // Donut Chart Configuration
    var donutOptions = {
        series: [52.1, 22.8, 15.9],
        chart: {
            type: 'donut',
            height: 130,
            sparkline: {
                enabled: true
            }
        },
        colors: ['#22c55e', '#3b82f6', '#ef4444'],
        labels: ['Hadir', 'Izin', 'Alpha'],
        legend: {
            show: false
        },
        plotOptions: {
            pie: {
                donut: {
                    size: '75%'
                }
            }
        },
        stroke: {
            width: 2
        }
    };
    new ApexCharts(document.querySelector("#attendanceChart"), donutOptions).render();

    // Line Chart Configuration
    var lineOptions = {
        series: [{
            name: 'Kehadiran',
            data: [30, 40, 35, 50, 49, 60, 45]
        }],
        chart: {
            type: 'line',
            height: 180,
            toolbar: {
                show: false
            }
        },
        stroke: {
            curve: 'smooth',
            width: 3,
        },
        colors: ['#637F26'],
        xaxis: {
            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul']
        },
        grid: {
            borderColor: '#f1f1f1',
            strokeDashArray: 4
        },
        markers: {
            size: 5,
            colors: ['#637F26'],
            strokeColors: '#fff',
            strokeWidth: 2
        }
    };
    new ApexCharts(document.querySelector("#attendanceLineChart"), lineOptions).render();
</script>
@endpush
@endsection