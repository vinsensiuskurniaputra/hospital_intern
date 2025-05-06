@extends('layouts.auth')

@section('title', 'Certificate Management')

@section('content')
    <div class="p-6 space-y-6">
        <!-- Header Section -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-800">Certificate Management</h1>
                    <p class="mt-1 text-sm text-gray-500">Manage and generate student certificates</p>
                </div>
                <div class="flex gap-3">
                    <button class="px-4 py-2 text-sm font-medium text-white bg-[#637F26] rounded-lg hover:bg-[#85A832]">
                        <i class="bi bi-file-earmark-pdf mr-2"></i>Generate All
                    </button>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <!-- Filters Section -->
            <div class="p-6 border-b border-gray-100">
                <div class="flex flex-col lg:flex-row lg:items-center gap-4">
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select
                            class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#637F26]">
                            <option value="">All Status</option>
                            <option value="generated">Generated</option>
                            <option value="pending">Pending</option>
                        </select>
                    </div>
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Date Range</label>
                        <input type="date"
                            class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#637F26]">
                    </div>
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                        <div class="relative">
                            <input type="text" placeholder="Search by name or NIM..."
                                class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#637F26]">
                            <i class="bi bi-search absolute left-3 top-2.5 text-gray-400"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-y border-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Student</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Certificate Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Issue Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    {{-- <tbody class="divide-y divide-gray-100">
                        @foreach ($students as $student)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <img src="{{ asset('images/' . $student['avatar']) }}" alt="Avatar"
                                            class="w-8 h-8 rounded-full">
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-800">{{ $student['nama'] }}</p>
                                            <p class="text-xs text-gray-500">{{ $student['nim'] }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if ($student['badge'] === 'Generated')
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="bi bi-check-circle-fill mr-1"></i>Generated
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <i class="bi bi-clock-fill mr-1"></i>Pending
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $student['tanggal'] }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        @if ($student['badge'] === 'Generated')
                                            <button class="text-blue-600 hover:text-blue-700">
                                                <i class="bi bi-download text-lg"></i>
                                            </button>
                                        @endif
                                        <button class="text-[#637F26] hover:text-[#85A832]">
                                            <i class="bi bi-file-earmark-pdf text-lg"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody> --}}
                </table>
            </div>

            <!-- Enhanced Pagination -->
            <div class="px-6 py-4 border-t border-gray-100">
                <div class="flex items-center justify-between">
                    <p class="text-sm text-gray-500">Showing 1 to 5 of 25 entries</p>
                    <div class="flex gap-2">
                        <button class="px-3 py-1 text-sm text-gray-500 hover:text-[#637F26]">Previous</button>
                        <div class="flex gap-1">
                            <button class="px-3 py-1 text-sm font-medium text-white bg-[#637F26] rounded-lg">1</button>
                            <button class="px-3 py-1 text-sm text-gray-700 hover:bg-gray-100 rounded-lg">2</button>
                            <button class="px-3 py-1 text-sm text-gray-700 hover:bg-gray-100 rounded-lg">3</button>
                        </div>
                        <button class="px-3 py-1 text-sm text-gray-500 hover:text-[#637F26]">Next</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
