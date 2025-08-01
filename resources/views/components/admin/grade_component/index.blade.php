@extends('layouts.auth')
@section('the_title', 'Grade Components')
@section('content')
    <div x-data="{ addModal: false }">
        <!-- Notification Messages -->
        @include('components.general.notification')

        <div class="p-6 space-y-6">
            <!-- Summary Cards -->
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <h1 class="text-2xl text-gray-800 pb-6">Grade Components</h1>
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
                                    <input type="text" id="searchInput" placeholder="Search grade component..."
                                        class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-200 focus:border-[#637F26] focus:ring-2 focus:ring-[#637F26]">
                                    <i class="bi bi-search absolute left-3 top-2.5 text-gray-400"></i>
                                </div>
                            </div>
                            <button @click="addModal = true"
                                class="px-4 py-2 text-sm font-medium text-white bg-[#637F26] rounded-lg hover:bg-[#85A832]">
                                <i class="bi bi-plus-lg mr-2"></i>Add Grade Component
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="table-auto w-full">
                        <thead class="bg-gray-50 border-y border-gray-100">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stase</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="TableBody" class="divide-y divide-gray-100">
                            @include('components.admin.grade_component.table', [
                                'gradeComponents' => $gradeComponents,
                            ])
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $gradeComponents->links() }}
                </div>
            </div>
        </div>
        @include('components.admin.grade_component.add', ['show' => 'addModal'])
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    {{-- <script>
        $(document).ready(function() {
            function fetchSearch() {
                var search = $('#searchInput').val();

                $.ajax({
                    url: "{{ route('gradeComponents.filter') }}", // Ensure this route is created
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

            // Event listener for search input
            $('#searchInput').on('change keyup', function() {
                fetchSearch();
            });
        });
    </script> --}}
@endsection
