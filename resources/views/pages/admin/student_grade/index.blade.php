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
                            <span class="text-2xl font-bold text-[#637F26]">{{ number_format($currentAvg, 2) }}</span>
                            <span class="{{ $isDown ? 'text-red-500' : 'text-green-500' }} text-xs flex items-center">
                                <i class="bi {{ $isDown ? 'bi-arrow-down-right' : 'bi-arrow-up-right' }}"></i>
                                <span class="ml-1">{{ number_format(abs($changePercent), 1) }}%</span>
                            </span>
                        </div>
                    </div>
                    <div class="h-12 w-px bg-gray-200"></div>
                    <div>
                        <span class="text-xs font-medium text-[#637F26] bg-white px-2 py-1 rounded">
                            {{ \Carbon\Carbon::now()->format('F Y') }}
                        </span>
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
                    <div class="flex items-center gap-4">
                        <select id="studyProgramFilter"
                            class="px-4 min-w-[100px] py-2 w-full rounded-lg border border-gray-200">
                            <option value="">All Program Study</option>
                            @foreach ($studyPrograms as $studyProgram)
                                <option value="{{ $studyProgram->id }}">{{ $studyProgram->name }}</option>
                            @endforeach
                        </select>
                        <select id="departementFilter"
                            class="px-4 min-w-[100px] py-2 w-full rounded-lg border border-gray-200">
                            <option value="">All Departement</option>
                            @foreach ($departements as $departement)
                                <option value="{{ $departement->id }}">{{ $departement->name }}</option>
                            @endforeach
                        </select>

                        <div class="relative">
                            <input type="text" id="searchInput" placeholder="Search students..."
                                class="pl-10 pr-4 py-2 rounded-lg border border-gray-200 focus:border-[#637F26] focus:ring-2 focus:ring-[#637F26] w-full lg:w-[240px]">
                            <i class="bi bi-search absolute left-3 top-2.5 text-gray-400"></i>
                        </div>
                    </div>
                    <!-- Actions -->
                    {{-- <div class="flex gap-3">
                        <button
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-gray-50">
                            <i class="bi bi-upload mr-2"></i>Import CSV
                        </button>
                        <button class="px-4 py-2 text-sm font-medium text-white bg-[#637F26] rounded-lg hover:bg-[#85A832]">
                            <i class="bi bi-plus-lg mr-2"></i>Add Grade
                        </button>
                    </div> --}}
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-y border-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Student</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stase
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Department</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Grade
                            </th>
                        </tr>
                    </thead>
                    <tbody id="TableBody">
                        @include('components.admin.student_grade.table', [
                            'studentGrades' => $studentGrades,
                        ])

                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @include('components.general.pagination', [
                'datas' => $studentGrades,
            ])
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            function fetchSearch() {
                var studyProgram = $('#studyProgramFilter').val();
                var departement = $('#departementFilter').val();
                var search = $('#searchInput').val();

                $.ajax({
                    url: "{{ route('studentScores.filter') }}",
                    type: "GET",
                    data: {
                        study_program: studyProgram,
                        departement: departement,
                        search: search
                    },
                    success: function(data) {
                        console.log(data);
                        $('#TableBody').html(data);
                    }
                });
            }

            // Event listener untuk setiap filter
            $('#studyProgramFilter, #departementFilter, #searchInput').on('change keyup',
                function() {
                    fetchSearch();
                });
        });
    </script>
@endsection
