@extends('layouts.auth')

@section('title', 'Nilai Mahasiswa')

@section('content')
<div class="min-h-screen bg-gray-50 py-8 px-4">
    <div class="max-w-6xl mx-auto">
        <!-- Header Section -->
        <div class="bg-white rounded-lg shadow-sm mb-8 p-6">
            <div class="border-b border-gray-200 pb-4 mb-6">
                <h1 class="text-2xl font-semibold text-gray-900">Nilai Mahasiswa</h1>
                <p class="text-sm text-gray-600 mt-1">Menampilkan rekap nilai akhir mahasiswa selama periode rotasi klinik.</p>
            </div>
            
            <!-- Student Info Section -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                    <input type="text" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-gray-900" value="{{ auth()->user()->name }}" readonly>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Induk Mahasiswa</label>
                    <input type="text" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-gray-900" value="{{ auth()->user()->student->nim ?? '-' }}" readonly>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Asal Kampus</label>
                    <input type="text" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-gray-900" value="{{ auth()->user()->student->studyProgram->campus->name ?? '-' }}" readonly>
                </div>
            </div>
        </div>
        
        <!-- Grades Section -->
        <div class="space-y-6">
            <div class="flex items-center space-x-3 mb-6">
                <div class="w-1 h-6 bg-green-600 rounded-full"></div>
                <h2 class="text-xl font-semibold text-green-700">Detail Nilai Mahasiswa</h2>
            </div>
            
            @php
                $totalOverallAverage = 0;
                $staseCount = 0;
                $hasGrades = false;
            @endphp
            
            @forelse($grades->groupBy('stase_id') as $staseId => $staseGrades)
                @php
                    $stase = $staseGrades->first()->stase;
                    $studentGrade = $staseGrades->first();
                    
                    // Get grade components for this stase
                    $gradeComponents = \App\Models\GradeComponent::where('stase_id', $staseId)->get();
                    
                    // Get component grades for this student and stase
                    $componentGrades = \App\Models\StudentComponentGrade::where('student_id', auth()->user()->student->id)
                        ->where('stase_id', $staseId)
                        ->get()
                        ->keyBy('grade_component_id');
                    
                    $hasGrades = true;
                @endphp
                
                <!-- Stase Card -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <!-- Stase Header -->
                    <div class="bg-green-50 px-6 py-4 border-b border-green-100">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-2 sm:space-y-0">
                            <h3 class="text-lg font-semibold text-green-800">{{ $stase->name ?? 'N/A' }}</h3>
                            <div class="flex flex-col sm:flex-row sm:items-center space-y-1 sm:space-y-0 sm:space-x-4 text-sm text-gray-600">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                    </svg>
                                    PJ: 
                                    @php
                                        $pjName = 'N/A';
                                        if ($stase && isset($stase->responsibleUsers) && $stase->responsibleUsers->isNotEmpty()) {
                                            $responsibleUser = $stase->responsibleUsers->first();
                                            if ($responsibleUser && isset($responsibleUser->user)) {
                                                $pjName = $responsibleUser->user->name;
                                            }
                                        }
                                    @endphp
                                    {{ $pjName }}
                                </span>
                                @if(isset($studentGrade->updated_at))
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                    </svg>
                                    Diperbaharui: {{ \Carbon\Carbon::parse($studentGrade->updated_at)->format('d M Y, H:i') }}
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <!-- Stase Table -->
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    @foreach($gradeComponents as $component)
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ $component->name }}
                                        </th>
                                    @endforeach
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Rata-Rata Stase
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr class="hover:bg-gray-50">
                                    @foreach($gradeComponents as $component)
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center font-medium text-gray-900">
                                            @if(isset($componentGrades[$component->id]))
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                                    {{ number_format($componentGrades[$component->id]->value, 1) }}
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-500">
                                                    -
                                                </span>
                                            @endif
                                        </td>
                                    @endforeach
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center font-bold">
                                        @if($studentGrade->avg_grades && $studentGrade->avg_grades > 0)
                                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-green-100 text-green-800">
                                                {{ number_format($studentGrade->avg_grades, 1) }}
                                            </span>
                                            @php
                                                $totalOverallAverage += $studentGrade->avg_grades;
                                                $staseCount++;
                                            @endphp
                                        @else
                                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-gray-100 text-gray-500">
                                                -
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada nilai</h3>
                    <p class="mt-1 text-sm text-gray-500">Belum ada nilai yang tersedia untuk ditampilkan.</p>
                </div>
            @endforelse
            
            <!-- Simple Summary Card -->
            @if($hasGrades && $staseCount > 0)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden mt-8">
                    <!-- Header -->
                    <div class="bg-green-600 px-6 py-4">
                        <h3 class="text-lg font-semibold text-white">Ringkasan Nilai Keseluruhan</h3>
                    </div>
                    
                    <!-- Content -->
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                            <!-- Total Average -->
                            <div class="text-center">
                                <div class="text-3xl font-bold text-green-600 mb-2">
                                    {{ number_format($totalOverallAverage / $staseCount, 1) }}
                                </div>
                                <div class="text-sm text-gray-600">Rata-Rata Total</div>
                            </div>
                            
                            <!-- Completed Stases -->
                            <div class="text-center">
                                <div class="text-3xl font-bold text-blue-600 mb-2">{{ $staseCount }}</div>
                                <div class="text-sm text-gray-600">Stase Selesai</div>
                            </div>
                            
                            <!-- Total Stases -->
                            <div class="text-center">
                                <div class="text-3xl font-bold text-purple-600 mb-2">{{ $grades->groupBy('stase_id')->count() }}</div>
                                <div class="text-sm text-gray-600">Total Stase</div>
                            </div>
                            
                            <!-- Performance -->
                            <div class="text-center">
                                @php
                                    $avgScore = $totalOverallAverage / $staseCount;
                                    $performance = '';
                                    $performanceColor = '';
                                    if ($avgScore >= 85) {
                                        $performance = 'Sangat Baik';
                                        $performanceColor = 'text-green-600';
                                    } elseif ($avgScore >= 75) {
                                        $performance = 'Baik';
                                        $performanceColor = 'text-blue-600';
                                    } elseif ($avgScore >= 65) {
                                        $performance = 'Cukup';
                                        $performanceColor = 'text-yellow-600';
                                    } else {
                                        $performance = 'Perlu Perbaikan';
                                        $performanceColor = 'text-red-600';
                                    }
                                @endphp
                                <div class="text-lg font-bold {{ $performanceColor }} mb-2">{{ $performance }}</div>
                                <div class="text-sm text-gray-600">Kategori Nilai</div>
                            </div>
                        </div>
                        
                        <!-- Progress Bar -->
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <div class="flex justify-between text-sm text-gray-600 mb-2">
                                <span>Progress Evaluasi</span>
                                <span>{{ number_format(($staseCount / $grades->groupBy('stase_id')->count()) * 100, 0) }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-green-600 h-2 rounded-full" style="width: {{ ($staseCount / $grades->groupBy('stase_id')->count()) * 100 }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            @elseif($hasGrades)
                <!-- Jika ada grades tapi tidak ada yang punya avg_grades -->
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 text-center">
                    <svg class="mx-auto h-8 w-8 text-yellow-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.864-.833-2.634 0L3.098 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                    <h3 class="text-lg font-semibold text-yellow-800 mb-2">Evaluasi Dalam Proses</h3>
                    <p class="text-yellow-700">Nilai rata-rata akan tersedia setelah semua komponen dinilai.</p>
                </div>
            @endif
            
            <!-- Export Button -->
            @if($hasGrades)
            <div class="flex justify-end pt-6">
                <button onclick="printGrades()" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-green-600 hover:bg-green-700 transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Ekspor ke PDF
                </button>
            </div>
            @endif
        </div>

        <!-- Hidden Print Template -->
        <div class="print-only-template hidden">
            <div class="flex justify-between items-center mb-6 pb-4 border-b">
                <div class="text-sm text-gray-500">{{ now()->format('d/m/Y, H:i') }}</div>
                <div class="text-lg font-semibold">Nilai Mahasiswa</div>
            </div>
            
            <div class="text-center mb-8">
                <div class="text-2xl font-bold text-green-700 mb-2">🏥 Hospital Intern</div>
                <h1 class="text-xl font-bold mb-2">Detail Nilai Mahasiswa</h1>
                <p class="text-gray-600">Menampilkan rekap nilai akhir mahasiswa selama periode rotasi klinik.</p>
            </div>
            
            <div class="bg-gray-50 p-4 rounded mb-6">
                <div class="grid grid-cols-3 gap-4 text-sm">
                    <div><strong>Nama:</strong> {{ auth()->user()->name }}</div>
                    <div><strong>NIM:</strong> {{ auth()->user()->student->nim ?? '-' }}</div>
                    <div><strong>Kampus:</strong> {{ auth()->user()->student->studyProgram->campus->name ?? '-' }}</div>
                </div>
            </div>
            
            @foreach($grades->groupBy('stase_id') as $staseId => $staseGrades)
                @php
                    $stase = $staseGrades->first()->stase;
                    $studentGrade = $staseGrades->first();
                    $gradeComponents = \App\Models\GradeComponent::where('stase_id', $staseId)->get();
                    $componentGrades = \App\Models\StudentComponentGrade::where('student_id', auth()->user()->student->id)
                        ->where('stase_id', $staseId)
                        ->get()
                        ->keyBy('grade_component_id');
                @endphp
                
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-green-700 mb-3 bg-green-50 p-3 rounded">{{ $stase->name ?? 'N/A' }}</h3>
                    
                    <table class="w-full border-collapse border border-gray-300 mb-4">
                        <thead>
                            <tr class="bg-gray-100">
                                @foreach($gradeComponents as $component)
                                    <th class="border border-gray-300 p-2 text-sm">{{ $component->name }}</th>
                                @endforeach
                                <th class="border border-gray-300 p-2 text-sm font-bold">Rata-Rata Stase</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                @foreach($gradeComponents as $component)
                                    <td class="border border-gray-300 p-2 text-center">
                                        {{ isset($componentGrades[$component->id]) ? number_format($componentGrades[$component->id]->value, 1) : '-' }}
                                    </td>
                                @endforeach
                                <td class="border border-gray-300 p-2 text-center font-bold bg-green-50">
                                    {{ $studentGrade->avg_grades ? number_format($studentGrade->avg_grades, 1) : '-' }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            @endforeach
            
            @if($staseCount > 0)
                <div class="text-center text-lg font-bold bg-green-100 p-4 rounded mt-6">
                    Total Rata-Rata Keseluruhan: {{ number_format($totalOverallAverage / $staseCount, 1) }}
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    function printGrades() {
        const printTemplate = document.querySelector('.print-only-template');
        if (printTemplate) {
            printTemplate.classList.remove('hidden');
        }
        
        let originalTitle = document.title;
        document.title = "Detail Nilai Mahasiswa";
        
        setTimeout(function() {
            window.print();
            
            setTimeout(function() {
                document.title = originalTitle;
                if (printTemplate) {
                    printTemplate.classList.add('hidden');
                }
            }, 500);
        }, 300);
    }
</script>

<style>
@media print {
    body * {
        visibility: hidden;
    }
    .print-only-template, .print-only-template * {
        visibility: visible;
    }
    .print-only-template {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        padding: 20px;
        background: white;
    }
    @page {
        margin: 10mm;
    }
}
</style>
@endsection