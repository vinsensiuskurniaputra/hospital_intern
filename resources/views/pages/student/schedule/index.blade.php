@extends('layouts.auth')

@section('title', 'Jadwal Mahasiswa')

@section('content')
<div class="p-4" x-data="{
    months: [
        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ],
    currentMonth: 'November',
    currentYear: '2024',
    selectedDate: null,
    getYears() {
        const currentYear = new Date().getFullYear();
        return Array.from({length: 10}, (_, i) => currentYear + i);
    }
}">
    <h5 class="text-xl font-semibold mb-4">Jadwal Magang</h5>

    <!-- Jadwal Hari Ini Section -->
    <div class="bg-white rounded-lg p-6 mb-6">
        <h6 class="font-semibold mb-4">Jadwal Hari Ini</h6>

        <!-- Two Column Layout -->
        <div class="flex gap-6">
            <!-- Left Column - Calendar -->
            <div class="w-1/2">
                <!-- Calendar Controls -->
                <div class="flex gap-4 mb-6">
                    <select x-model="currentMonth" class="form-select rounded-lg text-sm border-gray-300 w-40">
                        <template x-for="month in months" :key="month">
                            <option :value="month" x-text="month"></option>
                        </template>
                    </select>
                    <select x-model="currentYear" class="form-select rounded-lg text-sm border-gray-300 w-32">
                        <template x-for="year in getYears()" :key="year">
                            <option :value="year" x-text="year"></option>
                        </template>
                    </select>
                </div>

                <!-- Calendar -->
                <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                    <!-- Calendar Header -->
                    <div class="grid grid-cols-7 border-b border-gray-200">
                        <div class="py-3 text-center text-xs font-medium text-gray-600">Su</div>
                        <div class="py-3 text-center text-xs font-medium text-gray-600">Mo</div>
                        <div class="py-3 text-center text-xs font-medium text-gray-600">Tu</div>
                        <div class="py-3 text-center text-xs font-medium text-gray-600">We</div>
                        <div class="py-3 text-center text-xs font-medium text-gray-600">Th</div>
                        <div class="py-3 text-center text-xs font-medium text-gray-600">Fr</div>
                        <div class="py-3 text-center text-xs font-medium text-gray-600">Sa</div>
                    </div>

                    <!-- Calendar Body -->
                    <div class="grid grid-cols-7">
                        <!-- Previous Month -->
                        <div class="aspect-square flex items-center justify-center text-sm text-gray-400 cursor-not-allowed">30</div>
                        <div class="aspect-square flex items-center justify-center text-sm text-gray-400 cursor-not-allowed">31</div>

                        <!-- Current Month Days -->
                        <template x-for="date in 30" :key="date">
                            <div
                                class="aspect-square flex items-center justify-center cursor-pointer group"
                                @click="selectedDate = date"
                            >
                                <div
                                    class="w-8 h-8 flex items-center justify-center rounded-full text-sm transition-all duration-200"
                                    :class="{
                                        'bg-[#637F26] text-white scale-105 shadow-md': selectedDate === date,
                                        'hover:bg-[#F5F7F0] hover:text-[#637F26] hover:scale-105': selectedDate !== date
                                    }"
                                    x-text="date"
                                ></div>
                            </div>
                        </template>

                        <!-- Next Month -->
                        <div class="aspect-square flex items-center justify-center text-sm text-gray-400 cursor-not-allowed">1</div>
                        <div class="aspect-square flex items-center justify-center text-sm text-gray-400 cursor-not-allowed">2</div>
                        <div class="aspect-square flex items-center justify-center text-sm text-gray-400 cursor-not-allowed">3</div>
                    </div>
                </div>

                <!-- Calendar Footer -->
                <div class="flex justify-end gap-2 mt-4">
                    <button
                        @click="selectedDate = null"
                        class="px-4 py-2 text-sm rounded-lg text-gray-700 hover:bg-gray-100"
                    >
                        Batalkan
                    </button>
                    <button
                        class="px-4 py-2 text-sm rounded-lg bg-[#637F26] text-white hover:bg-[#4f6420]"
                        @click="$dispatch('date-selected', { date: selectedDate })"
                    >
                        Selesai
                    </button>
                </div>
            </div>

            <!-- Right Column - Schedule -->
            <div class="w-1/2 space-y-3">
                <div class="bg-[#F5F7F0] p-4 rounded-lg">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-[#637F26]"></span>
                        <h6 class="font-semibold text-sm">Kelas FK-01</h6>
                    </div>
                    <div class="mt-2 text-sm text-gray-600">
                        <div class="flex items-center gap-2 mb-1">
                            <i class="bi bi-clock text-[#637F26]"></i>
                            <span>08:00 - 11:00</span>
                        </div>
                        <p class="ml-6">Departemen THT</p>
                    </div>
                </div>

                <div class="bg-[#F5F7F0] p-4 rounded-lg">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-[#637F26]"></span>
                        <h6 class="font-semibold text-sm">Kelas FK-01</h6>
                    </div>
                    <div class="mt-2 text-sm text-gray-600">
                        <div class="flex items-center gap-2 mb-1">
                            <i class="bi bi-clock text-[#637F26]"></i>
                            <span>11:00 - 14:00</span>
                        </div>
                        <p class="ml-6">Departemen Mata</p>
                    </div>
                </div>

                <div class="bg-[#F5F7F0] p-4 rounded-lg">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-[#637F26]"></span>
                        <h6 class="font-semibold text-sm">Kelas FK-01</h6>
                    </div>
                    <div class="mt-2 text-sm text-gray-600">
                        <div class="flex items-center gap-2 mb-1">
                            <i class="bi bi-clock text-[#637F26]"></i>
                            <span>14:00 - 17:00</span>
                        </div>
                        <p class="ml-6">Departemen Kulit</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Jadwal Section -->
    <div class="bg-white rounded-lg p-6">
        <h6 class="text-lg font-semibold mb-6">Tabel Jadwal</h6>

        <!-- Filters -->
        <div class="flex gap-4 mb-6">
            <div class="w-full max-w-[240px]">
                <p class="text-sm text-gray-600 mb-1">Rentang Tanggal</p>
                <input type="date" class="form-input rounded-lg text-sm border-gray-300 w-full" placeholder="Pilih rentang tanggal">
            </div>
            <div class="w-full max-w-[240px]">
                <p class="text-sm text-gray-600 mb-1">Departemen</p>
                <select class="form-select rounded-lg text-sm border-gray-300 w-full">
                    <option>Pilih departemen</option>
                    <option>Cardiology</option>
                    <option>Neurology</option>
                    <option>Radiology</option>
                </select>
            </div>
            <div class="flex items-end">
                <button class="px-6 py-2.5 text-sm rounded-lg bg-[#637F26] text-white hover:bg-[#4f6420] transition-colors">
                    Terapkan
                </button>
            </div>
        </div>

        <!-- Time Filter Tabs -->
        <div class="border-b border-gray-200 mb-4">
            <div class="flex gap-8">
                <button class="text-sm pb-3 text-[#637F26] border-b-2 border-[#637F26] font-medium">Bulan Ini</button>
                <button class="text-sm pb-3 text-gray-500 hover:text-[#637F26] transition-colors">Minggu Ini</button>
                <button class="text-sm pb-3 text-gray-500 hover:text-[#637F26] transition-colors">Minggu Depan</button>
            </div>
        </div>

        <!-- Schedule Table -->
        <div class="overflow-x-auto mb-4">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="text-left py-4 px-6 text-sm font-medium text-gray-600">Tanggal</th>
                        <th class="text-left py-4 px-6 text-sm font-medium text-gray-600">Kelas</th>
                        <th class="text-left py-4 px-6 text-sm font-medium text-gray-600">Nama Penanggung Jawab</th>
                        <th class="text-left py-4 px-6 text-sm font-medium text-gray-600">Departemen</th>
                        <th class="text-left py-4 px-6 text-sm font-medium text-gray-600">Jam</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <tr class="hover:bg-gray-50">
                        <td class="py-4 px-6 text-sm text-gray-600">Mei 15, 2023</td>
                        <td class="py-4 px-6 text-sm text-gray-600">Kelas A-2023</td>
                        <td class="py-4 px-6 text-sm text-gray-600">dr. Jeki Indomie</td>
                        <td class="py-4 px-6 text-sm text-gray-600">Cardiology</td>
                        <td class="py-4 px-6 text-sm text-gray-600">8:00 - 16:00</td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="py-4 px-6 text-sm text-gray-600">Mei 16, 2023</td>
                        <td class="py-4 px-6 text-sm text-gray-600">Kelas B-2023</td>
                        <td class="py-4 px-6 text-sm text-gray-600">dr. Angga Radiant</td>
                        <td class="py-4 px-6 text-sm text-gray-600">Cardiology</td>
                        <td class="py-4 px-6 text-sm text-gray-600">8:00 - 16:00</td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="py-4 px-6 text-sm text-gray-600">Mei 17, 2023</td>
                        <td class="py-4 px-6 text-sm text-gray-600">Kelas A-2023</td>
                        <td class="py-4 px-6 text-sm text-gray-600">dr. Acim Resing</td>
                        <td class="py-4 px-6 text-sm text-gray-600">Neurology</td>
                        <td class="py-4 px-6 text-sm text-gray-600">9:00 - 17:00</td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="py-4 px-6 text-sm text-gray-600">Mei 18, 2023</td>
                        <td class="py-4 px-6 text-sm text-gray-600">Kelas C-2023</td>
                        <td class="py-4 px-6 text-sm text-gray-600">dr. Stones</td>
                        <td class="py-4 px-6 text-sm text-gray-600">Cardiology</td>
                        <td class="py-4 px-6 text-sm text-gray-600">8:00 - 16:00</td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="py-4 px-6 text-sm text-gray-600">Mei 19, 2023</td>
                        <td class="py-4 px-6 text-sm text-gray-600">Kelas B-2023</td>
                        <td class="py-4 px-6 text-sm text-gray-600">dr. Jeki, N.Tr</td>
                        <td class="py-4 px-6 text-sm text-gray-600">Radiology</td>
                        <td class="py-4 px-6 text-sm text-gray-600">7:00 - 15:00</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Export Button -->
        <div class="text-right">
            <button class="px-4 py-2 text-sm rounded-lg border border-[#637F26] text-[#637F26] hover:bg-[#F5F7F0] transition-colors">
                Cetak Jadwal
            </button>
        </div>
    </div>
</div>
@endsection
