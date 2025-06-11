@extends('layouts.auth')

@section('title', 'Detail Presensi Mahasiswa')

@section('content')
    <div class="p-6 space-y-6">
        <!-- Header Section -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-800">Detail Presensi Mahasiswa</h1>
                    <p class="mt-1 text-sm text-gray-500">Pantau detail kehadiran mahasiswa</p>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <form method="GET" action="{{ route('admin.studentPresences.show', $student->id) }}">
                <div class="flex flex-col lg:flex-row lg:items-center gap-4">
                    <!-- Filter Status -->
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status"
                            class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#637F26]">
                            <option value="">Semua Status</option>
                            <option value="present" {{ request('status') == 'present' ? 'selected' : '' }}>Hadir</option>
                            <option value="sick" {{ request('status') == 'sick' ? 'selected' : '' }}>Sakit</option>
                            <option value="absent" {{ request('status') == 'absent' ? 'selected' : '' }}>Alpa</option>
                        </select>
                    </div>

                    <!-- Date Range -->
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Rentang Tanggal</label>
                        <div class="flex gap-2">
                            <input type="date" name="start_date" value="{{ request('start_date') }}"
                                class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#637F26]">
                            <input type="date" name="end_date" value="{{ request('end_date') }}"
                                class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#637F26]">
                        </div>
                    </div>

                    <div>
                        <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-[#637F26] rounded-lg hover:bg-[#85A832]">
                            Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Attendance Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-y border-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($presences as $index => $presence)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $index + 1 }}.</td>
                                <td class="px-6 py-4 text-sm text-gray-800">{{ $presence->date_entry->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    @if ($presence->status == 'present')
                                        <span class="px-2 py-1 text-xs font-medium text-white bg-green-500 rounded-lg">Hadir
                                        </span>
                                    @elseif ($presence->status == 'sick')
                                        <span
                                            class="px-2 py-1 text-xs font-medium text-white bg-yellow-400 rounded-lg">Sakit
                                        </span>
                                    @elseif ($presence->status == 'absent')
                                        <span class="px-2 py-1 text-xs font-medium text-white bg-red-500 rounded-lg">Alpa
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @include('components.general.pagination', [
                'datas' => $presences->appends(request()->except('page')), // Preserve filter values
            ])
        </div>
    </div>
@endsection
