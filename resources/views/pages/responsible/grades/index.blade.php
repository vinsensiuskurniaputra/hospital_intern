@extends('layouts.auth')

@section('title', 'Penilaian Mahasiswa')

@section('content')
<div class="container-fluid py-4 px-4">
    <div class="max-w-7xl mx-auto">
        <div class="card bg-white rounded-xl shadow-sm">
            <div class="card-body">
                <div class="p-4">
                    <!-- Kelas Info -->
                    <div class="mb-4 space-y-1">
                        <h6 class="text-sm font-medium text-gray-600">Kelas</h6>
                        <h5 class="text-xl font-semibold mb-2">FK-01</h5>
                        <div class="space-y-0.5">
                            <p class="text-sm text-gray-600">Departemen Bedah</p>
                        </div>
                    </div>

                    <!-- Detail Kelompok -->
                    <div class="mt-2">
                        <div class="flex justify-between items-center mb-2">
                            <h6 class="text-base font-medium">Detail Kelas</h6>
                            <button onclick="toggleDetail()" class="p-1.5 hover:bg-gray-100 rounded-lg transition-colors duration-200">
                                <svg id="toggleIcon" class="w-4 h-4 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                        </div>
                        
                        <div id="detailContent" class="space-y-4 transition-all duration-200 ease-in-out">
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
                                            <p class="text-base">FK-01</p>
                                            <!-- <p class="text-xs text-gray-500">Departemen Bedah</p> -->
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
                                            <p class="text-base">Bedah Umum</p>
                                            <p class="text-xs text-gray-500">Departemen Bedah</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Student List moved inside detailContent -->
                            <div class="bg-[#FAFBF8] rounded-xl p-6">
                                <h6 class="font-medium text-lg mb-6">Daftar Penilaian</h6>
                                <div class="overflow-x-auto">
                                    <table class="w-full">
                                        <thead>
                                            <tr class="border-b-2 border-[#E8EBE0]">
                                                <th class="text-left py-4 px-4 text-sm font-semibold text-gray-700 w-1/5">Nama</th>
                                                <th class="text-left py-4 px-4 text-sm font-semibold text-gray-700 w-1/5">Kampus</th>
                                                <th class="text-center py-4 px-4 text-sm font-semibold text-gray-700 w-[15%]">Keahlian</th>
                                                <th class="text-center py-4 px-4 text-sm font-semibold text-gray-700 w-[15%]">Komunikasi</th>
                                                <th class="text-center py-4 px-4 text-sm font-semibold text-gray-700 w-[15%]">Profesionalisme</th>
                                                <th class="text-center py-4 px-4 text-sm font-semibold text-gray-700 w-[20%]">Kemampuan Menangani Pasien</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-[#E8EBE0]">
                                            @foreach([
                                                ['Zaki Ki-Demang', 'Politeknik Negeri Semarang'],
                                                ['Zaki Indome', 'Universitas Diponegoro'],
                                                ['Zaki Dok-dok', 'Universitas Gajah Mada']
                                            ] as [$name, $campus])
                                            <tr class="hover:bg-[#F5F7F2] transition-colors duration-150">
                                                <td class="py-4 px-4">
                                                    <div class="flex items-center gap-3">
                                                        <div class="w-10 h-10 bg-[#F0F3E7] rounded-full flex items-center justify-center text-sm font-medium">
                                                            {{ strtoupper(substr($name, 0, 2)) }}
                                                        </div>
                                                        <span class="font-medium">{{ $name }}</span>
                                                    </div>
                                                </td>
                                                <td class="py-4 px-4 text-gray-600">{{ $campus }}</td>
                                                @for($i = 0; $i < 4; $i++)
                                                <td class="py-4 px-4 text-center">
                                                    <input type="number" 
                                                           class="w-full max-w-[140px] mx-auto px-4 py-2.5 text-black bg-white border border-[#E8EBE0] rounded-lg text-base text-center font-medium focus:ring-2 focus:ring-[#DCE0D3] focus:border-transparent transition-all duration-200 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                                                           min="0" 
                                                           max="100" 
                                                           placeholder="Masukkan Nilai"
                                                           pattern="[0-9]*"
                                                           onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))"
                                                    >
                                                </td>
                                                @endfor
                                                
                                                <!-- Add Submit Button Column -->
                                                <td class="py-4 px-4 text-center">
                                                    <button class="px-4 py-2 bg-[#4CAF50] text-white rounded-lg text-sm font-medium hover:bg-[#43A047] transition-all duration-200 focus:ring-2 focus:ring-[#388E3C] focus:ring-offset-2">
                                                        Submit
                                                    </button>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <div class="text-center mt-8">
                                    <button class="px-6 py-2.5 bg-white border border-[#E8EBE0] text-gray-700 rounded-lg text-sm font-medium hover:bg-[#F5F7F2] transition-all duration-200 focus:ring-2 focus:ring-[#DCE0D3]">
                                        Lihat Semua Mahasiswa
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function handleScoreInput(input) {
    input.value = input.value.replace(/[^0-9]/g, '');
    let value = parseInt(input.value);
    if (value > 100) {
        input.value = '100';
    }
    if (value < 0) {
        input.value = '0';
    }
}

function toggleDetail() {
    const content = document.getElementById('detailContent');
    const icon = document.getElementById('toggleIcon');
    
    if (content.style.display === 'none' || content.style.display === '') {
        content.style.display = 'block';
        content.style.maxHeight = content.scrollHeight + 'px';
        icon.classList.add('rotate-180');
        setTimeout(() => {
            content.classList.add('opacity-100');
            content.classList.remove('opacity-0');
        }, 10);
    } else {
        icon.classList.remove('rotate-180');
        content.classList.add('opacity-0');
        content.classList.remove('opacity-100');
        content.style.maxHeight = '0px';
        setTimeout(() => {
            content.style.display = 'none';
        }, 200);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const content = document.getElementById('detailContent');
    if (content) {
        content.style.display = 'none';
        content.style.maxHeight = '0px';
        content.classList.add('opacity-0', 'transition-all', 'duration-200', 'ease-in-out');
    }
    
    // Initialize score inputs
    const scoreInputs = document.querySelectorAll('input[type="number"]');
    scoreInputs.forEach(input => {
        input.addEventListener('input', () => handleScoreInput(input));
    });
});
</script>
@endpush