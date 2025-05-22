@extends('layouts.auth')

@section('title', 'Presensi Mahasiswa')

@section('content')
    <div class="p-6 space-y-6">
        <!-- Bagian Header -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-800">Presensi Mahasiswa</h1>
                    <p class="mt-1 text-sm text-gray-500">Pantau dan kelola kehadiran mahasiswa</p>
                </div>
                <div class="flex gap-3">
                    <button class="px-4 py-2 text-sm font-medium text-white bg-[#637F26] rounded-lg hover:bg-[#85A832]">
                        <i class="bi bi-plus-lg mr-2"></i>Tambah Presensi
                    </button>
                    <button
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-gray-50">
                        <i class="bi bi-download mr-2"></i>Ekspor Data
                    </button>
                </div>
            </div>
        </div>

        <!-- Filter & Pencarian -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="p-6 border-b border-gray-100">
                <div class="flex flex-col lg:flex-row lg:items-center gap-4">
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tahun Kelas</label>
                        <select
                            class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#637F26]">
                            <option value="">Semua Tahun Kelas</option>
                            <option value="present">Hadir</option>
                            <option value="sick">Sakit</option>
                            <option value="permission">Izin</option>
                        </select>
                    </div>

                    <!-- Filter Status -->
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select
                            class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#637F26]">
                            <option value="">Semua Status</option>
                            <option value="present">Hadir</option>
                            <option value="sick">Sakit</option>
                            <option value="permission">Izin</option>
                        </select>
                    </div>

                    <!-- Pencarian -->
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pencarian</label>
                        <div class="relative">
                            <input type="text" placeholder="Cari berdasarkan nama atau NIM..."
                                class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#637F26]">
                            <i class="bi bi-search absolute left-3 top-2.5 text-gray-400"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabel -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-y border-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Info Mahasiswa
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            {{-- <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Evidence</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th> --}}
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($students as $index => $student)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $index + 1 }}.</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <img src="{{ $student->user->photo_profile_url ? asset('storage/' . $student->user->photo_profile_url) : 'https://ui-avatars.com/api/?name=' . urlencode($student->user->name) }}"
                                            class="h-8 w-8 rounded-full" alt="{{ $student->user->name }}">
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-800">{{ $student->user->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $student->nim }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    {{-- Bilah Progres Gabungan --}}
                                    <div class="w-full bg-gray-200 rounded-full h-4 flex overflow-hidden">
                                        <div class="bg-green-500 h-4" style="width: {{ $student->present_percentage }}%">
                                        </div>
                                        <div class="bg-yellow-400 h-4" style="width: {{ $student->sick_percentage }}%">
                                        </div>
                                        <div class="bg-red-500 h-4" style="width: {{ $student->absent_percentage }}%"></div>
                                    </div>

                                    {{-- Keterangan --}}
                                    <div class="text-xs text-gray-700 mt-2 space-x-2">
                                        <span class="inline-block">
                                            <span class="inline-block w-3 h-3 bg-green-500 rounded-full mr-1"></span>Hadir:
                                            {{ $student->present_percentage }}%
                                        </span>
                                        <span class="inline-block">
                                            <span class="inline-block w-3 h-3 bg-yellow-400 rounded-full mr-1"></span>Sakit:
                                            {{ $student->sick_percentage }}%
                                        </span>
                                        <span class="inline-block">
                                            <span class="inline-block w-3 h-3 bg-red-500 rounded-full mr-1"></span>Alpa:
                                            {{ $student->absent_percentage }}%
                                        </span>
                                    </div>
                                    {{-- <td class="px-6 py-4">
                                    @if ($row['bukti'] !== '-')
                                        <button
                                            class="inline-flex items-center px-2 py-1 text-xs font-medium text-blue-700 bg-blue-50 rounded-lg hover:bg-blue-100">
                                            <i class="bi bi-eye mr-1"></i> View
                                        </button>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td> --}}
                                    {{-- <td class="px-6 py-4">
                                    <div class="flex gap-2">
                                        <button class="p-1 text-blue-600 hover:text-blue-700">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="p-1 text-green-600 hover:text-green-700">
                                            <i class="bi bi-check2"></i>
                                        </button>
                                    </div>
                                </td> --}}
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Paginasi -->
            @include('components.general.pagination', [
                'datas' => $students,
            ])
        </div>
    </div>
@endsection
