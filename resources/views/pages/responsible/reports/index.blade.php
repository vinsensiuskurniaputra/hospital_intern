@extends('layouts.auth')
@section('title', 'Laporan & Rekapitulasi')
@section('content')
<div class="min-h-screen bg-gray-100 p-6">
    <!-- Main Content Container -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <h1 class="text-xl font-semibold text-gray-800 mb-6">Laporan Rekapitulasi</h1>
        
        <!-- Filters -->
        <form id="filterForm" class="grid grid-cols-3 gap-4 mb-6">
            <div>
                <div class="relative">
                    <select name="study_program" id="study_program" class="w-full h-10 pl-3 pr-8 border border-gray-300 rounded-md appearance-none focus:outline-none focus:ring-2 focus:ring-blue-500" onchange="submitForm()">
                        <option value="">Semua Jurusan</option>
                        @foreach($studyPrograms as $program)
                            <option value="{{ $program->id }}" {{ request('study_program') == $program->id ? 'selected' : '' }}>
                                {{ $program->name }}
                            </option>
                        @endforeach
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
                    <select name="class_year" id="class_year" class="w-full h-10 pl-3 pr-8 border border-gray-300 rounded-md appearance-none focus:outline-none focus:ring-2 focus:ring-blue-500" onchange="submitForm()">
                        <option value="">Semua Tahun Angkatan</option>
                        @foreach($classYears as $year)
                            <option value="{{ $year->id }}" {{ request('class_year') == $year->id ? 'selected' : '' }}>
                                {{ $year->class_year }}
                            </option>
                        @endforeach
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
                    <select name="campus" id="campus" class="w-full h-10 pl-3 pr-8 border border-gray-300 rounded-md appearance-none focus:outline-none focus:ring-2 focus:ring-blue-500" onchange="submitForm()">
                        <option value="">Semua Kampus</option>
                        @foreach($campuses as $campus)
                            <option value="{{ $campus->id }}" {{ request('campus') == $campus->id ? 'selected' : '' }}>
                                {{ $campus->name }}
                            </option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </form>
        
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
                        <th class="py-3 px-4 font-medium text-center">NIM</th>
                        <th class="py-3 px-4 font-medium text-left">Nama</th>
                        <th class="py-3 px-4 font-medium text-center">
                            <div class="flex items-center justify-center">
                                Kelas Magang
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </th>
                        <th class="py-3 px-4 font-medium text-center">
                            <div class="flex items-center justify-center">
                                Jurusan
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </th>
                        <th class="py-3 px-4 font-medium text-center">
                            <div class="flex items-center justify-center">
                                Kampus
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </th>
                        <th class="py-3 px-4 font-medium text-center">
                            <div class="flex items-center justify-center">
                                Tahun Angkatan
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </th>
                        <th class="py-3 px-4 font-medium text-center">
                            <div class="flex items-center justify-center">
                                Status
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </th>
                        <th class="py-3 px-4 font-medium text-center">
                            <div class="flex items-center justify-center">
                                Absen
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($students as $student)
                    <tr class="hover:bg-gray-50">
                        <td class="py-3 px-4 text-sm text-center">{{ $student->nim }}</td>
                        <td class="py-3 px-4 text-left">
                            <div class="flex items-center">
                                <img class="h-8 w-8 rounded-full mr-3" src="{{ $student->user->photo_profile_url ?? '/api/placeholder/32/32' }}" alt="Profile">
                                <span class="text-sm">{{ $student->user->name }}</span>
                            </div>
                        </td>
                        <td class="py-3 px-4 text-sm text-center">{{ $student->internshipClass->name }}</td>
                        <td class="py-3 px-4 text-sm text-center">{{ $student->studyProgram->name }}</td>
                        <td class="py-3 px-4 text-sm text-center">{{ $student->studyProgram->campus->name }}</td>
                        <td class="py-3 px-4 text-sm text-center">{{ $student->internshipClass->classYear->class_year }}</td>
                        <td class="py-3 px-4 text-sm text-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $student->is_finished ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                {{ $student->is_finished ? 'Non Active' : 'Active' }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-sm text-center">{{ $student->attendance_percentage }}%</td>
                    </tr>
                    @endforeach
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
                        @foreach($averageGrades as $grade)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <img class="h-10 w-10 rounded-full mr-3" src="{{ $grade->student->user->photo_profile_url ?? '/api/placeholder/40/40' }}" alt="Profile">
                                <div>
                                    <p class="text-sm font-medium">{{ $grade->student->user->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $grade->student->studyProgram->campus->name }}</p>
                                </div>
                            </div>
                            <span class="text-lg font-medium">{{ round($grade->average_grade) }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                
                <!-- Performa Terbaik Card -->
                <div class="bg-slate-50 rounded-xl p-6 shadow-sm">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Mahasiswa Dengan Performa Terbaik</h2>
                    
                    <!-- Yellow Header with updated styling -->
                    <div class="bg-amber-100 text-amber-800 text-sm font-medium px-4 py-2 rounded-lg mb-4">
                        Tahun 2025 | Stase Gigi
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
                                        stroke-linecap="round" 
                                        stroke-dasharray="339.292" 
                                        stroke-dashoffset="{{ 339.292 * (1 - ($overallAverage/100)) }}"
                                        transform="rotate(-90 60 60)"/>
                            </svg>
                            <div class="absolute inset-0 flex flex-col items-center justify-center">
                                <span class="text-xs text-gray-500">Average Grade</span>
                                <span class="text-3xl font-bold text-gray-800">{{ round($overallAverage) }}%</span>
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