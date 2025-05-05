@extends('layouts.auth')

@section('title', 'Schedule Management')

@section('content')
    <div class="p-6 space-y-6">
        <!-- Header Section -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-800">Schedule Management</h1>
                    <p class="mt-1 text-sm text-gray-500">Manage and organize student internship schedules</p>
                </div>
                <a href="{{ route('admin.schedules.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-[#637F26] text-white rounded-lg hover:bg-[#85A832] transition-colors">
                    <i class="bi bi-plus-lg mr-2"></i>
                    Add New Schedule
                </a>
            </div>
        </div>

        <!-- Calendar and Schedule Cards -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Calendar Card -->
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-lg font-semibold text-gray-800">Calendar</h2>
                    <div class="flex gap-2">
                        <select
                            class="text-sm border border-gray-200 rounded-lg px-2 py-1 focus:ring-2 focus:ring-[#637F26]">
                            <option>November</option>
                            <option>December</option>
                            <option>Januari</option>
                        </select>
                        <select
                            class="text-sm border border-gray-200 rounded-lg px-2 py-1 focus:ring-2 focus:ring-[#637F26]">
                            <option>2024</option>
                            <option>2025</option>
                        </select>
                    </div>
                </div>

                <!-- Calendar Grid -->
                <div class="rounded-lg bg-gray-50 p-4">
                    <div class="grid grid-cols-7 gap-1 mb-2">
                        <!-- Day Headers -->
                        <div class="text-xs font-medium text-gray-500 text-center py-2">Sun</div>
                        <div class="text-xs font-medium text-gray-500 text-center py-2">Mon</div>
                        <div class="text-xs font-medium text-gray-500 text-center py-2">Tue</div>
                        <div class="text-xs font-medium text-gray-500 text-center py-2">Wed</div>
                        <div class="text-xs font-medium text-gray-500 text-center py-2">Thu</div>
                        <div class="text-xs font-medium text-gray-500 text-center py-2">Fri</div>
                        <div class="text-xs font-medium text-gray-500 text-center py-2">Sat</div>
                    </div>

                    <div class="grid grid-cols-7 gap-1">
                        <div class="p-1 text-center text-xs text-gray-400">30</div>
                        <div class="p-1 text-center text-xs text-gray-400">31</div>
                        <div class="p-1 text-center text-xs">1</div>
                        <div class="p-1 text-center text-xs">2</div>
                        <div class="p-1 text-center text-xs">3</div>
                        <div class="p-1 text-center text-xs">4</div>
                        <div class="p-1 text-center text-xs">5</div>

                        <div class="p-1 text-center text-xs">6</div>
                        <div class="p-1 text-center text-xs">7</div>
                        <div class="p-1 text-center text-xs">8</div>
                        <div class="p-1 text-center text-xs">9</div>
                        <div class="p-1 text-center text-xs bg-green-600 text-white rounded-full">10</div>
                        <div class="p-1 text-center text-xs">11</div>
                        <div class="p-1 text-center text-xs">12</div>

                        <div class="p-1 text-center text-xs">13</div>
                        <div class="p-1 text-center text-xs">14</div>
                        <div class="p-1 text-center text-xs">15</div>
                        <div class="p-1 text-center text-xs">16</div>
                        <div class="p-1 text-center text-xs">17</div>
                        <div class="p-1 text-center text-xs">18</div>
                        <div class="p-1 text-center text-xs">19</div>

                        <div class="p-1 text-center text-xs">20</div>
                        <div class="p-1 text-center text-xs">21</div>
                        <div class="p-1 text-center text-xs">22</div>
                        <div class="p-1 text-center text-xs">23</div>
                        <div class="p-1 text-center text-xs">24</div>
                        <div class="p-1 text-center text-xs">25</div>
                        <div class="p-1 text-center text-xs">26</div>

                        <div class="p-1 text-center text-xs">27</div>
                        <div class="p-1 text-center text-xs">28</div>
                        <div class="p-1 text-center text-xs">29</div>
                        <div class="p-1 text-center text-xs">30</div>
                        <div class="p-1 text-center text-xs text-gray-400">1</div>
                        <div class="p-1 text-center text-xs text-gray-400">2</div>
                        <div class="p-1 text-center text-xs text-gray-400">3</div>
                    </div>
                </div>
            </div>

            <!-- Schedule Cards -->
            <div class="lg:col-span-2 space-y-4">
                @foreach ($schedules->take(4) as $schedule)
                    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 hover:shadow-md transition-all">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-medium text-gray-800">
                                    {{ $schedule->internshipClass->name ?? 'N/A' }}</h3>
                                <p class="text-sm text-gray-500">{{ $schedule->stase->departement->name ?? 'N/A' }}</p>
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="text-right">
                                    <p class="text-sm font-medium text-gray-600">
                                        {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} -
                                        {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        {{ \Carbon\Carbon::parse($schedule->start_date)->format('d M Y') }}
                                    </p>
                                </div>
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.schedules.edit', $schedule->id) }}" class="text-blue-500">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.schedules.destroy', $schedule->id) }}" method="POST"
                                        class="inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Filters and Table Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <!-- Enhanced Filters -->
            <div class="p-6 border-b border-gray-100">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="relative flex-1">
                        <select id="departemen-filter"
                            class="w-full border border-gray-300 rounded-md py-2 px-4 pr-8 appearance-none bg-white">
                            <option value="">Departemen</option>
                            @foreach ($departments as $department)
                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                            @endforeach
                        </select>
                        <div class="absolute right-3 top-3 pointer-events-none">
                            <svg class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                                </path>
                            </svg>
                        </div>
                    </div>

                    <div class="relative flex-1">
                        <select id="tahun-filter"
                            class="w-full border border-gray-300 rounded-md py-2 px-4 pr-8 appearance-none bg-white">
                            <option value="">Tahun Angkatan</option>
                            @foreach ($internshipClasses as $class)
                                <option value="{{ $class->id }}">{{ $class->classYear->class_year }}</option>
                            @endforeach
                        </select>
                        <div class="absolute right-3 top-3 pointer-events-none">
                            <svg class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                                </path>
                            </svg>
                        </div>
                    </div>

                    <div class="relative flex-1">
                        <select id="pembimbing-filter"
                            class="w-full border border-gray-300 rounded-md py-2 px-4 pr-8 appearance-none bg-white">
                            <option value="">Pembimbing</option>
                            @foreach ($responsibles as $responsible)
                                @if ($responsible && $responsible->user)
                                    <option value="{{ $responsible->id }}">{{ $responsible->user->name }}</option>
                                @endif
                            @endforeach
                        </select>
                        <div class="absolute right-3 top-3 pointer-events-none">
                            <svg class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                                </path>
                            </svg>
                        </div>
                    </div>

                    <div class="relative flex-1">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" placeholder="Cari"
                            class="pl-10 w-full border border-gray-300 rounded-md py-2 px-4">
                    </div>
                </div>
            </div>

            <!-- Enhanced Table -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr>
                            <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Kelas Magang
                            </th>
                            <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Stase</th>
                            <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Departemen</th>
                            <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tahun Angkatan</th>
                            <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Pembimbing</th>
                            <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Periode Rotasi</th>
                            <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($schedules as $schedule)
                            <tr>
                                <td class="py-3 px-4">{{ $schedule->internshipClass->name ?? 'N/A' }}</td>
                                <td class="py-3 px-4">{{ $schedule->stase->name ?? 'N/A' }}</td>
                                <td class="py-3 px-4">{{ $schedule->stase->departement->name ?? 'N/A' }}</td>
                                <td class="py-3 px-4">{{ $schedule->internshipClass->classYear->class_year ?? 'N/A' }}
                                </td>
                                <td class="py-3 px-4">
                                    {{ $schedule->stase->responsibleUser->user->name ?? 'N/A' }}
                                </td>
                                <td class="py-3 px-4">
                                    @if ($schedule->start_date && $schedule->end_date)
                                        {{ \Carbon\Carbon::parse($schedule->start_date)->format('d-m-Y') }} s/d
                                        {{ \Carbon\Carbon::parse($schedule->end_date)->format('d-m-Y') }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td class="py-3 px-4">
                                    <div class="flex gap-2">
                                        <a href="{{ route('admin.schedules.edit', $schedule->id) }}"
                                            class="text-blue-500">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                        </a>
                                        <form action="{{ route('admin.schedules.destroy', $schedule->id) }}"
                                            method="POST" class="inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500">
                                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Enhanced Pagination -->
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $schedules->links() }}
            </div>
        </div>
    </div>

    <script>
        function confirmDelete(event) {
            if (!confirm('Apakah Anda yakin ingin menghapus jadwal ini?')) {
                event.preventDefault();
            }
        }

        // Add this to each delete form
        document.querySelectorAll('.delete-form').forEach(form => {
            form.addEventListener('submit', confirmDelete);
        });
    </script>
@endsection
