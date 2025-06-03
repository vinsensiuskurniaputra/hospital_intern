@extends('layouts.auth')
@section('title', 'Laporan & Rekapitulasi')
@section('content')
<div class="min-h-screen bg-gray-100 p-6">
    <!-- Main Content Container -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <h1 class="text-xl font-semibold text-gray-800 mb-6">Laporan Rekapitulasi</h1>
        
        <!-- Filters -->
        <form id="filterForm" method="GET" action="{{ route('responsible.reports') }}" class="mb-6">
            <!-- Filter Container -->
            <div class="flex flex-wrap items-center gap-4">
                <!-- Search Bar -->
                <div class="flex-1 min-w-[200px]">
                    <input 
                        type="text" 
                        name="search" 
                        placeholder="Search" 
                        value="{{ request('search') }}" 
                        class="w-full px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg focus:outline-none focus:border-green-500"
                    >
                </div>

                <!-- Stase Dropdown -->
                <div class="w-48">
                    <select 
                        name="stase" 
                        id="stase" 
                        class="w-full px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg focus:outline-none focus:border-green-500 appearance-none"
                    >
                        @foreach($stases ?? [] as $staseItem)
                            <option value="{{ $staseItem->id }}" {{ request('stase') == $staseItem->id || ($staseItem->id == $stase->id && !request('stase')) ? 'selected' : '' }}>
                                {{ $staseItem->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Kelas Magang Dropdown -->
                <div class="w-48">
                    <select 
                        name="internship_class" 
                        id="internship_class" 
                        class="w-full px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg focus:outline-none focus:border-green-500 appearance-none"
                    >
                        <option value="">Kelas Magang</option>
                        @foreach($internshipClasses ?? [] as $class)
                            <option value="{{ $class->id }}" {{ request('internship_class') == $class->id ? 'selected' : '' }}>
                                {{ $class->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Tahun Angkatan Dropdown -->
                <div class="w-48">
                    <select 
                        name="class_year" 
                        id="class_year" 
                        class="w-full px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg focus:outline-none focus:border-green-500 appearance-none"
                    >
                        <option value="">Tahun Angkatan</option>
                        @foreach($classYears as $year)
                            <option value="{{ $year->id }}" {{ request('class_year') == $year->id ? 'selected' : '' }}>
                                {{ $year->class_year }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Apply Button -->
                <button 
                    type="submit" 
                    class="px-6 py-2 text-white bg-[#7E8B3C] rounded-lg hover:bg-[#697431] transition-colors duration-200"
                >
                    Terapkan
                </button>
            </div>
        </form>
        
        <!-- Data Table Section -->
        <div class="overflow-x-auto mb-8">
            <table class="min-w-full border-collapse bg-white">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="py-3 px-4 text-left text-sm font-medium text-gray-700">NIM</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-gray-700">Nama</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-gray-700">Kelas Magang</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-gray-700">Stase</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-gray-700">Jurusan</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-gray-700">Kampus</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-gray-700">Tahun Angkatan</th>
                        <th class="py-3 px-4 text-center text-sm font-medium text-gray-700">Presensi</th>
                        <th class="py-3 px-4 text-center text-sm font-medium text-gray-700">Nilai Rata-Rata</th> <!-- Tambahkan ini -->
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($students as $student)
                    <tr class="hover:bg-gray-50 transition duration-150">
                        <td class="py-4 px-4 text-sm text-gray-700">{{ $student->nim }}</td>
                        <td class="py-4 px-4">
                            <div class="flex items-center space-x-3">
                                <img 
                                    class="h-8 w-8 rounded-full object-cover" 
                                    src="{{ $student->user->photo_profile_url ?? '/api/placeholder/32/32' }}" 
                                    alt="Profile"
                                >
                                <span class="text-sm font-medium text-gray-900">{{ $student->user->name }}</span>
                            </div>
                        </td>
                        <td class="py-4 px-4 text-sm text-gray-700">{{ $student->internshipClass->name }}</td>
                        <td class="py-4 px-4 text-sm text-gray-700">{{ $staseName }}</td>
                        <td class="py-4 px-4 text-sm text-gray-700">{{ $student->studyProgram->name }}</td>
                        <td class="py-4 px-4 text-sm text-gray-700">{{ $student->studyProgram->campus->name }}</td>
                        <td class="py-4 px-4 text-sm text-gray-700">{{ $student->internshipClass->classYear->class_year }}</td>
                        <td class="py-4 px-4 text-sm text-center font-medium 
                            {{ $student->attendance_percentage >= 75 ? 'text-green-600' : 'text-red-600' }}">
                            {{ $student->attendance_percentage }}%
                        </td>
                        <td class="py-4 px-4 text-sm text-center font-medium">
                            {{ round($student->average_grade) }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Bottom Sections -->
        <div class="grid grid-cols-2 gap-6">
            <!-- Left Column -->
            <div class="space-y-6">
                
                
                <!-- Performa Terbaik Card -->
                <div class="bg-slate-50 rounded-xl p-6 shadow-sm">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Mahasiswa Dengan Performa Terbaik</h2>
                    
                    <!-- Yellow Header with updated styling -->
                    <div class="bg-amber-100 text-amber-800 text-sm font-medium px-4 py-2 rounded-lg mb-4">
                        Tahun {{ $currentYear }} | {{ $staseName }}
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
                            @foreach($topPerformers as $index => $performer)
                            <tr>
                                <td class="py-3 text-sm">{{ $index + 1 }}</td>
                                <td class="py-3">
                                    <div class="flex items-center">
                                        <img class="h-8 w-8 rounded-full mr-3" src="{{ $performer->student->user->photo_profile_url ?? '/api/placeholder/32/32' }}" alt="Profile">
                                        <span class="text-sm">{{ $performer->student->user->name }}</span>
                                    </div>
                                </td>
                                <td class="py-3 text-right text-sm">{{ round($performer->average_grade) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Right Column -->
                <div class="space-y-6">
                    <!-- Chart Card -->
                    <div class="bg-white rounded-xl p-6">
                        <h2 class="text-xl font-medium text-gray-800 mb-6">Grafik Nilai</h2>

                        <div class="flex items-start gap-16">
                            <!-- Donut Chart -->
                            <div class="relative" style="width: 200px; height: 200px;">
                                <!-- SVG Donut Chart -->
                                <svg class="w-full h-full -rotate-90" viewBox="0 0 100 100">
                                    @php
                                        $radius = 40;
                                        $circumference = 2 * pi() * $radius;

                                        // Use data from database
                                        $above90 = $gradePercentages['above90'];
                                        $above70 = $gradePercentages['above70'];
                                        $above50 = $gradePercentages['above50'];

                                        // Calculate strokes
                                        $stroke90 = ($above90 / 100) * $circumference;
                                        $stroke70 = ($above70 / 100) * $circumference;
                                        $stroke50 = ($above50 / 100) * $circumference;

                                        // Calculate offsets
                                        $offset90 = 0;
                                        $offset70 = $stroke90;
                                        $offset50 = $stroke90 + $stroke70;
                                    @endphp

                                    <!-- Background circle -->
                                    <circle cx="50" cy="50" r="{{ $radius }}" fill="none" stroke="#F3F4F6"
                                        stroke-width="18" />

                                    <!-- >90 Segment (Green) -->
                                    <circle cx="50" cy="50" r="{{ $radius }}" fill="none" stroke="#22C55E" stroke-width="18"
                                        stroke-dasharray="{{ $stroke90 }} {{ $circumference }}"
                                        stroke-dashoffset="{{ $offset90 }}" />

                                    <!-- >70 Segment (Blue) -->
                                    <circle cx="50" cy="50" r="{{ $radius }}" fill="none" stroke="#60A5FA" stroke-width="18"
                                        stroke-dasharray="{{ $stroke70 }} {{ $circumference }}"
                                        stroke-dashoffset="{{ -$offset70 }}" />

                                    <!-- >50 Segment (Red) -->
                                    <circle cx="50" cy="50" r="{{ $radius }}" fill="none" stroke="#EF4444" stroke-width="18"
                                        stroke-dasharray="{{ $stroke50 }} {{ $circumference }}"
                                        stroke-dashoffset="{{ -$offset50 }}" />
                                </svg>
                            </div>

                            <!-- Legend -->
                            <div class="flex flex-col gap-4 pt-4">
                                <div class="flex items-center gap-6">
                                    <div class="flex items-center gap-2">
                                        <div class="w-3 h-3 rounded-full bg-[#22C55E]"></div>
                                        <span class="text-sm text-gray-600">> 90</span>
                                    </div>
                                    <span class="text-sm font-medium text-gray-900">{{ $gradePercentages['above90'] }}%</span>
                                </div>
                                <div class="flex items-center gap-6">
                                    <div class="flex items-center gap-2">
                                        <div class="w-3 h-3 rounded-full bg-[#60A5FA]"></div>
                                        <span class="text-sm text-gray-600">70-89</span>
                                    </div>
                                    <span class="text-sm font-medium text-gray-900">{{ $gradePercentages['above70'] }}%</span>
                                </div>
                                <div class="flex items-center gap-6">
                                    <div class="flex items-center gap-2">
                                        <div class="w-3 h-3 rounded-full bg-[#EF4444]"></div>
                                        <span class="text-sm text-gray-600">50-69</span>
                                    </div>
                                    <span class="text-sm font-medium text-gray-900">{{ $gradePercentages['above50'] }}%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                
                <!-- Download Report Card -->
                <div class="bg-slate-50 rounded-xl p-6 shadow-sm">
                    <p class="text-sm text-gray-600 mb-4 text-center">
                        Unduh laporan rekapitulasi mahasiswa dibawah ini
                    </p>
                    <form method="GET" action="{{ route('responsible.reports.download-csv') }}">
                        <input type="hidden" name="stase" value="{{ $stase->id }}">
                        <button type="submit" class="w-full bg-teal-500 hover:bg-teal-600 text-white font-medium py-2.5 px-6 rounded-lg flex items-center justify-center transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                            </svg>
                            Unduh Rekapitulasi
                        </button>
                    </form>
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

    /* Custom select dropdown arrow */
    select {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
        background-position: right 0.5rem center;
        background-repeat: no-repeat;
        background-size: 1.5em 1.5em;
    }
</style>
@endsection