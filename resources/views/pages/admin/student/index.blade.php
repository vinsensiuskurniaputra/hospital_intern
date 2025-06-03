@extends('layouts.auth')

@section('title', 'Students Management')

@section('content')
    <div x-data="{ addStudent: false, showImportModal: false }">
        <!-- Notification Messages -->
        <div class="fixed top-20 right-4 z-50 w-96 space-y-4">
            <!-- Success Message -->
            @if (session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
                    class="flex items-center p-4 bg-green-50 border-l-4 border-green-500 rounded-lg shadow-lg">
                    <div class="flex-shrink-0">
                        <i class="bi bi-check-circle text-green-500 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                    </div>
                    <button @click="show = false" class="ml-auto text-green-500 hover:text-green-600">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
            @endif

            <!-- Error Message -->
            @if (session('error') || $errors->any())
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
                    class="flex items-center p-4 bg-red-50 border-l-4 border-red-500 rounded-lg shadow-lg">
                    <div class="flex-shrink-0">
                        <i class="bi bi-exclamation-circle text-red-500 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800">
                            {{ session('error') ?? 'Ada yang salah dalam masukan Anda!' }}
                        </p>
                        @error('file')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <button @click="show = false" class="ml-auto text-red-500 hover:text-red-600">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
            @endif
        </div>

        <div class="p-6 space-y-6">
            <!-- Summary Cards -->
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <h1 class="text-2xl text-gray-800 pb-6">Mahasiswa</h1>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Total Students -->
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Total Mahasiswa</p>
                            <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $studentCount }}</h3>
                        </div>
                        <div class="p-3 bg-[#F5F7F0] rounded-lg">
                            <i class="bi bi-people text-xl text-[#637F26]"></i>
                        </div>
                    </div>

                    <!-- Total Departments -->
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Program Studi</p>
                            <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $studyPrograms->count() }}</h3>
                        </div>
                        <div class="p-3 bg-[#F5F7F0] rounded-lg">
                            <i class="bi bi-buildings text-xl text-[#637F26]"></i>
                        </div>
                    </div>

                    <!-- Total Campuses -->
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Kampus</p>
                            <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $campuses->count() }}</h3>
                        </div>
                        <div class="p-3 bg-[#F5F7F0] rounded-lg">
                            <i class="bi bi-building text-xl text-[#637F26]"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <!-- Card Header -->
                <div class="border-b border-gray-100 p-6">
                    <div class="flex flex-col lg:items-center lg:justify-between gap-4">
                        <!-- Search & Filters -->
                        <div class="flex w-full overflow-x-auto items-center gap-4">
                            <!-- Filter & Search Section -->
                            <form method="GET" action="{{ route('students.filter') }}" class="w-full">
                                <div class="flex flex-col lg:flex-row lg:items-center gap-4">
                                    <!-- Study Program Filter -->
                                    <div class="flex-1">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Program Studi</label>
                                        <select name="study_program"
                                            class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#637F26]">
                                            <option value="">Semua Program Studi</option>
                                            @foreach ($studyPrograms as $studyProgram)
                                                <option value="{{ $studyProgram->id }}"
                                                    {{ request('study_program') == $studyProgram->id ? 'selected' : '' }}>
                                                    {{ $studyProgram->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Class Year Filter -->
                                    <div class="flex-1">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Tahun Kelas</label>
                                        <select name="class_year"
                                            class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#637F26]">
                                            <option value="">Semua Tahun</option>
                                            @foreach ($classYears as $classYear)
                                                <option value="{{ $classYear->class_year }}"
                                                    {{ request('class_year') == $classYear->class_year ? 'selected' : '' }}>
                                                    {{ $classYear->class_year }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Campus Filter -->
                                    <div class="flex-1">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Kampus</label>
                                        <select name="campus"
                                            class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#637F26]">
                                            <option value="">Semua Kampus</option>
                                            @foreach ($campuses as $campus)
                                                <option value="{{ $campus->id }}"
                                                    {{ request('campus') == $campus->id ? 'selected' : '' }}>
                                                    {{ $campus->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Status Filter -->
                                    <div class="flex-1">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                        <select name="status"
                                            class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#637F26]">
                                            <option value="">Semua Status</option>
                                            <option value="finished"
                                                {{ request('status') == 'finished' ? 'selected' : '' }}>Selesai
                                            </option>
                                            <option value="unfinished"
                                                {{ request('status') == 'unfinished' ? 'selected' : '' }}>Belum Selesai
                                            </option>
                                        </select>
                                    </div>

                                    <!-- Search -->
                                    <div class="flex-1">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Pencarian</label>
                                        <div class="relative">
                                            <input type="text" name="search" value="{{ request('search') }}"
                                                placeholder="Cari berdasarkan nama atau NIM..."
                                                class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#637F26]">
                                            <i class="bi bi-search absolute left-3 top-2.5 text-gray-400"></i>
                                        </div>
                                    </div>

                                    <!-- Submit Button -->
                                    <div>
                                        <button type="submit"
                                            class="px-4 py-2 text-sm font-medium text-white bg-[#637F26] rounded-lg hover:bg-[#85A832]">
                                            Filter
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="table-auto  ">
                        <thead class="bg-gray-50 border-y border-gray-100">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Mahasiswa</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">NIM</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kelas</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Program Studi
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kampus</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tahun</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody id="studentTableBody" class="divide-y divide-gray-100">
                            @include('components.admin.student.table', [
                                'students' => $students,
                            ])
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @include('components.general.pagination', [
                    'datas' => $students->appends(request()->except('page')), // Preserve filter values
                ])


            </div>
        </div>
        @include('components.general.import_modal', [
            'show' => 'showImportModal',
            'title' => 'Students',
            'description' => 'Upload your CSV file to import student data',
            'action' => route('students.import'),
            'template_url' => route('students.downloadTemplate'),
        ])

        @include('components.admin.student.add', [
            'show' => 'addStudent',
            'campuses' => $campuses,
            'studyPrograms' => $studyPrograms,
            'classYears' => $classYears,
        ])
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            let currentFilters = {
                study_program: new URLSearchParams(window.location.search).get('study_program') || '',
                class_year: new URLSearchParams(window.location.search).get('class_year') || '',
                campus: new URLSearchParams(window.location.search).get('campus') || '',
                status: new URLSearchParams(window.location.search).get('status') || '',
                search: new URLSearchParams(window.location.search).get('search') || ''
            };

            $('#studyProgramFilter').val(currentFilters.study_program);
            $('#classYearFilter').val(currentFilters.class_year);
            $('#campusFilter').val(currentFilters.campus);
            $('#statusFilter').val(currentFilters.status);
            $('#searchStudent').val(currentFilters.search);

            function fetchStudents(page = 1) {
                const filters = {
                    ...currentFilters,
                    page: page
                };
                Object.keys(filters).forEach(key => {
                    if (!filters[key]) delete filters[key];
                });

                $.ajax({
                    url: "{{ route('students.filter') }}",
                    type: "GET",
                    data: filters,
                    success: function(response) {
                        $('#studentTableBody').html(response);
                        const queryString = new URLSearchParams(filters).toString();
                        const newUrl =
                            `${window.location.pathname}${queryString ? '?' + queryString : ''}`;
                        window.history.pushState({}, '', newUrl);
                    }
                });
            }

            $('.filter-input').on('change keyup', function() {
                const newFilters = {
                    study_program: $('#studyProgramFilter').val(),
                    class_year: $('#classYearFilter').val(),
                    campus: $('#campusFilter').val(),
                    status: $('#statusFilter').val(),
                    search: $('#searchStudent').val()
                };

                if (JSON.stringify(newFilters) !== JSON.stringify(currentFilters)) {
                    currentFilters = newFilters;
                    fetchStudents(1);
                }
            });

            $(document).on('click', '.pagination a', function(e) {
                e.preventDefault();
                const url = new URL($(this).attr('href'));
                const page = url.searchParams.get('page');
                fetchStudents(page);
            });
        });
    </script>

@endsection
