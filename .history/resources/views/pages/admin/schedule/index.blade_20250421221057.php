@extends('layouts.auth')

@section('title', 'Schedule Management')

@section('content')
    <div class="p-6 bg-white rounded-lg shadow-sm">
        <h1 class="text-2xl font-medium text-gray-700 mb-6">Jadwal</h1>

        <div class="flex flex-wrap gap-4">
            <!-- Calendar Section - Now Smaller -->
            <div class="bg-white p-4 rounded-lg mb-6 w-64 border border-gray-200">
                <div class="flex items-center justify-between mb-2">
                    <div class="text-xs text-gray-500">GMT+7</div>
                    <div class="flex gap-1">
                        <div class="relative">
                            <select class="border border-gray-300 rounded-md py-1 px-2 text-sm appearance-none bg-white">
                                <option>November</option>
                            </select>
                            <div class="absolute right-2 top-2 pointer-events-none">
                                <svg class="h-3 w-3 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="relative">
                            <select class="border border-gray-300 rounded-md py-1 px-2 text-sm appearance-none bg-white">
                                <option>2024</option>
                            </select>
                            <div class="absolute right-2 top-2 pointer-events-none">
                                <svg class="h-3 w-3 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Calendar Grid -->
                <div class="mb-3">
                    <div class="grid grid-cols-7 gap-1 text-center mb-1">
                        <div class="text-xs text-gray-500">Su</div>
                        <div class="text-xs text-gray-500">Mo</div>
                        <div class="text-xs text-gray-500">Tu</div>
                        <div class="text-xs text-gray-500">We</div>
                        <div class="text-xs text-gray-500">Th</div>
                        <div class="text-xs text-gray-500">Fr</div>
                        <div class="text-xs text-gray-500">Sa</div>
                    </div>

                    <div class="grid grid-cols-7 gap-1">
                        <div class="h-6 flex items-center justify-center text-xs text-gray-400">30</div>
                        <div class="h-6 flex items-center justify-center text-xs text-gray-400">31</div>
                        <div class="h-6 flex items-center justify-center text-xs">1</div>
                        <div class="h-6 flex items-center justify-center text-xs">2</div>
                        <div class="h-6 flex items-center justify-center text-xs">3</div>
                        <div class="h-6 flex items-center justify-center text-xs">4</div>
                        <div class="h-6 flex items-center justify-center text-xs">5</div>

                        <div class="h-6 flex items-center justify-center text-xs">6</div>
                        <div class="h-6 flex items-center justify-center text-xs">7</div>
                        <div class="h-6 flex items-center justify-center text-xs">8</div>
                        <div class="h-6 flex items-center justify-center text-xs">9</div>
                        <div class="h-6 flex items-center justify-center text-xs bg-green-600 text-white rounded-full">10</div>
                        <div class="h-6 flex items-center justify-center text-xs">11</div>
                        <div class="h-6 flex items-center justify-center text-xs">12</div>

                        <div class="h-6 flex items-center justify-center text-xs">13</div>
                        <div class="h-6 flex items-center justify-center text-xs">14</div>
                        <div class="h-6 flex items-center justify-center text-xs">15</div>
                        <div class="h-6 flex items-center justify-center text-xs">16</div>
                        <div class="h-6 flex items-center justify-center text-xs">17</div>
                        <div class="h-6 flex items-center justify-center text-xs">18</div>
                        <div class="h-6 flex items-center justify-center text-xs">19</div>

                        <div class="h-6 flex items-center justify-center text-xs">20</div>
                        <div class="h-6 flex items-center justify-center text-xs">21</div>
                        <div class="h-6 flex items-center justify-center text-xs">22</div>
                        <div class="h-6 flex items-center justify-center text-xs">23</div>
                        <div class="h-6 flex items-center justify-center text-xs">24</div>
                        <div class="h-6 flex items-center justify-center text-xs">25</div>
                        <div class="h-6 flex items-center justify-center text-xs">26</div>

                        <div class="h-6 flex items-center justify-center text-xs">27</div>
                        <div class="h-6 flex items-center justify-center text-xs">28</div>
                        <div class="h-6 flex items-center justify-center text-xs">29</div>
                        <div class="h-6 flex items-center justify-center text-xs">30</div>
                        <div class="h-6 flex items-center justify-center text-xs text-gray-400">1</div>
                        <div class="h-6 flex items-center justify-center text-xs text-gray-400">2</div>
                        <div class="h-6 flex items-center justify-center text-xs text-gray-400">3</div>
                    </div>
                </div>

                <div class="flex justify-end gap-2">
                    <button class="px-2 py-1 text-xs border border-gray-300 rounded-md hover:bg-gray-50">Cancel</button>
                    <button class="px-2 py-1 text-xs bg-green-600 text-white rounded-md hover:bg-green-700">Done</button>
                </div>
            </div>

            <!-- Class Cards Side by Side -->
            <div class="flex flex-wrap gap-4 mb-6">
                <!-- Class Card 01 -->
                <div class="bg-gray-50 p-4 rounded-lg w-64">
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
                <div class="bg-gray-50 p-4 rounded-lg w-64">
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
                <div class="bg-gray-50 p-4 rounded-lg w-64">
                    <h3 class="text-lg font-medium mb-1">Kelas FK-03</h3>
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

            <div class="flex gap-4 mb-4">
                <!-- Filter Dropdowns -->
                <div class="relative flex-1">
                    <select class="w-full border border-gray-300 rounded-md py-2 px-4 pr-8 appearance-none bg-white">
                        <option>Departemen</option>
                    </select>
                    <div class="absolute right-3 top-3 pointer-events-none">
                        <svg class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </div>

                <div class="relative flex-1">
                    <select class="w-full border border-gray-300 rounded-md py-2 px-4 pr-8 appearance-none bg-white">
                        <option>Tahun Angkatan</option>
                    </select>
                    <div class="absolute right-3 top-3 pointer-events-none">
                        <svg class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </div>

                <div class="relative flex-1">
                    <select class="w-full border border-gray-300 rounded-md py-2 px-4 pr-8 appearance-none bg-white">
                        <option>Pembimbing</option>
                    </select>
                    <div class="absolute right-3 top-3 pointer-events-none">
                        <svg class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="flex gap-4 mb-6">
                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input type="text" placeholder="Search" class="pl-10 w-full border border-gray-300 rounded-md py-2 px-4">
                </div>

                <div class="relative">
                    <select class="border border-gray-300 rounded-md py-2 px-4 pr-8 appearance-none bg-white">
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
                                <div class="flex items-center">
                                    Kelas Magang
                                    <svg class="ml-1 h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4"></path>
                                    </svg>
                                </div>
                            </th>
                            <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stase</th>
                            <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center">
                                    Departemen
                                    <svg class="ml-1 h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4"></path>
                                    </svg>
                                </div>
                            </th>
                            <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center">
                                    Tahun Angkatan
                                    <svg class="ml-1 h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4"></path>
                                    </svg>
                                </div>
                            </th>
                            <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center">
                                    Pembimbing
                                    <svg class="ml-1 h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4"></path>
                                    </svg>
                                </div>
                            </th>
                            <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center">
                                    Periode Rotasi
                                </div>
                            </th>
                            <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center">
                                    Jam Mulai
                                    <svg class="ml-1 h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4"></path>
                                    </svg>
                                </div>
                            </th>
                            <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <!-- Row 1 -->
                        <tr>
                            <td class="py-3 px-4">FK - 01</td>
                            <td class="py-3 px-4">1</td>
                            <td class="py-3 px-4">Poliklinik Ortopedi</td>
                            <td class="py-3 px-4">2025/2026</td>
                            <td class="py-3 px-4">dr. Tirta Mandira Hudhi</td>
                            <td class="py-3 px-4">1-9-2025 s/d 1-11-2025</td>
                            <td class="py-3 px-4">10:00 - 14:00</td>
                            <td class="py-3 px-4">
                                <div class="flex gap-2">
                                    <a href="edit_schedule.php" class="text-blue-500">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    <a href="#" class="text-gray-500">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </a>
                                    <a href="delete_schedule.php" class="text-red-500">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <!-- Row 2 -->
                        <tr>
                            <td class="py-3 px-4">FK - 02</td>
                            <td class="py-3 px-4">2</td>
                            <td class="py-3 px-4">Poliklinik Umum</td>
                            <td class="py-3 px-4">2025/2026</td>
                            <td class="py-3 px-4">dr. Dion Haryadi</td>
                            <td class="py-3 px-4">1-9-2025 s/d 1-12-2025</td>
                            <td class="py-3 px-4">10:00 - 14:00</td>
                            <td class="py-3 px-4">
                                <div class="flex gap-2">
                                    <a href="edit_schedule.php" class="text-blue-500">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    <a href="#" class="text-gray-500">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </a>
                                    <a href="delete_schedule.php" class="text-red-500">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="flex items-center justify-center mt-4">
                <button class="px-3 py-1 border border-gray-300 rounded-md text-sm text-gray-500">Previous</button>
                <button class="px-3 py-1 bg-green-600 text-white rounded-md mx-1 text-sm">1</button>
                <button class="px-3 py-1 border border-gray-300 rounded-md mx-1 text-sm">2</button>
                <button class="px-3 py-1 border border-gray-300 rounded-md mx-1 text-sm">3</button>
                <button class="px-3 py-1 border border-gray-300 rounded-md text-sm text-gray-500">Next</button>
            </div>
        </div>
    </div>
@endsection