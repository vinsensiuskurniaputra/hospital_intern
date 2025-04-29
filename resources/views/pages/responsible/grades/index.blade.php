@extends('layouts.auth')

@section('title', 'Penilaian Mahasiswa')

@section('content')
<div class="p-6">
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h5 class="text-xl font-semibold mb-6">Penilaian Mahasiswa</h5>

        <!-- Penilaian Tertunda -->
        <div class="mb-6">
            <h6 class="font-medium mb-4">Penilaian Tertunda</h6>
            <h6 class="text-sm font-medium mb-4">Mahasiswa Yang Menunggu Penilaian</h6>
            
            <!-- Daftar Mahasiswa -->
            <div class="space-y-3 mb-8">
                @foreach(['Jeki Kebab' => 'Cardiology', 'Jeki Rendang' => 'Neurology', 'Jeki DokDok' => 'Pediatrics', 'Jeki Demangki' => 'Emergency Medicine'] as $name => $dept)
                <div class="flex items-center justify-between p-4 bg-white border rounded-lg">
                    <div class="flex items-center space-x-3">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($name) }}" 
                             class="h-10 w-10 rounded-full">
                        <div>
                            <p class="font-medium">{{ $name }}</p>
                            <p class="text-sm text-gray-500">{{ $dept }}</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="text-sm text-gray-500">Mei 15 - Juni 12</span>
                        <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm">Tertunda</span>
                        <button class="text-blue-600 hover:text-blue-800">edit</button>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Form Penilaian -->
            <div class="bg-[#F5F7F0] p-4 rounded-lg mb-6">
                <div class="grid grid-cols-2 gap-8">
                    <div>
                        <h6 class="font-medium mb-1">Informasi Mahasiswa</h6>
                        <p class="text-sm text-gray-600">Emma Johnson • Medical Year 3 • Student ID: M302012</p>
                    </div>
                    <div>
                        <h6 class="font-medium mb-1">Detail Rotasi</h6>
                        <p class="text-sm text-gray-600">Cardiology • Dr. James Wilson • May 15 - Jun 12, 2023</p>
                    </div>
                </div>
            </div>

            <!-- Departemen -->
            <div class="mb-6">
                <label class="block text-sm font-medium mb-2">Departemen/Disiplin Ilmu</label>
                <select class="w-full rounded-lg border-gray-300">
                    <option>Cardiology</option>
                </select>
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
                                    class="w-28 h-12 text-xl font-bold text-center rounded-lg
                                           bg-white/98 
                                           border-2 border-gray-200/80
                                           shadow-sm backdrop-blur-sm
                                           hover:border-[#637F26]/70 hover:bg-[#637F26]/10
                                           focus:border-[#637F26]/80 focus:ring-2 focus:ring-[#637F26]/30 
                                           transition-all duration-200 ease-out"
                                    maxlength="3"
                                    onkeyup="validateScore(this)">
                                <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                                    <span class="text-sm font-medium text-gray-400/90 group-hover:text-[#637F26]/80 
                                               group-focus-within:opacity-0 transition-all duration-200">
                                        Masukan nilai
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Komentar -->
            <div class="mb-6">
                <h6 class="font-medium mb-2">Komentar Tambahan</h6>
                <textarea rows="4" 
                    class="w-full rounded-lg border-gray-300"
                    placeholder="Masukkan komentar atau pesan baik tambahan untuk mahasiswa"></textarea>
            </div>

            <!-- Buttons -->
            <div class="flex space-x-4 mb-8">
                <button class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
                    Simpan Konsep
                </button>
                <button class="px-4 py-2 bg-[#637F26] text-white rounded-lg hover:bg-[#4B6019]">
                    Simpan & Kirim Penilaian
                </button>
            </div>

            <!-- Riwayat -->
            <div>
                <h6 class="font-medium mb-4">Riwayat Penilaian</h6>
                <div class="flex justify-between items-center mb-4">
                    <input type="text" 
                        class="w-96 rounded-lg border-gray-300"
                        placeholder="Cari berdasarkan nama siswa">
                    <div class="space-x-2">
                        <button class="px-3 py-1 bg-[#F5F7F0] text-[#637F26] rounded">Export ke Excel</button>
                        <button class="px-3 py-1 bg-[#F5F7F0] text-[#637F26] rounded">Export ke PDF</button>
                    </div>
                </div>

                <!-- Table -->
                <table class="w-full">
                    <tbody class="divide-y">
                        @foreach([
                            ['Jeki Rendang', 'Cardiology', 'April 1 - April 28', 'Luar biasa (4.8/5)'],
                            ['Jeki Kebab', 'Neurology', 'Maret 15 - April 12', 'Baik (4.2/5)'],
                            ['Jeki Ganteng', 'Pediatrics', 'Maret 5 - April 2', 'Sangat Baik (4.5/5)'],
                            ['Jeki Demangki', 'Emergency Medicine', 'Februari 10 - Maret 9', 'Memuaskan (3.7/5)']
                        ] as [$name, $dept, $period, $score])
                        <tr>
                            <td class="py-3">
                                <div class="flex items-center">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($name) }}" 
                                         class="h-8 w-8 rounded-full mr-3">
                                    <span>{{ $name }}</span>
                                </div>
                            </td>
                            <td class="py-3">{{ $dept }}</td>
                            <td class="py-3">{{ $period }}</td>
                            <td class="py-3">{{ $score }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function validateScore(input) {
    input.value = input.value.replace(/[^0-9]/g, '');
    let value = parseInt(input.value);
    
    if (value > 100) input.value = 100;
    if (value < 1 && value !== 0) input.value = 1;
    
    const overlay = input.nextElementSibling.querySelector('span');
    overlay.style.opacity = input.value ? '0' : '1';
    
    if (value >= 1 && value <= 100) {
        input.classList.add('border-[#637F26]', 'bg-[#637F26]/5', 'text-[#637F26]');
    } else {
        input.classList.remove('border-[#637F26]', 'bg-[#637F26]/5', 'text-[#637F26]');
    }
}
</script>
@endpush