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
                
                <!-- Class Selector - Hidden initially -->
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

                <!-- TAMBAHAN: Year Selector -->
                <div id="year-selector-container" class="hidden">
                    <label for="year-selector" class="block text-sm font-medium text-gray-700 mb-1">Tahun Ajaran</label>
                    <div class="relative">
                        <select id="year-selector" 
                                onchange="updateSelectedValues()"
                                class="block w-full appearance-none bg-white border-2 border-green-200 rounded-lg py-2.5 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 shadow-sm hover:border-green-300 transition-colors duration-200">
                            <option value="" selected>Pilih Tahun</option>
                            <option value="2024">2024</option>
                            <option value="2025">2025</option>
                            <option value="2026">2026</option>
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
                                            <thead>
                                                <tr class="border-b-2 border-[#E8EBE0]">
                                                    <th class="text-left py-4 px-4 text-sm font-semibold text-gray-700 w-1/5">Nama</th>
                                                    <th class="text-left py-4 px-4 text-sm font-semibold text-gray-700 w-1/5">Kampus</th>
                                                    @foreach($gradeComponents as $component)
                                                    <th class="text-center py-4 px-4 text-sm font-semibold text-gray-700 w-[15%]">{{ $component->name }}</th>
                                                    @endforeach
                                                    <!-- TAMBAHAN: Kolom Rata-rata -->
                                                    <th class="text-center py-4 px-4 text-sm font-semibold text-gray-700 w-[10%]">Rata-rata</th>
                                                    <th class="text-center py-4 px-4 text-sm font-semibold text-gray-700 w-[10%]">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-[#E8EBE0]">
                                                @foreach($students as $student)
                                                <tr class="{{ isset($existingGrades[$student->id]) ? 'bg-[#F0F7F0]' : '' }} hover:bg-[#F5F7F2] transition-colors duration-150">
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
                                                    
                                                    <!-- Kolom lain dari tabel -->
                                                    <td class="py-4 px-4 text-gray-600">
                                                        {{ $student->studyProgram->campus->name ?? '-' }}
                                                    </td>
                                                    
                                                    <!-- Form untuk nilai -->
                                                    <form action="{{ route('responsible.grades.store') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="student_id" value="{{ $student->id }}">
                                                        <input type="hidden" name="stase_id" value="{{ $stase->id }}">
                                                        <!-- TAMBAHAN: Hidden inputs untuk mempertahankan state -->
                                                        <input type="hidden" name="class_id" value="{{ $selectedClassId }}">
                                                        <input type="hidden" name="year" value="{{ request('year', date('Y')) }}">
                                                        @if(request('show_all'))
                                                        <input type="hidden" name="show_all" value="1">
                                                        @endif
                                                        
                                                        @foreach($gradeComponents as $component)
                                                        <td class="py-4 px-4 text-center">
                                                            <input type="number"
                                                                name="grades[{{ $component->id }}]"
                                                                class="w-full max-w-[140px] mx-auto px-4 py-2.5 text-black 
                                                                      {{ isset($existingGrades[$student->id]) ? 'bg-[#F6FFF6] border-green-200' : 'bg-white border-[#E8EBE0]' }} 
                                                                      border rounded-lg text-base text-center font-medium focus:ring-2 focus:ring-[#DCE0D3] focus:border-transparent transition-all duration-200 
                                                                      [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                                                                min="0"
                                                                max="100"
                                                                step="0.01"
                                                                placeholder="0.00"
                                                                onkeypress="return (event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57))"
                                                                oninput="handleScoreInput(this)"
                                                                value="{{ isset($existingGrades[$student->id]['grades'][$component->id]) ? number_format($existingGrades[$student->id]['grades'][$component->id], 2, '.', '') : '' }}"
                                                            >
                                                        </td>
                                                        @endforeach
                                                        <!-- TAMBAHAN: Kolom Rata-rata - Hanya tampilkan jika ada nilai -->
                                                        <td class="py-4 px-4 text-center">
                                                            <div class="w-full max-w-[100px] mx-auto px-4 py-2.5 
                                                                       {{ isset($existingGrades[$student->id]['average']) && $existingGrades[$student->id]['average'] > 0 ? 'bg-green-100 border-green-200 text-green-800' : 'bg-gray-100 border-gray-200 text-gray-600' }} 
                                                                       border rounded-lg text-base text-center font-bold">
                                                                @if(isset($existingGrades[$student->id]['average']) && $existingGrades[$student->id]['average'] > 0)
                                                                    {{ number_format($existingGrades[$student->id]['average'], 1) }}
                                                                @else
                                                                    0.0
                                                                @endif
                                                            </div>
                                                        </td>
                                                        <td class="py-4 px-4 text-center">
                                                            <button type="submit" class="{{ isset($existingGrades[$student->id]) ? 
                                                                'bg-green-600 hover:bg-green-700 focus:ring-green-500' : 
                                                                'bg-[#4CAF50] hover:bg-[#43A047] focus:ring-[#388E3C]' }} 
                                                                px-4 py-2 text-white rounded-lg text-sm font-medium transition-all duration-200 focus:ring-2 focus:ring-offset-2">
                                                                {{ isset($existingGrades[$student->id]) ? 'Perbarui' : 'Submit' }}
                                                            </button>
                                                        </td>
                                                    </form>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    
                                    @if($students->count() > 0 && isset($remainingCount) && $remainingCount > 0)
                                    <div class="text-center mt-8">
                                        <a href="{{ url('/responsible/grades?show_all=1&class_id='.$selectedClassId.'&stase_id='.$selectedStaseId) }}" class="px-6 py-3 bg-white border border-[#E8EBE0] text-gray-700 rounded-lg text-sm font-medium hover:bg-[#F5F7F2] transition-all duration-200 focus:ring-2 focus:ring-[#DCE0D3]">
                                            Lihat Semua Mahasiswa ({{ $remainingCount }} lainnya)
                                        </a>
                                    </div>
                                    @endif
                                    
                                    @if(isset($showAll) && $showAll)
                                    <div class="text-center mt-4">
                                        <a href="{{ url('/responsible/grades?class_id='.$selectedClassId.'&stase_id='.$selectedStaseId) }}" class="px-6 py-3 bg-white border border-[#E8EBE0] text-gray-700 rounded-lg text-sm font-medium hover:bg-[#F5F7F2] transition-all duration-200 focus:ring-2 focus:ring-[#DCE0D3]">
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
            <!-- Tampilkan instruksi untuk memilih -->
            <div class="bg-[#f8f9f5] border border-[#e8ebe0] rounded-xl p-8 text-center">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
                <h3 class="text-lg font-medium text-gray-700 mb-2">Silahkan Pilih Kelas dan Stase</h3>
                <p class="text-gray-500">Pilih kelas dan stase terlebih dahulu untuk melihat daftar mahasiswa dan melakukan penilaian.</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Store all classes with stase association for client-side filtering
const allClasses = [
    // PERBAIKAN: Menghapus filter FK-02, sehingga semua kelas ditampilkan
    @foreach($availableClasses as $class)
    {
        id: {{ $class->id }}, 
        name: "{{ $class->name }}", 
        stase_id: {{ $class->stase_id ?? 'null' }}
    },
    @endforeach
];

// Tambahkan debug untuk melihat semua kelas yang tersedia
console.log("Semua kelas yang tersedia:", allClasses);

function handleScoreInput(input) {
    // Hapus karakter yang bukan angka atau titik desimal
    let value = input.value.replace(/[^0-9.]/g, '');
    
    // Pastikan hanya ada satu titik desimal
    const parts = value.split('.');
    if (parts.length > 2) {
        value = parts[0] + '.' + parts.slice(1).join('');
    }
    
    // Batasi hingga 2 digit desimal
    if (parts[1] && parts[1].length > 2) {
        value = parts[0] + '.' + parts[1].substring(0, 2);
    }
    
    // Cek jika nilai melebihi 100
    const numValue = parseFloat(value);
    if (numValue > 100) {
        value = '100.00';
    }
    
    // Pastikan nilai minimum 0
    if (numValue < 0) {
        value = '0.00';
    }
    
    input.value = value;
}

function onStaseChange() {
    const staseId = document.getElementById('stase-selector').value;
    const classContainer = document.getElementById('class-selector-container');
    const yearContainer = document.getElementById('year-selector-container');
    
    if (staseId) {
        // Filter classes for the selected stase
        populateClassDropdown(staseId);
        
        // Show the class and year selector
        classContainer.classList.remove('hidden');
        yearContainer.classList.remove('hidden');
        
        // Update URL with just stase parameter
        window.location.href = '{{ url('/responsible/grades') }}?stase_id=' + staseId;
    } else {
        // Hide class and year selector if no stase selected
        classContainer.classList.add('hidden');
        yearContainer.classList.add('hidden');
        
        // Reset URL
        window.location.href = '{{ url('/responsible/grades') }}';
    }
}

function populateClassDropdown(staseId) {
    const classSelector = document.getElementById('class-selector');
    classSelector.innerHTML = '<option value="" selected>Pilih Kelas</option>';
    
    // Debug untuk melihat stase ID yang dipilih
    console.log("Stase ID yang dipilih:", staseId);
    
    // Filter classes by stase_id - dengan operator == (bukan ===) untuk menangani perbedaan tipe data
    const filteredClasses = allClasses.filter(c => Number(c.stase_id) === Number(staseId));
    
    // Debug untuk melihat kelas yang terfilter
    console.log("Kelas terfilter:", filteredClasses);
    
    // Jika tidak ada kelas yang cocok, tampilkan semua kelas
    if (filteredClasses.length === 0) {
        console.warn("Tidak ada kelas yang ditemukan untuk stase_id:", staseId);
        
        // Tambahkan FK-01 secara manual jika tidak ada dalam filter
        const option = document.createElement('option');
        option.value = "1"; // Sesuaikan dengan ID FK-01 yang sebenarnya
        option.textContent = "FK-01";
        classSelector.appendChild(option);
    } else {
        // Tambahkan kelas yang terfilter ke dropdown
        filteredClasses.forEach(classItem => {
            const option = document.createElement('option');
            option.value = classItem.id;
            option.textContent = classItem.name;
            classSelector.appendChild(option);
        });
    }
}

function updateSelectedValues() {
    const staseId = document.getElementById('stase-selector').value;
    const classId = document.getElementById('class-selector').value;
    const year = document.getElementById('year-selector').value;
    
    if (staseId && classId && year) {
        window.location.href = '{{ url('/responsible/grades') }}?stase_id=' + staseId + '&class_id=' + classId + '&year=' + year;
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const staseSelector = document.getElementById('stase-selector');
    const classSelector = document.getElementById('class-selector');
    const yearSelector = document.getElementById('year-selector');
    const classContainer = document.getElementById('class-selector-container');
    const yearContainer = document.getElementById('year-selector-container');
    const urlParams = new URLSearchParams(window.location.search);
    
    // Get params
    const staseParam = urlParams.get('stase_id');
    const classParam = urlParams.get('class_id');
    const yearParam = urlParams.get('year');
    
    if (staseParam) {
        // Set stase value
        staseSelector.value = staseParam;
        
        // Show class dropdown and populate options
        classContainer.classList.remove('hidden');
        yearContainer.classList.remove('hidden');
        populateClassDropdown(staseParam);
        
        // Set class value if available
        if (classParam) {
            classSelector.value = classParam;
        }
        
        // Set year value if available
        if (yearParam) {
            yearSelector.value = yearParam;
        }
    }
    
    // Validation for score inputs
    const scoreInputs = document.querySelectorAll('input[type="number"]');
    scoreInputs.forEach(input => {
        input.addEventListener('input', () => handleScoreInput(input));
    });

    // Tambahkan kode untuk menangani notifikasi sukses
    const successAlert = document.querySelector('.bg-green-100');
    if (successAlert) {
        // Tambahkan transisi untuk animasi fade out
        successAlert.style.transition = 'opacity 1s ease-out';
        
        // Set timer untuk menghilangkan notifikasi setelah 10 detik
        setTimeout(function() {
            // Fade out terlebih dahulu
            successAlert.style.opacity = '0';
            
            // Hapus elemen setelah animasi fade out selesai
            setTimeout(function() {
                successAlert.remove();
            }, 1000); // 1 detik untuk animasi fade out
        }, 4000); // 9 detik + 1 detik animasi = total 10 detik
    }
});
</script>
@endpush