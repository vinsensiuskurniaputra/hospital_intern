@extends('layouts.auth')

@section('title', 'Jadwal Mahasiswa')

@section('content')
<div class="p-4" x-data="{
    months: [
        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ],
    currentMonth: 'Mei',
    currentYear: '2025',
    selectedDate: 12,
    todayDate: new Date().getDate(),
    getYears() {
        const currentYear = new Date().getFullYear();
        return Array.from({length: 10}, (_, i) => currentYear + i);
    },
    printPage() {
        window.print();
    }
}">
    <h5 class="text-xl font-semibold mb-4">Jadwal Magang</h5>

    <!-- Jadwal Hari Ini Section -->
    <div class="bg-white rounded-lg p-4 mb-6">
        <h6 class="font-semibold mb-3">Jadwal Hari Ini</h6>

        <!-- Two Column Layout -->
        <div class="flex gap-4">
            <!-- Left Column - Calendar -->
            <div class="w-[320px]">
                <!-- Calendar Controls -->
                <div class="flex gap-2 mb-3">
                    <select x-model="currentMonth" class="form-select rounded-md text-sm border-gray-300 w-[160px] h-9 transition-all duration-200 ease-in-out hover:border-[#637F26] focus:border-[#637F26] focus:ring focus:ring-[#637F26] focus:ring-opacity-50">
                        <template x-for="month in months" :key="month">
                            <option :value="month" x-text="month"></option>
                        </template>
                    </select>
                    <select x-model="currentYear" class="form-select rounded-md text-sm border-gray-300 w-[112px] h-9 transition-all duration-200 ease-in-out hover:border-[#637F26] focus:border-[#637F26] focus:ring focus:ring-[#637F26] focus:ring-opacity-50">
                        <template x-for="year in getYears()" :key="year">
                            <option :value="year" x-text="year"></option>
                        </template>
                    </select>
                </div>

                <!-- Calendar -->
                <div class="w-[280px] bg-white rounded-lg border border-gray-200 overflow-hidden mb-3">
                    <!-- Calendar Header -->
                    <div class="grid grid-cols-7 border-b border-gray-200">
                        <div class="py-1.5 text-center text-xs font-medium text-gray-600">Ming</div>
                        <div class="py-1.5 text-center text-xs font-medium text-gray-600">Sen</div>
                        <div class="py-1.5 text-center text-xs font-medium text-gray-600">Sel</div>
                        <div class="py-1.5 text-center text-xs font-medium text-gray-600">Rab</div>
                        <div class="py-1.5 text-center text-xs font-medium text-gray-600">Kam</div>
                        <div class="py-1.5 text-center text-xs font-medium text-gray-600">Jum</div>
                        <div class="py-1.5 text-center text-xs font-medium text-gray-600">Sab</div>
                    </div>

                    <!-- Calendar Body -->
                    <div class="grid grid-cols-7 gap-1.5 p-1.5">
                        <template x-for="date in 31">
                            <div
                                @click="selectedDate = date"
                                class="aspect-square w-[32px] flex items-center justify-center cursor-pointer rounded-lg transition-all duration-300 ease-in-out transform text-sm"
                                :class="{
                                    'bg-[#637F26] text-white scale-105 shadow-md': selectedDate === date,
                                    'hover:bg-[#F5F7F0] hover:text-[#637F26] hover:scale-105': selectedDate !== date,
                                    'ring-2 ring-[#637F26] ring-offset-2': date === todayDate && selectedDate !== date
                                }"
                            >
                                <span x-text="date"></span>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Calendar Footer -->
                <div class="flex justify-end gap-2 w-[280px]">
                    <button
                        @click="selectedDate = todayDate"
                        class="h-8 px-3 text-sm rounded-md text-gray-700 hover:bg-gray-100 transition-all duration-200 ease-in-out hover:shadow-md transform hover:-translate-y-0.5 active:translate-y-0 active:shadow-sm"
                    >
                        Batal
                    </button>
                    <button
                        class="h-8 px-3 text-sm rounded-md bg-[#637F26] text-white hover:bg-[#4f6420] transition-all duration-200 ease-in-out hover:shadow-md transform hover:-translate-y-0.5 active:translate-y-0 active:shadow-sm"
                        @click="$dispatch('date-selected', { date: selectedDate })"
                    >
                        Selesai
                    </button>
                </div>
            </div>

            <!-- Right Column - Schedule -->
            <div class="flex-1 space-y-2">
                <div class="bg-[#F5F7F0] p-4 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-[#637F26]"></span>
                            <h6 class="font-semibold text-sm">Kelas FK-01</h6>
                        </div>
                        <span class="text-xs text-gray-500">Keperawatan</span>
                    </div>
                    <div class="mt-2 text-sm text-gray-600">
                        <div class="flex items-center gap-2 mb-1">
                            <i class="bi bi-clock text-[#637F26]"></i>
                            <span>08:00 - 11:00</span>
                        </div>
                        <div class="flex items-center gap-2 mb-1">
                            <i class="bi bi-calendar text-[#637F26]"></i>
                            <span class="text-xs">12/05/2025 - 18/05/2025</span>
                        </div>
                        <p class="ml-6">Departemen THT</p>
                    </div>
                </div>

                <div class="bg-[#F5F7F0] p-4 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-[#637F26]"></span>
                            <h6 class="font-semibold text-sm">Kelas FK-01</h6>
                        </div>
                        <span class="text-xs text-gray-500">Keperawatan</span>
                    </div>
                    <div class="mt-2 text-sm text-gray-600">
                        <div class="flex items-center gap-2 mb-1">
                            <i class="bi bi-clock text-[#637F26]"></i>
                            <span>11:00 - 14:00</span>
                        </div>
                        <div class="flex items-center gap-2 mb-1">
                            <i class="bi bi-calendar text-[#637F26]"></i>
                            <span class="text-xs">12/05/2025 - 12/05/2025</span>
                        </div>
                        <p class="ml-6">Departemen Mata</p>
                    </div>
                </div>

                <div class="bg-[#F5F7F0] p-4 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-[#637F26]"></span>
                            <h6 class="font-semibold text-sm">Kelas FK-01</h6>
                        </div>
                        <span class="text-xs text-gray-500">Keperawatan</span>
                    </div>
                    <div class="mt-2 text-sm text-gray-600">
                        <div class="flex items-center gap-2 mb-1">
                            <i class="bi bi-clock text-[#637F26]"></i>
                            <span>14:00 - 17:00</span>
                        </div>
                        <div class="flex items-center gap-2 mb-1">
                            <i class="bi bi-calendar text-[#637F26]"></i>
                            <span class="text-xs">12/05/2025 - 12/05/2025</span>
                        </div>
                        <p class="ml-6">Departemen Kulit</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Jadwal Section -->
    <div class="bg-white rounded-lg p-4">
        <h6 class="text-lg font-semibold mb-4">Tabel Jadwal</h6>

        <!-- Filters -->
        <div class="flex items-end gap-3 mb-4">
            <div class="w-[240px]">
                <p class="text-sm text-gray-600 mb-1">Rentang Tanggal</p>
                <input type="date" x-model="start_date" class="form-input rounded-md text-sm border-gray-300 w-full h-9 transition-all duration-200 ease-in-out hover:border-[#637F26] focus:border-[#637F26] focus:ring focus:ring-[#637F26] focus:ring-opacity-50" placeholder="Pilih rentang tanggal">
            </div>
            <div class="w-[240px]">
                <p class="text-sm text-gray-600 mb-1">Departemen</p>
                <select x-model="departement" class="form-select rounded-md text-sm border-gray-300 w-full h-9 transition-all duration-200 ease-in-out hover:border-[#637F26] focus:border-[#637F26] focus:ring focus:ring-[#637F26] focus:ring-opacity-50 [&>*]:transition-transform [&>*]:duration-200 [&>*]:ease-in-out hover:[&>*]:translate-x-1">
                    <option value="">Pilih departemen</option>
                    <option value="kardiologi">Kardiologi</option>
                    <option value="neurologi">Neurologi</option>
                    <option value="radiologi">Radiologi</option>
                </select>
            </div>
            <button class="h-9 px-4 text-sm rounded-md bg-[#637F26] text-white hover:bg-[#4f6420] transition-all duration-200 ease-in-out hover:shadow-md transform hover:-translate-y-0.5 active:translate-y-0 active:shadow-sm">
                Terapkan
            </button>
        </div>

        <!-- Time Filter Tabs -->
        <div class="border-b border-gray-200 mb-4">
            <div class="flex gap-6">
                <button class="text-sm pb-2 text-[#637F26] border-b-2 border-[#637F26] font-medium transition-all duration-200 ease-in-out transform hover:-translate-y-0.5 active:translate-y-0">Bulan Ini</button>
                <button class="text-sm pb-2 text-gray-500 hover:text-[#637F26] transition-all duration-200 ease-in-out hover:border-b-2 hover:border-[#637F26] transform hover:-translate-y-0.5 active:translate-y-0">Minggu Ini</button>
                <button class="text-sm pb-2 text-gray-500 hover:text-[#637F26] transition-all duration-200 ease-in-out hover:border-b-2 hover:border-[#637F26] transform hover:-translate-y-0.5 active:translate-y-0">Minggu Depan</button>
            </div>
        </div>

        <!-- Schedule Table -->
        <div class="overflow-x-auto mb-3">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200 transition-colors duration-200">
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-600">Tanggal</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-600">Kelas</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-600">Nama Penanggung Jawab</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-600">Departemen</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-600">Jam</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <tr class="hover:bg-gray-50 transition-colors duration-200 ease-in-out">
                        <td class="py-3 px-4 text-sm text-gray-600">Mei 15, 2023</td>
                        <td class="py-3 px-4 text-sm text-gray-600">Kelas A-2023</td>
                        <td class="py-3 px-4 text-sm text-gray-600">dr. Jeki Indomie</td>
                        <td class="py-3 px-4 text-sm text-gray-600">Kardiologi</td>
                        <td class="py-3 px-4 text-sm text-gray-600">8:00 - 16:00</td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="py-3 px-4 text-sm text-gray-600">Mei 16, 2023</td>
                        <td class="py-3 px-4 text-sm text-gray-600">Kelas B-2023</td>
                        <td class="py-3 px-4 text-sm text-gray-600">dr. Angga Radiant</td>
                        <td class="py-3 px-4 text-sm text-gray-600">Kardiologi</td>
                        <td class="py-3 px-4 text-sm text-gray-600">8:00 - 16:00</td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="py-3 px-4 text-sm text-gray-600">Mei 17, 2023</td>
                        <td class="py-3 px-4 text-sm text-gray-600">Kelas A-2023</td>
                        <td class="py-3 px-4 text-sm text-gray-600">dr. Acim Resing</td>
                        <td class="py-3 px-4 text-sm text-gray-600">Neurologi</td>
                        <td class="py-3 px-4 text-sm text-gray-600">9:00 - 17:00</td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="py-3 px-4 text-sm text-gray-600">Mei 18, 2023</td>
                        <td class="py-3 px-4 text-sm text-gray-600">Kelas C-2023</td>
                        <td class="py-3 px-4 text-sm text-gray-600">dr. Stones</td>
                        <td class="py-3 px-4 text-sm text-gray-600">Kardiologi</td>
                        <td class="py-3 px-4 text-sm text-gray-600">8:00 - 16:00</td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="py-3 px-4 text-sm text-gray-600">Mei 19, 2023</td>
                        <td class="py-3 px-4 text-sm text-gray-600">Kelas B-2023</td>
                        <td class="py-3 px-4 text-sm text-gray-600">dr. Jeki, N.Tr</td>
                        <td class="py-3 px-4 text-sm text-gray-600">Radiologi</td>
                        <td class="py-3 px-4 text-sm text-gray-600">7:00 - 15:00</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Export Button -->
        <div class="text-right print:hidden">
            <button
                @click="printPage()"
                class="h-8 px-4 text-sm rounded-md border border-[#637F26] text-[#637F26] hover:bg-[#F5F7F0] transition-all duration-200 ease-in-out hover:shadow-md transform hover:-translate-y-0.5 active:translate-y-0 active:shadow-sm"
            >
                Cetak Jadwal
            </button>
        </div>
    </div>
</div>

<style>
    @media print {
        .print\:hidden {
            display: none !important;
        }
        .bg-white {
            background-color: white !important;
            box-shadow: none !important;
        }
        .rounded-lg {
            border-radius: 0 !important;
        }
        body {
            background: white !important;
        }
        @page {
            margin: 2cm;
        }
    }
</style>
@endsection
