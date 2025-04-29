@extends('layouts.auth')

@section('title', 'Jadwal Magang')

@section('content')
<div class="px-6 py-4">
    <h4 class="text-xl font-semibold mb-6">Jadwal Magang</h4>

    <!-- Jadwal Hari Ini Section -->
    <div class="bg-white rounded-lg shadow-sm mb-6">
        <div class="p-6">
            <h5 class="text-lg font-medium mb-4">Jadwal Hari Ini</h5>
            <!-- Calendar Controls -->
            <div class="flex items-center gap-4 mb-6">
                <select class="form-select w-36 px-3 py-2 rounded-lg border border-gray-300">
                    <option>November</option>
                </select>
                <select class="form-select w-28 px-3 py-2 rounded-lg border border-gray-300">
                    <option>2024</option>
                </select>
                <div class="ml-auto">
                    <button class="px-4 py-2 text-sm rounded-lg border border-gray-300 hover:bg-gray-50">Cancel</button>
                    <button class="px-4 py-2 text-sm rounded-lg bg-[#637F26] text-white hover:bg-[#4B601C] ml-2">Done</button>
                </div>
            </div>

            <!-- Calendar -->
            <div class="mb-6">
                <!-- Days Header -->
                <div class="grid grid-cols-7 mb-2">
                    @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
                        <div class="text-center text-sm text-gray-600">{{ $day }}</div>
                    @endforeach
                </div>
                <!-- Calendar Days -->
                <div class="grid grid-cols-7 gap-1">
                    @for ($i = 30; $i <= 31; $i++)
                        <div class="text-center p-2 text-gray-400 text-sm">{{ $i }}</div>
                    @endfor
                    @for ($i = 1; $i <= 30; $i++)
                        <div class="text-center p-2 text-sm hover:bg-gray-50 cursor-pointer {{ $i == 10 ? 'bg-[#F5F7F0] border border-[#637F26] text-[#637F26]' : '' }}">
                            {{ $i }}
                        </div>
                    @endfor
                </div>
            </div>

            <!-- Schedule Cards -->
            <div class="space-y-3">
                @foreach(['Kelas FK-01', 'Kelas FK-01', 'Kelas FK-01'] as $index => $class)
                <div class="bg-[#F5F7F0] p-4 rounded-lg">
                    <h6 class="font-medium text-gray-900">{{ $class }}</h6>
                    <div class="flex items-center gap-2 text-sm text-gray-600 mt-1">
                        <i class="bi bi-clock"></i>
                        <span>10:00 - 14:00</span>
                    </div>
                    <div class="text-sm text-gray-600 mt-1">
                        Departemen {{ ['Kulit', 'THT', 'Mata'][$index] }}
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Tabel Jadwal Section -->
    <div class="bg-white rounded-lg shadow-sm">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h5 class="text-lg font-medium">Tabel Jadwal</h5>
                <div class="flex items-center gap-4">
                    <div class="relative">
                    <input type="date" class="form-input px-4 py-2 rounded-lg border border-gray-300" placeholder="Rentang Tanggal">
                    </div>
                    <div class="relative">
                        <select class="form-select w-48 px-3 py-2 rounded-lg border border-gray-300">
                            <option>Pilih departemen</option>
                        </select>
                    </div>
                    <button class="px-4 py-2 rounded-lg bg-[#637F26] text-white hover:bg-[#4B601C]">
                        Terapkan
                    </button>
                </div>
            </div>

            <!-- Filter Pills -->
            <div class="flex gap-2 mb-4">
                <button class="px-3 py-1 text-sm rounded-full bg-gray-100 text-gray-600">Bulan Ini</button>
                <button class="px-3 py-1 text-sm rounded-full bg-gray-100 text-gray-600">Minggu Ini</button>
                <button class="px-3 py-1 text-sm rounded-full bg-gray-100 text-gray-600">Minggu Depan</button>
                <button class="px-3 py-1 text-sm rounded-full bg-gray-100 text-gray-600">Bulan Ini</button>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-600">
                        <tr>
                            <th class="px-6 py-3 text-left">Tanggal</th>
                            <th class="px-6 py-3 text-left">Kelas</th>
                            <th class="px-6 py-3 text-left">Nama Penanggung Jawab</th>
                            <th class="px-6 py-3 text-left">Departemen</th>
                            <th class="px-6 py-3 text-left">Jam</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach(range(1, 5) as $i)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm">Mei {{ 14 + $i }}, 2023</td>
                            <td class="px-6 py-4 text-sm">Kelas {{ chr(64 + $i) }}-2023</td>
                            <td class="px-6 py-4 text-sm">dr. Jeki Indomie</td>
                            <td class="px-6 py-4 text-sm">Cardiology</td>
                            <td class="px-6 py-4 text-sm">8:00 - 15:00</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Print Button -->
            <div class="flex justify-end mt-4">
                <button class="px-4 py-2 text-sm rounded-lg border border-[#637F26] text-[#637F26] hover:bg-[#F5F7F0]">
                    Cetak Jadwal
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .form-select:focus, .form-input:focus {
        outline: none;
        border-color: #637F26;
        ring-color: #F5F7F0;
    }
</style>
@endpush