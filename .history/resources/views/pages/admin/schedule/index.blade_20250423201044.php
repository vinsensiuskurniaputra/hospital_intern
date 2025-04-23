@extends('layouts.auth')

@section('title', 'Schedule Management')

@section('content')
    <div class="p-6 bg-white rounded-lg shadow-sm">
        <h1 class="text-2xl font-medium text-gray-700 mb-6">Jadwal</h1>

        <!-- Calendar and Class Cards Section -->
        <div class="flex gap-6 mb-6">
            <!-- Calendar Section -->
            <div class="w-1/3 bg-white p-4 rounded-lg shadow-sm">
                <div class="flex items-center mb-4">
                    <div class="relative mr-2">
                        <select id="month-select" class="border border-gray-300 rounded-md py-1 px-2 pr-8 appearance-none bg-white text-sm">
                            <option>November</option>
                            <option>December</option>
                            <option>Januari</option>
                        </select>
                        <div class="absolute right-2 top-2 pointer-events-none">
                            <svg class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="relative">
                        <select id="year-select" class="border border-gray-300 rounded-md py-1 px-2 pr-8 appearance-none bg-white text-sm">
                            <option>2024</option>
                            <option>2025</option>
                        </select>
                        <div class="absolute right-2 top-2 pointer-events-none">
                            <svg class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Calendar Grid -->
                <div class="mb-4">
                    <div class="grid grid-cols-7 text-center mb-1">
                        <div class="text-xs font-medium text-gray-500">Min</div>
                        <div class="text-xs font-medium text-gray-500">Sen</div>
                        <div class="text-xs font-medium text-gray-500">Sel</div>
                        <div class="text-xs font-medium text-gray-500">Rab</div>
                        <div class="text-xs font-medium text-gray-500">Kam</div>
                        <div class="text-xs font-medium text-gray-500">Jum</div>
                        <div class="text-xs font-medium text-gray-500">Sab</div>
                    </div>

                    <div class="grid grid-cols-7 gap-1">
                        <!-- Calendar days dengan ukuran yang lebih kecil -->
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

                <div class="flex justify-end gap-2">
                    <button class="px-2 py-1 text-sm border border-gray-300 rounded-md hover:bg-gray-50">Cancel</button>
                    <button class="px-2 py-1 text-sm bg-green-600 text-white rounded-md hover:bg-green-700">Done</button>
                </div>
            </div>

            <!-- Class Cards -->
            <div class="w-2/3 grid grid-cols-2 gap-4">
                <!-- Class Card 01 -->
                <div class="bg-gray-50 p-4 rounded-lg shadow-sm hover:shadow-md transition-shadow">
                    <h3 class="text-lg font-medium mb-1">Kelas FK-01</h3>
                    <div class="flex items-center text-gray-500 mb-1">
                        <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>10:00 - 14:00</span>
                    </div>
                    <div class="text-gray-500">Departemen Kesehatan</div>
                </div>

                <!-- Class Card 02 -->
                <div class="bg-gray-50 p-4 rounded-lg shadow-sm hover:shadow-md transition-shadow">
                    <h3 class="text-lg font-medium mb-1">Kelas FK-02</h3>
                    <div class="flex items-center text-gray-500 mb-1">
                        <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>10:00 - 14:00</span>
                    </div>
                    <div class="text-gray-500">Departemen Kesehatan</div>
                </div>

                <!-- Class Card 03 -->
                <div class="bg-gray-50 p-4 rounded-lg shadow-sm hover:shadow-md transition-shadow">
                    <h3 class="text-lg font-medium mb-1">Kelas FK-03</h3>
                    <div class="flex items-center text-gray-500 mb-1">
                        <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>10:00 - 14:00</span>
                    </div>
                    <div class="text-gray-500">Departemen Kesehatan</div>
                </div>

                <!-- Class Card 04 -->
                <div class="bg-gray-50 p-4 rounded-lg shadow-sm hover:shadow-md transition-shadow">
                    <h3 class="text-lg font-medium mb-1">Kelas FK-04</h3>
                    <div class="flex items-center text-gray-500 mb-1">
                        <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>10:00 - 14:00</span>
                    </div>
                    <div class="text-gray-500">Departemen Kesehatan</div>
                </div>

                <!-- Class Card 05 -->
                <div class="bg-gray-50 p-4 rounded-lg shadow-sm hover:shadow-md transition-shadow">
                    <h3 class="text-lg font-medium mb-1">Kelas FK-05</h3>
                    <div class="flex items-center text-gray-500 mb-1">
                        <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>10:00 - 14:00</span>
                    </div>
                    <div class="text-gray-500">Departemen Kesehatan</div>
                </div>

                <!-- Class Card 06 -->
                <div class="bg-gray-50 p-4 rounded-lg shadow-sm hover:shadow-md transition-shadow">
                    <h3 class="text-lg font-medium mb-1">Kelas FK-06</h3>
                    <div class="flex items-center text-gray-500 mb-1">
                        <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>10:00 - 14:00</span>
                    </div>
                    <div class="text-gray-500">Departemen Kesehatan</div>
                </div>
            </div>
        </div>

        <!-- Search Filter Section -->
        <div class="bg-white p-4 rounded-lg mb-6">
            <div class="mb-4">
                <h2 class="text-lg font-medium">Search Filter</h2>
            </div>

            <div class="flex flex-wrap gap-4 mb-4">
                <!-- Filter Dropdowns -->
                <div class="relative flex-1">
                    <select id="departemen-filter" class="w-full border border-gray-300 rounded-md py-2 px-4 pr-8 appearance-none bg-white">
                        <option value="">Departemen</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                        @endforeach
                    </select>
                    <div class="absolute right-3 top-3 pointer-events-none">
                        <svg class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </div>

                <div class="relative flex-1">
                    <select id="tahun-filter" class="w-full border border-gray-300 rounded-md py-2 px-4 pr-8 appearance-none bg-white">
                        <option>Tahun Angkatan</option>
                        <option>2024/2025</option>
                        <option>2025/2026</option>
                    </select>
                    <div class="absolute right-3 top-3 pointer-events-none">
                        <svg class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </div>

                <div class="relative flex-1">
                    <select id="pembimbing-filter" class="w-full border border-gray-300 rounded-md py-2 px-4 pr-8 appearance-none bg-white">
                        <option value="">Pembimbing</option>
                        @foreach($responsible_users as $responsible)
                            <option value="{{ $responsible->id }}">{{ $responsible->user->name }}</option>
                        @endforeach
                    </select>
                    <div class="absolute right-3 top-3 pointer-events-none">
                        <svg class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap gap-4 mb-6">
                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input type="text" placeholder="Cari" class="pl-10 w-full border border-gray-300 rounded-md py-2 px-4">
                </div>

                <div class="relative">
                    <select id="rows-per-page" class="border border-gray-300 rounded-md py-2 px-4 pr-8 appearance-none bg-white">
                        <option>5</option>
                        <option>10</option>
                        <option>15</option>
                    </select>
                    <div class="absolute right-3 top-3 pointer-events-none">
                        <svg class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </div>

                <a href="add_schedule.php" class="flex items-center gap-1 bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded-md">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Tambah Jadwal
                </a>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead>
                        <tr>
                            <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center cursor-pointer sort-header" data-column="kelas">
                                    Kelas Magang
                                    <div class="ml-1 flex flex-col sort-icon">
                                        <svg class="h-2 w-2 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 8l-6 6 1.41 1.41L12 10.83l4.59 4.58L18 14z"/>
                                        </svg>
                                        <svg class="h-2 w-2 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M16.59 8.59L12 13.17 7.41 8.59 6 10l6 6 6-6z"/>
                                        </svg>
                                    </div>
                                </div>
                            </th>
                            <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stase</th>
                            <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center cursor-pointer sort-header" data-column="departemen">
                                    Departemen
                                    <svg class="ml-1 h-4 w-4 text-gray-400 sort-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4"></path>
                                    </svg>
                                </div>
                            </th>
                            <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center cursor-pointer sort-header" data-column="tahun">
                                    Tahun Angkatan
                                    <svg class="ml-1 h-4 w-4 text-gray-400 sort-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4"></path>
                                    </svg>
                                </div>
                            </th>
                            <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center cursor-pointer sort-header" data-column="pembimbing">
                                    Pembimbing
                                    <svg class="ml-1 h-4 w-4 text-gray-400 sort-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4"></path>
                                    </svg>
                                </div>
                            </th>
                            <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Periode Rotasi
                            </th>
                            <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center cursor-pointer sort-header" data-column="jam">
                                    Jam Mulai
                                    <svg class="ml-1 h-4 w-4 text-gray-400 sort-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4"></path>
                                    </svg>
                                </div>
                            </th>
                            <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($schedules as $schedule)
                            <tr>
                                <td class="py-3 px-4">{{ $schedule->internshipClass->name }}</td>
                                <td class="py-3 px-4">{{ $schedule->stase }}</td>
                                <td class="py-3 px-4">{{ $schedule->departement->name }}</td>
                                <td class="py-3 px-4">{{ $schedule->internshipClass->classYear->class_year }}</td>
                                <td class="py-3 px-4">{{ $schedule->responsibleUser->user->name }}</td>
                                <td class="py-3 px-4">{{ \Carbon\Carbon::parse($schedule->rotation_period_start)->format('d-m-Y') }} s/d {{ \Carbon\Carbon::parse($schedule->rotation_period_end)->format('d-m-Y') }}</td>
                                <td class="py-3 px-4">{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}</td>
                                <td class="py-3 px-4">
                                    <div class="flex gap-2">
                                        <a href="{{ route('admin.schedules.edit', $schedule->id) }}" class="text-blue-500">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>
                                        <form action="{{ route('admin.schedules.destroy', $schedule->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500" onclick="return confirm('Are you sure?')">
                                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
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

            <!-- Pagination -->
            <div class="flex items-center justify-center mt-4">
                {{ $schedules->links() }}
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Sorting functionality
            const sortHeaders = document.querySelectorAll('.sort-header');
            
            sortHeaders.forEach(header => {
                let sortDirection = ''; // Default no sort
                const column = header.dataset.column;
                const icon = header.querySelector('.sort-icon');
                
                header.addEventListener('click', function() {
                    // Reset all other headers
                    sortHeaders.forEach(h => {
                        if (h !== header) {
                            h.querySelector('.sort-icon').innerHTML = `
                                <svg class="h-2 w-2 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 8l-6 6 1.41 1.41L12 10.83l4.59 4.58L18 14z"/>
                                </svg>
                                <svg class="h-2 w-2 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M16.59 8.59L12 13.17 7.41 8.59 6 10l6 6 6-6z"/>
                                </svg>
                            `;
                        }
                    });

                    // Change sort direction and icon on click
                    if (sortDirection === '') {
                        sortDirection = 'asc';
                        icon.innerHTML = `
                            <svg class="h-2 w-2 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 8l-6 6 1.41 1.41L12 10.83l4.59 4.58L18 14z"/>
                            </svg>
                        `;
                    } else if (sortDirection === 'asc') {
                        sortDirection = 'desc';
                        icon.innerHTML = `
                            <svg class="h-2 w-2 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M16.59 8.59L12 13.17 7.41 8.59 6 10l6 6 6-6z"/>
                            </svg>
                        `;
                    } else {
                        sortDirection = '';
                        icon.innerHTML = `
                            <svg class="h-2 w-2 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 8l-6 6 1.41 1.41L12 10.83l4.59 4.58L18 14z"/>
                            </svg>
                            <svg class="h-2 w-2 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M16.59 8.59L12 13.17 7.41 8.59 6 10l6 6 6-6z"/>
                            </svg>
                        `;
                    }
                    
                    // Sort the table
                    if (sortDirection) {
                        sortTable(column, sortDirection);
                    }
                });
            });

            // Initialize sorting icons
            document.querySelectorAll('.sort-header').forEach(header => {
                const icon = header.querySelector('.sort-icon');
                icon.innerHTML = `
                    <svg class="h-2 w-2 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 8l-6 6 1.41 1.41L12 10.83l4.59 4.58L18 14z"/>
                    </svg>
                    <svg class="h-2 w-2 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M16.59 8.59L12 13.17 7.41 8.59 6 10l6 6 6-6z"/>
                    </svg>
                `;
            });
            
            function sortTable(column, direction) {
                const table = document.querySelector('table');
                const tbody = table.querySelector('tbody');
                const rows = Array.from(tbody.querySelectorAll('tr'));
                
                // Get the column index based on the column name
                let columnIndex;
                switch(column) {
                    case 'kelas':
                        columnIndex = 0;
                        break;
                    case 'departemen':
                        columnIndex = 2;
                        break;
                    case 'tahun':
                        columnIndex = 3;
                        break;
                    case 'pembimbing':
                        columnIndex = 4;
                        break;
                    case 'jam':
                        columnIndex = 6;
                        break;
                    default:
                        columnIndex = 0;
                }
                
                // Sort the rows
                rows.sort((a, b) => {
                    const cellA = a.cells[columnIndex].textContent.trim();
                    const cellB = b.cells[columnIndex].textContent.trim();
                    
                    // Check if the content is a number
                    const numA = parseFloat(cellA);
                    const numB = parseFloat(cellB);
                    
                    if (!isNaN(numA) && !isNaN(numB)) {
                        return direction === 'asc' ? numA - numB : numB - numA;
                    } else {
                        // Sort as strings
                        return direction === 'asc' 
                            ? cellA.localeCompare(cellB) 
                            : cellB.localeCompare(cellA);
                    }
                });
                
                // Remove all current rows
                while (tbody.firstChild) {
                    tbody.removeChild(tbody.firstChild);
                }
                
                // Add sorted rows back to the table
                rows.forEach(row => {
                    tbody.appendChild(row);
                });
            }
            
            // Calendar functionality
            const monthSelect = document.getElementById('month-select');
            const yearSelect = document.getElementById('year-select');
            const calendarDays = document.querySelectorAll('.grid-cols-7 div:not(.text-gray-500)');
            
            // Highlight the selected day
            calendarDays.forEach(day => {
                day.addEventListener('click', function() {
                    // Remove highlight from previously selected day
                    document.querySelectorAll('.grid-cols-7 div').forEach(d => {
                        d.classList.remove('bg-green-600', 'text-white', 'rounded-full');
                    });
                    
                    // Add highlight to selected day
                    this.classList.add('bg-green-600', 'text-white', 'rounded-full');
                    
                    // Here you can add code to load schedules for the selected day
                    loadSchedulesForDay(this.textContent.trim(), monthSelect.value, yearSelect.value);
                });
            });
            
            // Month and year selection
            monthSelect.addEventListener('change', updateCalendar);
            yearSelect.addEventListener('change', updateCalendar);
            
            function updateCalendar() {
                const month = monthSelect.value;
                const year = yearSelect.value;
                
                // Here you would update the calendar grid with the appropriate days
                // for the selected month and year
                console.log(`Updating calendar for ${month} ${year}`);
                
                // This would typically involve an AJAX call to get the data
                // and then rebuilding the calendar grid
            }
            
            function loadSchedulesForDay(day, month, year) {
                // Here you would load schedules for the selected day
                console.log(`Loading schedules for ${day} ${month} ${year}`);
                
                // This would typically involve an AJAX call to get the data
                // and then updating the class cards section
            }
            
            // Filter functionality
            const departmentFilter = document.getElementById('departemen-filter');
            const yearFilter = document.getElementById('tahun-filter');
            const instructorFilter = document.getElementById('pembimbing-filter');
            const searchInput = document.querySelector('input[placeholder="Search"]');
            const rowsPerPage = document.getElementById('rows-per-page');
            
            // Add event listeners for filters
            departmentFilter.addEventListener('change', applyFilters);
            yearFilter.addEventListener('change', applyFilters);
            instructorFilter.addEventListener('change', applyFilters);
            searchInput.addEventListener('input', applyFilters);
            rowsPerPage.addEventListener('change', updatePagination);
            
            function applyFilters() {
                const department = departmentFilter.value;
                const year = yearFilter.value;
                const instructor = instructorFilter.value;
                const searchTerm = searchInput.value;
                
                // Kirim request AJAX
                fetch(`/admin/schedules/filter?department=${department}&year=${year}&instructor=${instructor}&search=${searchTerm}`)
                    .then(response => response.text())
                    .then(html => {
                        document.querySelector('tbody').innerHTML = html;
                    });
            }
            
            // Pagination functionality
            const prevPageBtn = document.querySelector('button:contains("Previous")');
            const nextPageBtn = document.querySelector('button:contains("Next")');
            const pageButtons = document.querySelectorAll('.pagination button:not(:first-child):not(:last-child)');
            
            function updatePagination() {
                const perPage = parseInt(rowsPerPage.value);
                const visibleRows = Array.from(document.querySelectorAll('tbody tr')).filter(row => 
                    row.style.display !== 'none'
                );
                const totalPages = Math.ceil(visibleRows.length / perPage);
                
                // Update page buttons
                // This is a simplified version - in a real app you'd dynamically create the page buttons
                pageButtons.forEach((button, index) => {
                    if (index < totalPages) {
                        button.style.display = '';
                    } else {
                        button.style.display = 'none';
                    }
                });
                
                // Show/hide rows based on current page
                // Assuming page 1 is the default
                const currentPage = 1;
                visibleRows.forEach((row, index) => {
                    if (index >= (currentPage - 1) * perPage && index < currentPage * perPage) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }
            
            // Initialize sorting icons for better UX
            document.querySelectorAll('.sort-header').forEach(header => {
                const icon = header.querySelector('.sort-icon');
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4"></path>';
            });
            
            // Add event listeners for pagination
            document.querySelectorAll('.pagination button').forEach(button => {
                button.addEventListener('click', function() {
                    // Handle pagination
                    // This would update the current page and show/hide rows accordingly
                });
            });
        });
    </script>
@endsection