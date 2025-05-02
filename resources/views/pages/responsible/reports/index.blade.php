@extends('layouts.auth')
@section('title', 'Laporan & Rekapitulasi')
@section('content')
<div class="min-h-screen bg-gray-100 p-6">
    <!-- Main Content Container -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <h1 class="text-xl font-semibold text-gray-800 mb-6">Laporan Rekapitulasi</h1>
        
        <!-- Filters -->
        <div class="grid grid-cols-3 gap-4 mb-6">
            <div>
                <div class="relative">
                    <select class="w-full h-10 pl-3 pr-8 border border-gray-300 rounded-md appearance-none focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option>Jurusan</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </div>
            </div>
            
            <div>
                <div class="relative">
                    <select class="w-full h-10 pl-3 pr-8 border border-gray-300 rounded-md appearance-none focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option>Tahun Angkatan</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </div>
            </div>
            
            <div>
                <div class="relative">
                    <select class="w-full h-10 pl-3 pr-8 border border-gray-300 rounded-md appearance-none focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option>Kampus</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Search Bar -->
        <div class="mb-6">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input type="text" placeholder="Search" class="w-full sm:w-64 h-10 pl-10 pr-4 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
        </div>
        
        <!-- Data Table -->
        <div class="overflow-x-auto mb-8">
            <table class="min-w-full">
                <thead>
                    <tr class="border-b border-gray-100">
                        <th class="py-3 px-4 font-medium">NIM</th>
                        <th class="py-3 px-4 font-medium">Nama</th>
                        <th class="py-3 px-4 font-medium">
                            <div class="flex items-center">
                                Kelas Magang
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </th>
                        <th class="py-3 px-4 font-medium">
                            <div class="flex items-center">
                                Jurusan
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </th>
                        <th class="py-3 px-4 font-medium">
                            <div class="flex items-center">
                                Kampus
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </th>
                        <th class="py-3 px-4 font-medium">
                            <div class="flex items-center">
                                Tahun Angkatan
                                <svg class="w-4 h-4 ml-1" fill="none" strotolong hapus line ke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </th>
                        <th class="py-3 px-4 font-medium">
                            <div class="flex items-center">
                                Status
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </th>
                        <th class="py-3 px-4 font-medium">
                            <div class="flex items-center">
                                Absen
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <!-- Row 1 -->
                    <tr class="hover:bg-gray-50">
                        <td class="py-3 px-4 text-sm">3.34.23.2.24</td>
                        <td class="py-3 px-4">
                            <div class="flex items-center">
                                <img class="h-8 w-8 rounded-full mr-3" src="/api/placeholder/32/32" alt="Profile">
                                <span class="text-sm">Vinsensius Kurnia Putra</span>
                            </div>
                        </td>
                        <td class="py-3 px-4 text-sm">FK - 01</td>
                        <td class="py-3 px-4 text-sm">Informatika</td>
                        <td class="py-3 px-4 text-sm">Politeknik Negeri Semarang</td>
                        <td class="py-3 px-4 text-sm">2025/2026</td>
                        <td class="py-3 px-4 text-sm">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                Non Active
                            </span>
                        </td>
                        <td class="py-3 px-4 text-sm">80%</td>
                    </tr>
                    
                    <!-- Row 2 -->
                    <tr class="hover:bg-gray-50">
                        <td class="py-3 px-4 text-sm">3.34.23.2.24</td>
                        <td class="py-3 px-4">
                            <div class="flex items-center">
                                <img class="h-8 w-8 rounded-full mr-3" src="/api/placeholder/32/32" alt="Profile">
                                <span class="text-sm">James N. McKinley</span>
                            </div>
                        </td>
                        <td class="py-3 px-4 text-sm">FK - 01</td>
                        <td class="py-3 px-4 text-sm">Kedokteran</td>
                        <td class="py-3 px-4 text-sm">Universitas Diponegoro</td>
                        <td class="py-3 px-4 text-sm">2025/2026</td>
                        <td class="py-3 px-4 text-sm">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Active
                            </span>
                        </td>
                        <td class="py-3 px-4 text-sm">80%</td>
                    </tr>
                    
                    <!-- Row 3 -->
                    <tr class="hover:bg-gray-50">
                        <td class="py-3 px-4 text-sm">3.34.23.2.24</td>
                        <td class="py-3 px-4">
                            <div class="flex items-center">
                                <img class="h-8 w-8 rounded-full mr-3" src="/api/placeholder/32/32" alt="Profile">
                                <span class="text-sm">James N. McKinley</span>
                            </div>
                        </td>
                        <td class="py-3 px-4 text-sm">FK - 01</td>
                        <td class="py-3 px-4 text-sm">Kedokteran</td>
                        <td class="py-3 px-4 text-sm">Universitas Diponegoro</td>
                        <td class="py-3 px-4 text-sm">2025/2026</td>
                        <td class="py-3 px-4 text-sm">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Active
                            </span>
                        </td>
                        <td class="py-3 px-4 text-sm">80%</td>
                    </tr>
                    
                    <!-- Row 4 -->
                    <tr class="hover:bg-gray-50">
                        <td class="py-3 px-4 text-sm">3.34.23.2.24</td>
                        <td class="py-3 px-4">
                            <div class="flex items-center">
                                <img class="h-8 w-8 rounded-full mr-3" src="/api/placeholder/32/32" alt="Profile">
                                <span class="text-sm">James N. McKinley</span>
                            </div>
                        </td>
                        <td class="py-3 px-4 text-sm">FK - 01</td>
                        <td class="py-3 px-4 text-sm">Kedokteran</td>
                        <td class="py-3 px-4 text-sm">Universitas Diponegoro</td>
                        <td class="py-3 px-4 text-sm">2025/2026</td>
                        <td class="py-3 px-4 text-sm">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Active
                            </span>
                        </td>
                        <td class="py-3 px-4 text-sm">80%</td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <!-- Bottom Sections -->
        <div class="grid grid-cols-2 gap-6">
            <!-- Left Column -->
            <div class="space-y-6">
                <!-- Nilai Rata-Rata Card -->
                <div class="bg-slate-50 rounded-xl p-6 shadow-sm">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Nilai Rata-Rata Mahasiswa</h2>
                    <div class="space-y-4">
                        <!-- Student 1 -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <img class="h-10 w-10 rounded-full mr-3" src="/api/placeholder/40/40" alt="Profile">
                                <div>
                                    <p class="text-sm font-medium">Chris Friedely</p>
                                    <p class="text-xs text-gray-500">Supermarket Vilanova</p>
                                </div>
                            </div>
                            <span class="text-lg font-medium">72</span>
                        </div>
                        
                        <!-- Student 2 (highlighted) -->
                        <div class="flex items-center justify-between bg-green-50 p-3 rounded-lg">
                            <div class="flex items-center">
                                <img class="h-10 w-10 rounded-full mr-3" src="/api/placeholder/40/40" alt="Profile">
                                <div>
                                    <p class="text-sm font-medium">Maggie Johnson</p>
                                    <p class="text-xs text-gray-500">Oasis Organic Inc.</p>
                                </div>
                            </div>
                            <span class="text-lg font-medium">72</span>
                        </div>
                        
                        <!-- Student 3 -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <img class="h-10 w-10 rounded-full mr-3" src="/api/placeholder/40/40" alt="Profile">
                                <div>
                                    <p class="text-sm font-medium">Gael Harry</p>
                                    <p class="text-xs text-gray-500">New York Finest Fruits</p>
                                </div>
                            </div>
                            <span class="text-lg font-medium">72</span>
                        </div>
                        
                        <!-- Student 4 -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <img class="h-10 w-10 rounded-full mr-3" src="/api/placeholder/40/40" alt="Profile">
                                <div>
                                    <p class="text-sm font-medium">Jenna Sullivan</p>
                                    <p class="text-xs text-gray-500">Walmart</p>
                                </div>
                            </div>
                            <span class="text-lg font-medium">72</span>
                        </div>
                        
                        <!-- See More Link -->
                        <div class="pt-2">
                            <a href="#" class="text-sm text-blue-600 hover:underline flex items-center">
                                Lihat semua
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Performa Terbaik Card -->
                <div class="bg-slate-50 rounded-xl p-6 shadow-sm">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Mahasiswa Dengan Performa Terbaik</h2>
                    
                    <!-- Yellow Header with updated styling -->
                    <div class="bg-amber-100 text-amber-800 text-sm font-medium px-4 py-2 rounded-lg mb-4">
                        YEAR 2025 | Stase Bedah
                    </div>
                    
                    <!-- Top Performers Table -->
                    <table class="min-w-full">
                        <thead>
                            <tr>
                                <th class="py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                <th class="py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mahasiswa</th>
                                <th class="py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Score</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <!-- Row 1 -->
                            <tr>
                                <td class="py-3 text-sm">1</td>
                                <td class="py-3">
                                    <div class="flex items-center">
                                        <img class="h-8 w-8 rounded-full mr-3" src="/api/placeholder/32/32" alt="Profile">
                                        <span class="text-sm">Jenice Salim</span>
                                    </div>
                                </td>
                                <td class="py-3 text-right text-sm">100</td>
                            </tr>
                            
                            <!-- Row 2 -->
                            <tr>
                                <td class="py-3 text-sm">2</td>
                                <td class="py-3">
                                    <div class="flex items-center">
                                        <img class="h-8 w-8 rounded-full mr-3" src="/api/placeholder/32/32" alt="Profile">
                                        <span class="text-sm">Vinsensius Kurnia P</span>
                                    </div>
                                </td>
                                <td class="py-3 text-right text-sm">98</td>
                            </tr>
                            
                            <!-- Row 3 -->
                            <tr>
                                <td class="py-3 text-sm">3</td>
                                <td class="py-3">
                                    <div class="flex items-center">
                                        <img class="h-8 w-8 rounded-full mr-3" src="/api/placeholder/32/32" alt="Profile">
                                        <span class="text-sm">James N. McKinley</span>
                                    </div>
                                </td>
                                <td class="py-3 text-right text-sm">96</td>
                            </tr>
                            
                            <!-- Row 4 -->
                            <tr>
                                <td class="py-3 text-sm">4</td>
                                <td class="py-3">
                                    <div class="flex items-center">
                                        <img class="h-8 w-8 rounded-full mr-3" src="/api/placeholder/32/32" alt="Profile">
                                        <span class="text-sm">Steven Jayadi</span>
                                    </div>
                                </td>
                                <td class="py-3 text-right text-sm">96</td>
                            </tr>
                            
                            <!-- Row 5 -->
                            <tr>
                                <td class="py-3 text-sm">5</td>
                                <td class="py-3">
                                    <div class="flex items-center">
                                        <img class="h-8 w-8 rounded-full mr-3" src="/api/placeholder/32/32" alt="Profile">
                                        <span class="text-sm">Jessica Wulan</span>
                                    </div>
                                </td>
                                <td class="py-3 text-right text-sm">90</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Right Column -->
            <div class="space-y-6">
                <!-- Chart Card -->
                <div class="bg-slate-50 rounded-xl p-6 shadow-sm">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-semibold text-gray-800">Grafik Rata-Rata Nilai Mahasiswa</h2>
                        <a href="#" class="text-teal-600 hover:text-teal-700 text-sm font-medium flex items-center">
                            Lihat Details
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                            </svg>
                        </a>
                    </div>
                    
                    <!-- Circular Progress Chart with updated colors -->
                    <div class="flex justify-center">
                        <div class="relative w-48 h-48">
                            <svg class="w-full h-full" viewBox="0 0 120 120">
                                <circle cx="60" cy="60" r="54" fill="none" stroke="#e2e8f0" stroke-width="12"/>
                                <circle cx="60" cy="60" r="54" fill="none" stroke="#0d9488" stroke-width="12"
                                        stroke-linecap="round" stroke-dasharray="339.292" stroke-dashoffset="95"
                                        transform="rotate(-90 60 60)"/>
                            </svg>
                            <div class="absolute inset-0 flex flex-col items-center justify-center">
                                <span class="text-xs text-gray-500">Completed</span>
                                <span class="text-3xl font-bold text-gray-800">72%</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Download Report Card -->
                <div class="bg-slate-50 rounded-xl p-6 shadow-sm">
                    <p class="text-sm text-gray-600 mb-4 text-center">
                        Unduh laporan rekapitulasi mahasiswa dibawah ini
                    </p>
                    <button class="w-full bg-teal-500 hover:bg-teal-600 text-white font-medium py-2.5 px-6 rounded-lg flex items-center justify-center transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        Unduh Rekapitulasi
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer with updated styling -->
    <div class="text-center text-sm text-gray-500">
        @2025 IK Polines
    </div>
</div>

<!-- Update style section -->
<style>
    body {
        background-color: #F1F5F9;  /* Lighter slate background */
    }
    
    .bg-slate-50 {
        background-color: #FFFFFF;  /* Pure white for cards */
    }
    
    /* Table styling */
    .divide-y > tr {
        border-color: #F8FAFC;
    }
    
    th {
        border: none;
        color: #64748B;
        font-weight: 500;
    }
    
    td {
        border: none;
    }
    
    tr:hover {
        background-color: #F8FAFC;
    }
    
    /* Card styling */
    .shadow-sm {
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    }
    
    /* Button styling */
    .hover\:bg-teal-600:hover {
        background-color: #0D9488;
    }
    
    .bg-teal-500 {
        background-color: #14B8A6;
    }
    
    /* Status badges */
    .bg-yellow-100 {
        background-color: #FEF9C3;
    }
    
    .bg-green-100 {
        background-color: #DCFCE7;
    }
    
    .text-yellow-800 {
        color: #854D0E;
    }
    
    .text-green-800 {
        color: #166534;
    }
</style>
@endsection