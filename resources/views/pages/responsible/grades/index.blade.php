@extends('layouts.auth')

@section('title', 'Penilaian Mahasiswa')

@section('content')
<div class="container-fluid py-4">
    <div class="card">
        <div class="card-body">
            <div class="p-6">
                <!-- Main Title -->
                <h5 class="text-2xl font-semibold mb-8">Penilaian Mahasiswa</h5>

                <!-- Penilaian Tertunda Section -->
                <div class="mb-8">
                    <div class="border-b-2 border-gray-200 mb-6">
                        <h6 class="text-xl font-medium pb-2">Penilaian Tertunda</h6>
                    </div>
                    <h6 class="text-lg font-medium text-gray-900 mb-4">Mahasiswa Yang Menunggu Penilaian</h6>
                    
                    <div class="space-y-4">
                        @foreach([
                            ['Jeki Kebab', 'Cardiology', 'Mei 15 - Juni 12'],
                            ['Jeki Rendang', 'Neurology', 'Mei 10 - Juni 7'],
                            ['Jeki DokDok', 'Pediatrics', 'Mei 22 - Juni 19'],
                            ['Jeki Demangki', 'Emergency Medicine', 'Mei 5 - Juni 2']
                        ] as [$name, $dept, $period])
                        <div class="flex items-center justify-between p-4 bg-white border rounded-lg hover:shadow-sm transition-all duration-200">
                            <div class="flex items-center space-x-3">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($name) }}&background=F5F7F0&color=637F26" 
                                     class="h-10 w-10 rounded-full"
                                     alt="{{ $name }}">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $name }}</p>
                                    <p class="text-sm text-gray-500">{{ $dept }}</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-4">
                                <span class="text-sm text-gray-500">{{ $period }}</span>
                                <span class="px-3 py-1 bg-yellow-50 text-yellow-700 rounded-full text-sm font-medium">Tertunda</span>
                                <a href="#form-penilaian" 
                                   onclick="editAssessment('{{ $name }}', '{{ $dept }}', '{{ $period }}')"
                                   class="text-blue-600 hover:text-blue-800 font-medium cursor-pointer">
                                    edit
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Single Form Section -->
                <div id="form-penilaian" class="mb-8">
                    <div class="border-b-2 border-gray-200 mb-6">
                        <h6 class="text-xl font-medium pb-2 text-gray-900">Formulir Penilaian</h6>
                    </div>
                    
                    <!-- Info Cards -->
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div class="bg-[#F5F7F0] p-5 rounded-[20px] border-2 border-[#637F26]/30">
                            <h6 class="font-medium mb-2 text-gray-900">Informasi Mahasiswa</h6>
                            <p class="text-sm text-gray-600">Emma Johnson • Medical Year 3 • Student ID: MED202</p>
                        </div>
                        <div class="bg-[#F5F7F0] p-5 rounded-[20px] border-2 border-[#637F26]/30">
                            <h6 class="font-medium mb-2 text-gray-900">Detail Rotasi</h6>
                            <p class="text-sm text-gray-600">Cardiology • Dr. James Wilson • May 15 - Jun 12, 2023</p>
                        </div>
                    </div>

                    <form action="{{ route('responsible.reports') }}" method="POST">
                        @csrf
                        
                        <!-- Departemen -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium mb-2 text-gray-900">Departemen/Disiplin Ilmu</label>
                            <div class="relative">
                                <select class="w-full h-12 px-4 rounded-[20px] border-2 border-[#637F26]/30 bg-[#F5F7F0] text-gray-900 appearance-none cursor-pointer hover:border-[#637F26]/50 transition-colors focus:ring-0 focus:border-[#637F26]/70">
                                    <option>Cardiology</option>
                                    <option>Neurology</option>
                                    <option>Pediatrics</option>
                                    <option>Emergency Medicine</option>
                                </select>
                                <!-- Dropdown indicator -->
                                <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Evaluasi Kinerja -->
                        <div class="mb-6">
                            <h6 class="font-medium mb-4">Evaluasi Kinerja</h6>
                            @foreach([
                                'Keahlian' => 'Ability to perform clinical tasks and procedures',
                                'Komunikasi' => 'Effectiveness in communicating with the medical team',
                                'Profesionalisme' => 'Punctuality, responsibility, and professional conduct',
                                'Kemampuan dalam merawat pasien' => 'Ability to handle patients with care and empathy'
                            ] as $title => $desc)
                            <div class="border-2 rounded-lg p-5 mb-4 bg-white hover:shadow-md transition-all duration-300">
                                <div class="flex justify-between items-center gap-8">
                                    <div class="flex-1">
                                        <h6 class="font-medium text-base mb-2 text-gray-800">{{ $title }}</h6>
                                        <p class="text-sm text-gray-500 leading-relaxed">{{ $desc }}</p>
                                    </div>
                                    <div class="flex flex-col items-center">
                                        <div class="relative group">
                                            <input type="text" 
                                                name="scores[{{ Str::slug($title) }}]"
                                                class="w-28 h-12 text-xl text-center rounded-lg
                                                       bg-white/98 
                                                       border-2 border-gray-200/80
                                                       shadow-sm
                                                       hover:border-[#637F26]/70 hover:bg-[#637F26]/10
                                                       focus:border-[#637F26]/80 focus:ring-2 focus:ring-[#637F26]/30 
                                                       transition-all duration-200 ease-out"
                                                maxlength="3"
                                                pattern="[0-9]*"
                                                inputmode="numeric"
                                                oninput="handleScoreInput(this)">
                                            <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                                                <span class="text-sm font-medium text-gray-400 score-placeholder">Masukan nilai</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <!-- Single Komentar Section - Keep only this one -->
                        <div class="mb-6">
                            <h6 class="font-medium mb-2 text-gray-900">Komentar Tambahan</h6>
                            <div class="border-2 border-gray-200 rounded-[20px] bg-white">
                                <textarea 
                                    rows="4" 
                                    name="comment"
                                    class="w-full rounded-[20px] border-0 focus:ring-0 bg-white px-4 py-3 text-gray-900 placeholder-gray-400"
                                    placeholder="Masukkan komentar atau umpan balik tambahan untuk mahasiswa"
                                    ></textarea>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex space-x-3 mb-8">
                            <button type="submit" name="action" value="draft"
                                    class="px-4 py-2 bg-[#F5F7F0] text-[#637F26] rounded hover:bg-[#637F26]/10 transition-colors">
                                Simpan Konsep
                            </button>
                            <button type="submit" name="action" value="submit"
                                    class="px-4 py-2 bg-[#F5F7F0] text-[#637F26] rounded hover:bg-[#637F26]/10 transition-colors">
                                Simpan & Kirim Penilaian
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Riwayat Penilaian Section -->
                <div>
                    <div class="border-b-2 border-gray-200 mb-6">
                        <h6 class="text-xl font-medium pb-2">Riwayat Penilaian</h6>
                    </div>

                    <!-- Search and Export Section -->
                    <div class="flex items-center justify-between mb-6">
                        <!-- Search Bar -->
                        <div class="relative flex-1 max-w-[480px]">
                            <input type="search" 
                                   class="w-full h-12 rounded-[20px] border-2 border-gray-200 pl-4 pr-4 bg-gray-50/50
                                          placeholder-gray-400 text-gray-600
                                          focus:border-[#637F26]/30 focus:ring-0 focus:bg-white
                                          transition-colors duration-200"
                                   placeholder="Cari berdasarkan nama siswa">
                        </div>
                        
                        <!-- Export Buttons -->
                        <div class="flex gap-3">
                            <button type="button" 
                                    class="px-4 py-2.5 rounded-[20px] bg-[#F5F7F0] border-2 border-[#637F26]/30 
                                           text-[#637F26] text-sm font-medium hover:bg-[#637F26]/10 
                                           transition-colors duration-200">
                                Ekspor ke Excel
                            </button>
                            <button type="button"
                                    class="px-4 py-2.5 rounded-[20px] bg-[#F5F7F0] border-2 border-[#637F26]/30 
                                           text-[#637F26] text-sm font-medium hover:bg-[#637F26]/10 
                                           transition-colors duration-200">
                                Ekspor ke PDF
                            </button>
                        </div>
                    </div>

                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="text-left py-3 font-medium text-[#637F26]">Mahasiswa</th>
                                <th class="text-left py-3 font-medium text-[#637F26]">Departemen</th>
                                <th class="text-left py-3 font-medium text-[#637F26]">Periode</th>
                                <th class="text-left py-3 font-medium text-[#637F26]">Nilai</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach([
                                ['Jeki Rendang', 'Cardiology', 'April 1 - April 28', 'Luar biasa (4.8/5)'],
                                ['Jeki Kebab', 'Neurology', 'Maret 15 - April 12', 'Baik (4.2/5)'],
                                ['Jeki Ganteng', 'Pediatrics', 'Maret 5 - April 2', 'Sangat Baik (4.5/5)'],
                                ['Jeki Demangki', 'Emergency Medicine', 'Februari 10 - Maret 9', 'Memuaskan (3.7/5)']
                            ] as [$name, $dept, $period, $score])
                            <tr class="hover:bg-gray-50">
                                <td class="py-3">
                                    <div class="flex items-center">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($name) }}&background=F5F7F0&color=637F26" 
                                             class="h-8 w-8 rounded-full mr-3"
                                             alt="{{ $name }}">
                                        <span class="font-medium">{{ $name }}</span>
                                    </div>
                                </td>
                                <td class="py-3 text-gray-600">{{ $dept }}</td>
                                <td class="py-3 text-gray-600">{{ $period }}</td>
                                <td class="py-3 text-gray-600">{{ $score }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function editAssessment(name, dept, period) {
    // Update info cards
    const infoCards = document.querySelectorAll('.bg-[#F5F7F0] p.text-sm');
    infoCards[0].textContent = `${name} • ${dept}`;
    infoCards[1].textContent = `${dept} • ${period}`;
    
    // Set department in select
    const deptSelect = document.querySelector('select');
    if (deptSelect) {
        deptSelect.value = dept;
    }
    
    // Clear previous scores
    document.querySelectorAll('input[pattern="[0-9]*"]').forEach(input => {
        input.value = '';
        handleScoreInput(input);
    });
    
    // Clear comment
    document.querySelector('textarea').value = '';
    
    // Scroll to form
    document.getElementById('form-penilaian').scrollIntoView({
        behavior: 'smooth',
        block: 'start'
    });
}

function handleScoreInput(input) {
    // Remove non-numeric characters
    input.value = input.value.replace(/[^0-9]/g, '');
    
    let value = parseInt(input.value);
    let placeholder = input.nextElementSibling.querySelector('.score-placeholder');
    
    // Handle placeholder visibility
    if (input.value.length > 0) {
        placeholder.style.display = 'none';
    } else {
        placeholder.style.display = 'block';
    }
    
    // Validate range
    if (value > 100) {
        input.value = '100';
    }
    
    // Add styling based on value
    if (value > 0 && value <= 100) {
        input.classList.add('border-[#637F26]', 'bg-[#637F26]/5', 'text-[#637F26]');
    } else {
        input.classList.remove('border-[#637F26]', 'bg-[#637F26]/5', 'text-[#637F26]');
    }
}

// Initialize all score inputs
document.addEventListener('DOMContentLoaded', function() {
    const scoreInputs = document.querySelectorAll('input[pattern="[0-9]*"]');
    
    scoreInputs.forEach(input => {
        // Prevent form submission on enter
        input.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                // Optionally move focus to next input
                const allInputs = Array.from(scoreInputs);
                const currentIndex = allInputs.indexOf(this);
                const nextInput = allInputs[currentIndex + 1];
                if (nextInput) nextInput.focus();
            }
        });
        
        input.addEventListener('input', () => handleScoreInput(input));
    });
});
</script>
@endpush