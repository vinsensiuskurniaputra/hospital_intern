@extends('layouts.auth')

@section('title', 'Responsible Management')

@section('content')
    <div x-data="{ addModal: false, showImportModal: false }">
        <!-- Notification Messages -->
        @include('components.general.notification')

        <div class="p-6 space-y-6">
            <!-- Summary Cards -->
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <h1 class="text-2xl text-gray-800 pb-6">PIC</h1>
                {{-- <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
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
                </div> --}}
            </div>

            <!-- Main Content Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <!-- Card Header -->
                <div class="border-b border-gray-100 p-6">
                    <div class="flex flex-col lg:items-center lg:justify-between gap-4">
                        <!-- Action Buttons -->
                        <div class="flex flex-col lg:flex-row w-full justify-between gap-3">
                            <!-- Search -->
                            <div class="w-full lg:w-[360px]">
                                <div class="relative">
                                    <input type="text" id="searchInput" placeholder="Search responsible..."
                                        class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-200 focus:border-[#637F26] focus:ring-2 focus:ring-[#637F26]">
                                    <i class="bi bi-search absolute left-3 top-2.5 text-gray-400"></i>
                                </div>
                            </div>
                            <div class="flex gap-3">
                                <button @click="showImportModal = true"
                                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-gray-50">
                                    <i class="bi bi-upload mr-2"></i>Import CSV
                                </button>
                                <button @click="addModal = true"
                                    class="px-4 py-2 text-sm font-medium text-white bg-[#637F26] rounded-lg hover:bg-[#85A832]">
                                    <i class="bi bi-plus-lg mr-2"></i>Add Responsible
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
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Username</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Telp</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="TableBody" class="divide-y divide-gray-100">
                            @include('components.admin.responsible.table', [
                                'responsibles' => $responsibles,
                            ])
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @include('components.general.pagination', [
                    'datas' => $responsibles,
                ])

            </div>
        </div>
        @include('components.general.import_modal', [
            'show' => 'showImportModal',
            'title' => 'Responsible',
            'description' => 'Upload your CSV file to import pic data',
            'action' => route('responsibles.import'),
            'template_url' => route('responsibles.downloadTemplate'),
        ])
        @include('components.admin.responsible.add', [
            'show' => 'addModal',
        ])
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            function fetchResponsibles() {
                var search = $('#searchInput').val();

                $.ajax({
                    url: "{{ route('responsibles.filter') }}", // Pastikan route ini dibuat
                    type: "GET",
                    data: {
                        search: search
                    },
                    success: function(data) {
                        console.log(data);
                        $('#TableBody').html(data);
                    }
                });
            }

            // Event listener untuk setiap filter
            $('#searchInput').on('change keyup',
                function() {
                    fetchResponsibles();
                });
        });
    </script>

@endsection
