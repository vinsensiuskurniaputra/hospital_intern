@extends('layouts.auth')

@section('title', 'Penilaian Mahasiswa')

@section('content')
<div class="container-fluid py-4 px-4">
    <div class="max-w-7xl mx-auto">
        @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded-md">
            {{ session('success') }}
        </div>
        @endif
        
        @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded-md">
            {{ session('error') }}
        </div>
        @endif
        
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
            <h2 class="text-xl font-bold text-gray-800">Penilaian Mahasiswa</h2>
        </div>

        <!-- Dedicated Filter Section -->
        <div class="bg-[#F0F7F0] border border-[#CCEACC] rounded-xl p-5 mb-6 shadow-sm">
            <div class="flex items-center gap-2 mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
                <h3 class="font-medium text-lg text-gray-800">Filter Penilaian</h3>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Stase Selector - Enhanced -->
                <div>
                    <label for="stase-selector" class="block text-sm font-medium text-gray-700 mb-1">Pilih Stase</label>
                    <div class="relative">
                        <select id="stase-selector" 
                                onchange="onStaseChange()"
                                class="block w-full appearance-none bg-white border-2 border-green-200 rounded-lg py-2.5 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 shadow-sm hover:border-green-300 transition-colors duration-200">
                            <option value="" selected>Pilih Stase</option>
                            @foreach($stases as $s)
                            <option value="{{ $s->id }}" {{ (request('stase_id') == $s->id) ? 'selected' : '' }}>{{ $s->name }}</option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-green-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- TAHUN SELECTOR - Tampil setelah Stase dipilih -->
                <div id="year-selector-container" class="hidden">
                    <label for="year-selector" class="block text-sm font-medium text-gray-700 mb-1">Tahun Ajaran</label>
                    <div class="relative">
                        <select id="year-selector" 
                                onchange="onYearChange()"
                                class="block w-full appearance-none bg-white border-2 border-green-200 rounded-lg py-2.5 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 shadow-sm hover:border-green-300 transition-colors duration-200">
                            <option value="" selected>Pilih Tahun Ajaran</option>
                            @if(isset($availableYears) && $availableYears->count() > 0)
                                @foreach($availableYears as $yearData)
                                <option value="{{ $yearData->class_year }}" {{ (request('year') == $yearData->class_year) ? 'selected' : '' }}>
                                    {{ $yearData->class_year }}
                                    @if($yearData->class_year == date('Y') . '/' . (date('Y') + 1))
                                        (Tahun Ini)
                                    @endif
                                </option>
                                @endforeach
                            @endif
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-green-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                </div>
                
                <!-- Class Selector - Tampil setelah Tahun dipilih -->
                <div id="class-selector-container" class="hidden">
                    <label for="class-selector" class="block text-sm font-medium text-gray-700 mb-1">Pilih Kelas</label>
                    <div class="relative">
                        <select id="class-selector" 
                                onchange="updateSelectedValues()"
                                class="block w-full appearance-none bg-white border-2 border-green-200 rounded-lg py-2.5 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 shadow-sm hover:border-green-300 transition-colors duration-200">
                            <option value="" selected>Pilih Kelas</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-green-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="content-container">
            @php
                // Hanya tampilkan konten jika ketiga parameter dipilih dan tidak kosong
                $showContent = request()->filled('class_id') && request()->filled('stase_id') && request()->filled('year') &&
                               request('class_id') != '' && request('stase_id') != '' && request('year') != '';
            @endphp
            
            @if($showContent)
                @if($students->isNotEmpty())
                    <!-- Tampilkan konten penilaian -->
                    <div class="card bg-white rounded-xl shadow-sm">
                        <div class="card-body">
                            <div class="p-4">
                                <!-- Kelas Info -->
                                <div class="mb-4 space-y-1">
                                    <h6 class="text-sm font-medium text-gray-600">Kelas</h6>
                                    <h5 class="text-xl font-semibold mb-2">{{ $kelas }}</h5>
                                    <div class="space-y-0.5">
                                        <p class="text-sm text-gray-600">{{ $departement->name }}</p>
                                    </div>
                                </div>
        
                                <!-- Detail Kelompok -->
                                <div class="mt-2">
                                    <div class="mb-4">
                                        <h6 class="text-base font-medium">Detail Kelas</h6>
                                    </div>
                                    
                                    <div class="space-y-4">
                                        <div class="grid grid-cols-2 gap-4">
                                            <!-- Kampus Card -->
                                            <div class="flex items-center bg-[#ECF5EC] rounded-xl p-4 hover:shadow-lg transition duration-300">
                                                <div class="flex items-center gap-2">
                                                    <div class="w-8 h-8 bg-black rounded-full grid place-items-center">
                                                        <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                                        </svg>
                                                    </div>
                                                    <div>
                                                        <p class="text-sm font-medium">Kelas</p>
                                                        <p class="text-base">{{ $kelas }}</p>
                                                        <!-- TAMBAHAN: Tampilkan tahun ajaran -->
                                                        <p class="text-xs text-gray-500">Tahun {{ request('year', date('Y')) }}</p>
                                                    </div>
                                                </div>
                                            </div>
        
                                            <!-- Stase Card -->
                                            <div class="bg-[#ECF5EC] rounded-xl p-4 hover:shadow-lg transition duration-300">
                                                <div class="flex items-center gap-2">
                                                    <div class="w-8 h-8 bg-black rounded-full grid place-items-center">
                                                        <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                                        </svg>
                                                    </div>
                                                    <div>
                                                        <p class="text-sm font-medium">Stase</p>
                                                        <p class="text-base">{{ $stase->name }}</p>
                                                        <p class="text-xs text-gray-500">{{ $departement->name }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
        
                                        <!-- Student List -->
                                        <div class="bg-[#FAFBF8] rounded-xl p-6">
                                            <h6 class="font-medium text-lg mb-6">Daftar Penilaian</h6>
                                            <div class="overflow-x-auto">
                                                <table class="w-full">
                                                    <thead class="bg-gray-50">
                                                        <tr>
                                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-16">No</th>
                                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kampus</th>
                                                            
                                                            <!-- PERBAIKAN: Tampilkan SEMUA komponen tanpa filter -->
                                                            @foreach($gradeComponents as $component)
                                                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[120px]">
                                                                {{ $component->name }}
                                                            </th>
                                                            @endforeach
                                                            
                                                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[100px]">Rata-rata</th>
                                                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-24">Aksi</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="divide-y divide-[#E8EBE0]">
                                                        @foreach($students as $student)
                                                        <tr class="{{ isset($existingGrades[$student->id]) ? 'bg-[#F0F7F0]' : '' }} hover:bg-[#F5F7F2] transition-colors duration-150">
                                                            <!-- Kolom No -->
                                                            <td class="py-4 px-4 text-center">
                                                                {{ $loop->iteration }}
                                                            </td>
                                                            
                                                            <!-- Kolom Nama -->
                                                            <td class="py-4 px-4">
                                                                <div class="flex items-center gap-3">
                                                                    <div class="w-10 h-10 bg-[#F0F3E7] rounded-full flex items-center justify-center text-sm font-medium">
                                                                        {{ strtoupper(substr($student->user->name, 0, 2)) }}
                                                                    </div>
                                                                    <div>
                                                                        <span class="font-medium">{{ $student->user->name }}</span>
                                                                        
                                                                        <!-- Status indikator untuk mahasiswa yang sudah dinilai -->
                                                                        @if(isset($existingGrades[$student->id]))
                                                                        <div class="flex items-center mt-1">
                                                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                                                <svg class="mr-1 h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                                                </svg>
                                                                                Sudah dinilai
                                                                            </span>
                                                                            <span class="ml-2 text-xs text-gray-500">
                                                                                {{ isset($existingGrades[$student->id]['updated_at']) ? 
                                                                               'Diperbaharui: ' . \Carbon\Carbon::parse($existingGrades[$student->id]['updated_at'])->format('d M Y, H:i') : '' }}
                                                                            </span>
                                                                        </div>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            
                                                            <!-- Kolom Kampus -->
                                                            <td class="py-4 px-4 text-gray-600">
                                                                {{ $student->studyProgram->campus->name ?? '-' }}
                                                            </td>
                                                            
                                                            <!-- PERBAIKAN: Input untuk SEMUA komponen dengan data attributes -->
                                                            @foreach($gradeComponents as $component)
                                                            <td class="py-4 px-4 text-center">
                                                                <input type="text"
                                                                    name="grades[{{ $component->id }}]"
                                                                    data-student-id="{{ $student->id }}"
                                                                    data-component-id="{{ $component->id }}"
                                                                    class="w-full max-w-[140px] mx-auto px-4 py-2.5 text-black 
                                                                          {{ isset($existingGrades[$student->id]) ? 'bg-[#F6FFF6] border-green-200' : 'bg-white border-[#E8EBE0]' }} 
                                                                          border rounded-lg text-base text-center font-medium focus:ring-2 focus:ring-[#DCE0D3] focus:border-transparent transition-all duration-200"
                                                                    placeholder="0.00"
                                                                    inputmode="decimal"
                                                                    pattern="[0-9]*\.?[0-9]*"
                                                                    oninput="handleScoreInput(this); updateGradeValue({{ $student->id }}, {{ $component->id }}, this.value)"
                                                                    value="{{ isset($existingGrades[$student->id]['grades'][$component->id]) ? number_format($existingGrades[$student->id]['grades'][$component->id], 2, '.', '') : '' }}"
                                                                >
                                                            </td>
                                                            @endforeach
                                                            
                                                            <!-- Kolom Rata-rata dengan ID untuk update real-time -->
                                                            <td class="py-4 px-4 text-center">
                                                                <div id="average-container-{{ $student->id }}" class="w-full max-w-[100px] mx-auto px-4 py-2.5 
                                                                               {{ isset($existingGrades[$student->id]['average']) && $existingGrades[$student->id]['average'] > 0 ? 'bg-green-100 border-green-200' : 'bg-gray-100 border-gray-200' }} 
                                                                               border rounded-lg text-base text-center font-bold">
                                                                    <span id="average-{{ $student->id }}" class="{{ isset($existingGrades[$student->id]['average']) && $existingGrades[$student->id]['average'] > 0 ? 'text-green-800' : 'text-gray-600' }}">
                                                                        @if(isset($existingGrades[$student->id]['average']) && $existingGrades[$student->id]['average'] > 0)
                                                                            {{ number_format($existingGrades[$student->id]['average'], 1) }}
                                                                        @else
                                                                            0.0
                                                                        @endif
                                                                    </span>
                                                                </div>
                                                            </td>
                                                            
                                                            <!-- Kolom Aksi -->
                                                            <td class="py-4 px-4 text-center">
                                                                <form action="{{ route('responsible.grades.store') }}" method="POST" id="gradeForm{{ $student->id }}">
                                                                    @csrf
                                                                    <input type="hidden" name="student_id" value="{{ $student->id }}">
                                                                    <input type="hidden" name="stase_id" value="{{ $stase->id }}">
                                                                    <input type="hidden" name="class_id" value="{{ $selectedClassId }}">
                                                                    <input type="hidden" name="year" value="{{ request('year', date('Y')) }}">
                                                                    @if(request('show_all'))
                                                                    <input type="hidden" name="show_all" value="1">
                                                                    @endif
                                                                    
                                                                    <!-- Hidden inputs untuk semua komponen -->
                                                                    @foreach($gradeComponents as $component)
                                                                    <input type="hidden" 
                                                                           name="grades[{{ $component->id }}]" 
                                                                           value="{{ isset($existingGrades[$student->id]['grades'][$component->id]) ? $existingGrades[$student->id]['grades'][$component->id] : '0' }}"
                                                                           class="grade-input-{{ $student->id }}-{{ $component->id }}">
                                                                    @endforeach
                                                                    
                                                                    <button type="submit" class="{{ isset($existingGrades[$student->id]) ? 
                                                                        'bg-green-600 hover:bg-green-700 focus:ring-green-500' : 
                                                                        'bg-[#4CAF50] hover:bg-[#43A047] focus:ring-[#388E3C]' }} 
                                                                        px-4 py-2 text-white rounded-lg text-sm font-medium transition-all duration-200 focus:ring-2 focus:ring-offset-2">
                                                                        {{ isset($existingGrades[$student->id]) ? 'Perbarui' : 'Submit' }}
                                                                    </button>
                                                                </form>
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                            
                                            @if($students->count() > 0 && isset($remainingCount) && $remainingCount > 0)
                                            <div class="text-center mt-8">
                                                <a href="{{ url('/responsible/grades?show_all=1&class_id='.$selectedClassId.'&stase_id='.$selectedStaseId.'&year='.request('year')) }}" class="px-6 py-3 bg-white border border-[#E8EBE0] text-gray-700 rounded-lg text-sm font-medium hover:bg-[#F5F7F2] transition-all duration-200 focus:ring-2 focus:ring-[#DCE0D3]">
                                                    Lihat Semua Mahasiswa ({{ $remainingCount }} lainnya)
                                                </a>
                                            </div>
                                            @endif
                                            
                                            @if(isset($showAll) && $showAll)
                                            <div class="text-center mt-4">
                                                <a href="{{ url('/responsible/grades?class_id='.$selectedClassId.'&stase_id='.$selectedStaseId.'&year='.request('year')) }}" class="px-6 py-3 bg-white border border-[#E8EBE0] text-gray-700 rounded-lg text-sm font-medium hover:bg-[#F5F7F2] transition-all duration-200 focus:ring-2 focus:ring-[#DCE0D3]">
                                                    Kembali ke Tampilan Awal
                                                </a>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Tampilkan pesan tidak ada mahasiswa untuk tahun yang dipilih -->
                    <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-8 text-center">
                        <svg class="w-16 h-16 text-yellow-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                        <h3 class="text-lg font-medium text-yellow-700 mb-2">Tidak Ada Mahasiswa</h3>
                        <p class="text-yellow-600">Tidak ada mahasiswa di kelas {{ $kelas }} untuk tahun {{ $selectedYear }}. Silahkan pilih tahun yang berbeda.</p>
                    </div>
                @endif
            @else
            <!-- Tampilkan instruksi untuk memilih -->
            <div class="bg-[#f8f9f5] border border-[#e8ebe0] rounded-xl p-8 text-center">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
                <h3 class="text-lg font-medium text-gray-700 mb-2">Silahkan Pilih Kelas, Stase, dan Tahun</h3>
                <p class="text-gray-500">Pilih kelas, stase, dan tahun terlebih dahulu untuk melihat daftar mahasiswa dan melakukan penilaian.</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Store available data dari backend
const availableYears = @json($availableYears);
const availableClasses = @json($availableClasses);

// Simpan data existing grades dari backend
const existingGrades = @json($existingGrades ?? []);

console.log("Available years:", availableYears);
console.log("Available classes:", availableClasses);

function onStaseChange() {
    const staseId = document.getElementById('stase-selector').value;
    const yearContainer = document.getElementById('year-selector-container');
    const classContainer = document.getElementById('class-selector-container');
    
    if (staseId) {
        // Show year selector
        yearContainer.classList.remove('hidden');
        
        // Hide class selector until year is selected
        classContainer.classList.add('hidden');
        
        // Clear class selector
        const classSelector = document.getElementById('class-selector');
        classSelector.innerHTML = '<option value="" selected>Pilih Kelas</option>';
        
        // Update URL with just stase parameter
        window.location.href = '{{ url('/responsible/grades') }}?stase_id=' + staseId;
    } else {
        // Hide both year and class selector
        yearContainer.classList.add('hidden');
        classContainer.classList.add('hidden');
        
        // Reset URL
        window.location.href = '{{ url('/responsible/grades') }}';
    }
}

function onYearChange() {
    const staseId = document.getElementById('stase-selector').value;
    const year = document.getElementById('year-selector').value;
    const classContainer = document.getElementById('class-selector-container');
    
    if (staseId && year) {
        // Show class selector
        classContainer.classList.remove('hidden');
        
        // Clear class selector first
        const classSelector = document.getElementById('class-selector');
        classSelector.innerHTML = '<option value="" selected>Pilih Kelas</option>';
        
        // Add message while loading
        const loadingOption = document.createElement('option');
        loadingOption.value = "";
        loadingOption.textContent = "Memuat kelas...";
        loadingOption.disabled = true;
        classSelector.appendChild(loadingOption);
        
        // Update URL with stase and year parameters
        window.location.href = '{{ url('/responsible/grades') }}?stase_id=' + staseId + '&year=' + year;
    } else {
        // Hide class selector
        classContainer.classList.add('hidden');
        
        // Clear class selector
        const classSelector = document.getElementById('class-selector');
        classSelector.innerHTML = '<option value="" selected>Pilih Kelas</option>';
    }
}

function populateClassDropdownByYear(selectedYear) {
    const classSelector = document.getElementById('class-selector');
    classSelector.innerHTML = '<option value="" selected>Pilih Kelas</option>';
    
    console.log("Selected year:", selectedYear);
    console.log("Available classes data:", availableClasses);
    
    // Filter kelas berdasarkan tahun yang dipilih - HANYA gunakan data yang ada
    if (availableClasses && availableClasses.length > 0) {
        availableClasses.forEach(classItem => {
            const option = document.createElement('option');
            option.value = classItem.id;
            option.textContent = classItem.name;
            if ({{ request('class_id') ?? 'null' }} == classItem.id) {
                option.selected = true;
            }
            classSelector.appendChild(option);
        });
    } else {
        // Jika tidak ada kelas, tampilkan pesan
        const option = document.createElement('option');
        option.value = "";
        option.textContent = "Tidak ada kelas untuk tahun " + selectedYear;
        option.disabled = true;
        classSelector.appendChild(option);
    }
}

function updateSelectedValues() {
    const staseId = document.getElementById('stase-selector').value;
    const year = document.getElementById('year-selector').value;
    const classId = document.getElementById('class-selector').value;
    
    if (staseId && year && classId) {
        window.location.href = '{{ url('/responsible/grades') }}?stase_id=' + staseId + '&year=' + year + '&class_id=' + classId;
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const staseSelector = document.getElementById('stase-selector');
    const yearSelector = document.getElementById('year-selector');
    const classSelector = document.getElementById('class-selector');
    const yearContainer = document.getElementById('year-selector-container');
    const classContainer = document.getElementById('class-selector-container');
    const urlParams = new URLSearchParams(window.location.search);
    
    // Get params
    const staseParam = urlParams.get('stase_id');
    const yearParam = urlParams.get('year');
    const classParam = urlParams.get('class_id');
    
    if (staseParam) {
        // Set stase value
        staseSelector.value = staseParam;
        
        // Show year dropdown
        yearContainer.classList.remove('hidden');
        
        if (yearParam) {
            // Set year value
            yearSelector.value = yearParam;
            
            // Show class dropdown and populate options
            classContainer.classList.remove('hidden');
            populateClassDropdownByYear(yearParam);
            
            // Set class value if available
            setTimeout(() => {
                if (classParam) {
                    classSelector.value = classParam;
                }
            }, 100);
        }
    }
});

// Tambahkan fungsi untuk menghitung rata-rata real-time
function calculateAverage(studentId) {
    const avgElement = document.getElementById(`average-${studentId}`);
    const avgContainer = document.getElementById(`average-container-${studentId}`);
    
    if (avgElement && avgContainer) {
        // Ambil rata-rata langsung dari data StudentGrade yang sudah ada
        let displayAverage = '0.0';
        
        if (existingGrades[studentId] && existingGrades[studentId].average) {
            // Gunakan rata-rata dari database StudentGrade
            displayAverage = parseFloat(existingGrades[studentId].average).toFixed(1);
        }
        
        avgElement.textContent = displayAverage;
        
        if (parseFloat(displayAverage) > 0) {
            // Update styling untuk menunjukkan ada nilai
            avgContainer.className = 'w-full max-w-[100px] mx-auto px-4 py-2.5 bg-green-100 border-green-200 border rounded-lg text-base text-center font-bold';
            avgElement.className = 'text-green-800';
        } else {
            // Update styling untuk menunjukkan tidak ada nilai
            avgContainer.className = 'w-full max-w-[100px] mx-auto px-4 py-2.5 bg-gray-100 border-gray-200 border rounded-lg text-base text-center font-bold';
            avgElement.className = 'text-gray-600';
        }
    }
}

// Update fungsi updateGradeValue untuk sync dengan hidden input
function updateGradeValue(studentId, componentId, value) {
    // Update hidden input dengan nilai yang valid
    const hiddenInput = document.querySelector(`.grade-input-${studentId}-${componentId}`);
    if (hiddenInput) {
        // Pastikan value adalah angka yang valid, jika kosong set ke 0
        const numValue = parseFloat(value);
        hiddenInput.value = isNaN(numValue) || value === '' ? '0' : numValue;
    }
    
    // Hitung rata-rata real-time
    calculateAverage(studentId);
}

// Perbaiki fungsi handleScoreInput
function handleScoreInput(input) {
    // Simpan posisi cursor
    const cursorPosition = input.selectionStart;
    let value = input.value;
    let newCursorPosition = cursorPosition;
    
    // Hapus karakter yang bukan angka atau titik desimal
    const originalLength = value.length;
    value = value.replace(/[^0-9.]/g, '');
    
    // Adjust cursor position jika ada karakter yang dihapus
    if (value.length < originalLength) {
        newCursorPosition = Math.max(0, cursorPosition - (originalLength - value.length));
    }
    
    // Pastikan hanya ada satu titik desimal
    const parts = value.split('.');
    if (parts.length > 2) {
        value = parts[0] + '.' + parts.slice(1).join('');
    }
    
    // Jika dimulai dengan titik, tambahkan 0 di depan
    if (value.startsWith('.')) {
        value = '0' + value;
        newCursorPosition++;
    }
    
    // Batasi hingga 2 digit desimal
    if (parts.length > 1 && parts[1] && parts[1].length > 2) {
        value = parts[0] + '.' + parts[1].substring(0, 2);
        if (newCursorPosition > value.length) {
            newCursorPosition = value.length;
        }
    }
    
    // Cek jika nilai melebihi 100 (hanya jika value tidak kosong dan bukan hanya titik)
    if (value !== '' && value !== '.' && !isNaN(parseFloat(value))) {
        const numValue = parseFloat(value);
        if (numValue > 100) {
            value = '100';
            newCursorPosition = value.length;
        }
        
        // Pastikan nilai minimum 0
        if (numValue < 0) {
            value = '0';
            newCursorPosition = value.length;
        }
    }
    
    // Set value
    input.value = value;
    
    // Restore posisi cursor
    setTimeout(() => {
        if (input === document.activeElement) {
            input.setSelectionRange(newCursorPosition, newCursorPosition);
        }
    }, 0);
}

document.addEventListener('DOMContentLoaded', function() {
    // ... existing code ...
    
    // Hitung rata-rata untuk semua student yang sudah ada nilainya saat halaman dimuat
    const allStudents = document.querySelectorAll('[data-student-id]');
    const processedStudents = new Set();
    
    allStudents.forEach(input => {
        const studentId = input.getAttribute('data-student-id');
        if (!processedStudents.has(studentId)) {
            calculateAverage(studentId);
            processedStudents.add(studentId);
        }
    });
    
    // ... rest of existing DOMContentLoaded code ...
});
</script>
@endpush