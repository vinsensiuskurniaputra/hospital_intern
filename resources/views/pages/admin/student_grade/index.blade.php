@extends('layouts.auth')

@section('title', 'Student Grades')

@section('content')
    <div class="p-6 space-y-6">
        <!-- Header Card -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-semibold text-gray-800">Student Grades</h2>
                    <p class="text-sm text-gray-500 mt-1">View and manage student performance</p>
                </div>
                <!-- Stats Summary -->
                <div class="flex items-center gap-3 bg-[#F5F7F0] px-4 py-2 rounded-lg">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Average Grade</p>
                        <div class="flex items-center gap-2">
                            <span class="text-2xl font-bold text-[#637F26]">82</span>
                            <span class="text-red-500 text-xs flex items-center">
                                <i class="bi bi-arrow-down-right"></i>
                                <span class="ml-1">2.5%</span>
                            </span>
                        </div>
                    </div>
                    <div class="h-12 w-px bg-gray-200"></div>
                    <div>
                        <span class="text-xs font-medium text-[#637F26] bg-white px-2 py-1 rounded">January 2024</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <!-- Filters & Actions -->
            <div class="border-b border-gray-100 p-6">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <!-- Filters -->
                    <div class="flex flex-wrap items-center gap-4">
                        <select
                            class="px-4 py-2 rounded-lg border border-gray-200 focus:border-[#637F26] focus:ring-2 focus:ring-[#637F26] min-w-[160px]">
                            <option value="">All Stase</option>
                            <!-- Add options -->
                        </select>
                        <select
                            class="px-4 py-2 rounded-lg border border-gray-200 focus:border-[#637F26] focus:ring-2 focus:ring-[#637F26] min-w-[160px]">
                            <option value="">All Departments</option>
                            <!-- Add options -->
                        </select>
                        <div class="relative">
                            <input type="text" placeholder="Search students..."
                                class="pl-10 pr-4 py-2 rounded-lg border border-gray-200 focus:border-[#637F26] focus:ring-2 focus:ring-[#637F26] w-full lg:w-[240px]">
                            <i class="bi bi-search absolute left-3 top-2.5 text-gray-400"></i>
                        </div>
                    </div>
                    <!-- Actions -->
                    <div class="flex gap-3">
                        <button
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-gray-50">
                            <i class="bi bi-upload mr-2"></i>Import CSV
                        </button>
                        <button class="px-4 py-2 text-sm font-medium text-white bg-[#637F26] rounded-lg hover:bg-[#85A832]">
                            <i class="bi bi-plus-lg mr-2"></i>Add Grade
                        </button>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-y border-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Student</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stase
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Department</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Grade
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @for ($i = 0; $i < 5; $i++)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <img class="h-8 w-8 rounded-full" src="https://ui-avatars.com/api/?name=John+Doe"
                                            alt="">
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">John Doe</div>
                                            <div class="text-sm text-gray-500">NIM: 20240{{ $i + 1 }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">General Surgery</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">Surgery</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-2 py-1 text-sm font-medium rounded-full
                                    {{ random_int(0, 1) ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                        {{ random_int(80, 100) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button class="text-[#637F26] hover:text-[#85A832] p-1">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="text-red-500 hover:text-red-600 p-1">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @endfor
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="flex items-center justify-between px-6 py-4 border-t border-gray-100">
                <div class="text-sm text-gray-500">
                    Showing 1 to 5 of 25 entries
                </div>
                <div class="flex gap-2">
                    <button class="px-3 py-1 text-sm text-gray-500 hover:text-[#637F26]" disabled>Previous</button>
                    <button class="px-3 py-1 text-sm font-medium text-white bg-[#637F26] rounded-lg">1</button>
                    <button class="px-3 py-1 text-sm text-gray-700 hover:bg-gray-100 rounded-lg">2</button>
                    <button class="px-3 py-1 text-sm text-gray-700 hover:bg-gray-100 rounded-lg">3</button>
                    <button class="px-3 py-1 text-sm text-gray-500 hover:text-[#637F26]">Next</button>
                </div>
            </div>
        </div>
    </div>
@endsection
