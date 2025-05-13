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
                            {{ session('error') ?? 'here is something wrong in your input !' }}
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
                <h1 class="text-2xl text-gray-800 pb-6">Students</h1>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Total Students -->
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Total Students</p>
                            <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $studentCount }}</h3>
                        </div>
                        <div class="p-3 bg-[#F5F7F0] rounded-lg">
                            <i class="bi bi-people text-xl text-[#637F26]"></i>
                        </div>
                    </div>

                    <!-- Total Departments -->
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Program Study</p>
                            <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $studyPrograms->count() }}</h3>
                        </div>
                        <div class="p-3 bg-[#F5F7F0] rounded-lg">
                            <i class="bi bi-buildings text-xl text-[#637F26]"></i>
                        </div>
                    </div>

                    <!-- Total Campuses -->
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Campuses</p>
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
                            <!-- Filters -->
                            <select id="studyProgramFilter"
                                class="px-4 min-w-[100px] py-2 w-full rounded-lg border border-gray-200">
                                <option value="">All Program Study</option>
                                @foreach ($studyPrograms as $studyProgram)
                                    <option value="{{ $studyProgram->id }}">{{ $studyProgram->name }}</option>
                                @endforeach
                            </select>

                            <select id="classYearFilter"
                                class="px-4 min-w-[100px] py-2 w-full rounded-lg border border-gray-200">
                                <option value="">All Years</option>
                                @foreach ($classYears as $classYear)
                                    <option value="{{ $classYear->class_year }}">{{ $classYear->class_year }}</option>
                                @endforeach
                            </select>

                            <select id="campusFilter"
                                class="px-4 min-w-[100px] py-2 w-full rounded-lg border border-gray-200">
                                <option value="">All Campuses</option>
                                @foreach ($campuses as $campus)
                                    <option value="{{ $campus->id }}">{{ $campus->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <!-- Action Buttons -->
                        <div class="flex flex-col lg:flex-row w-full justify-between gap-3">
                            <!-- Search -->
                            <div class="w-full lg:w-[360px]">
                                <div class="relative">
                                    <input type="text" id="searchStudent" placeholder="Search students..."
                                        class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-200 focus:border-[#637F26] focus:ring-2 focus:ring-[#637F26]">
                                    <i class="bi bi-search absolute left-3 top-2.5 text-gray-400"></i>
                                </div>
                            </div>
                            <div class="flex gap-3">
                                <button @click="showImportModal = true"
                                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-gray-50">
                                    <i class="bi bi-upload mr-2"></i>Import CSV
                                </button>
                                <button @click="addStudent = true"
                                    class="px-4 py-2 text-sm font-medium text-white bg-[#637F26] rounded-lg hover:bg-[#85A832]">
                                    <i class="bi bi-plus-lg mr-2"></i>Add Student
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="table-auto  ">
                        <thead class="bg-gray-50 border-y border-gray-100">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Student</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">NIM</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Class</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Program Study
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Campus</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Year</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
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
                <div class="flex items-center justify-between px-6 py-4 border-t border-gray-100">
                    <div class="text-sm text-gray-500">
                        Showing {{ $students->firstItem() }} to {{ $students->lastItem() }} of {{ $students->total() }}
                        entries
                    </div>
                    <div class="flex items-center gap-2">
                        <!-- Previous Button -->
                        @if ($students->onFirstPage())
                            <button class="px-3 py-1 text-sm text-gray-400 disabled:opacity-50" disabled>
                                Previous
                            </button>
                        @else
                            <a href="{{ $students->previousPageUrl() }}"
                                class="px-3 py-1 text-sm text-gray-500 hover:text-gray-600">
                                Previous
                            </a>
                        @endif

                        <!-- Pagination Numbers -->
                        <div class="flex gap-2">
                            {{-- Tombol First Page --}}
                            @if ($students->currentPage() > 2)
                                <a href="{{ $students->url(1) }}"
                                    class="px-3 py-1 text-sm font-medium text-black bg-gray-200 rounded-lg hover:bg-gray-300">
                                    1
                                </a>
                                @if ($students->currentPage() > 3)
                                    <span class="px-3 py-1 text-sm text-gray-500">...</span>
                                @endif
                            @endif

                            {{-- Loop Nomor Halaman (Menampilkan halaman di sekitar halaman aktif) --}}
                            @for ($page = max(1, $students->currentPage() - 1); $page <= min($students->lastPage(), $students->currentPage() + 1); $page++)
                                @if ($page == $students->currentPage())
                                    <a href="{{ $students->url($page) }}"
                                        class="px-3 py-1 text-sm font-medium text-white bg-[#6B7126] rounded-lg shadow-md">
                                        {{ $page }}
                                    </a>
                                @else
                                    <a href="{{ $students->url($page) }}"
                                        class="px-3 py-1 text-sm font-medium text-black bg-gray-200 rounded-lg hover:bg-gray-300">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endfor

                            {{-- Tombol Last Page --}}
                            @if ($students->currentPage() < $students->lastPage() - 1)
                                @if ($students->currentPage() < $students->lastPage() - 2)
                                    <span class="px-3 py-1 text-sm text-gray-500">...</span>
                                @endif
                                <a href="{{ $students->url($students->lastPage()) }}"
                                    class="px-3 py-1 text-sm font-medium text-black bg-gray-200 rounded-lg hover:bg-gray-300">
                                    {{ $students->lastPage() }}
                                </a>
                            @endif
                        </div>


                        <!-- Next Button -->
                        @if ($students->hasMorePages())
                            <a href="{{ $students->nextPageUrl() }}"
                                class="px-3 py-1 text-sm text-gray-500 hover:text-gray-600">
                                Next
                            </a>
                        @else
                            <button class="px-3 py-1 text-sm text-gray-400 disabled:opacity-50" disabled>
                                Next
                            </button>
                        @endif
                    </div>
                </div>


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
            function fetchStudents() {
                var studyProgram = $('#studyProgramFilter').val();
                var classYear = $('#classYearFilter').val();
                var campus = $('#campusFilter').val();
                var search = $('#searchStudent').val();

                $.ajax({
                    url: "{{ route('students.filter') }}", // Pastikan route ini dibuat
                    type: "GET",
                    data: {
                        study_program: studyProgram,
                        class_year: classYear,
                        campus: campus,
                        search: search
                    },
                    success: function(data) {
                        console.log(data);
                        $('#studentTableBody').html(data);
                    }
                });
            }

            // Event listener untuk setiap filter
            $('#studyProgramFilter, #classYearFilter, #campusFilter, #searchStudent').on('change keyup',
                function() {
                    fetchStudents();
                });
        });
    </script>

@endsection
