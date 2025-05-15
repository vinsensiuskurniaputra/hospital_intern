@extends('layouts.auth')

@section('title', 'Internship Class Management')

@section('content')
<div x-data="{ addModal: false }">
    @include('components.general.notification')

    <div class="p-6 space-y-6">
        <!-- Heading -->
        <div class="bg-white rounded-xl p-6 shadow-sm border">
            <h1 class="text-2xl text-gray-800">Internship Class</h1>
        </div>

        <!-- Main Card -->
        <div class="bg-white rounded-xl shadow-sm border">
            <!-- Header -->
            <div class="border-b p-6 flex flex-col lg:flex-row gap-4 justify-between items-center">
                <!-- Search -->
                <div class="relative w-full lg:w-[360px]">
                    <input type="text" id="searchInput" placeholder="Search Internship Class..."
                        class="w-full pl-10 pr-4 py-2 rounded-lg border focus:border-[#637F26] focus:ring-2 focus:ring-[#637F26]">
                    <i class="bi bi-search absolute left-3 top-2.5 text-gray-400"></i>
                </div>
                <!-- Add Button -->
                <button @click="addModal = true"
                    class="px-4 py-2 text-sm text-white bg-[#637F26] rounded-lg hover:bg-[#85A832]">
                    <i class="bi bi-plus-lg mr-2"></i>Add Internship Class
                </button>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="table-auto w-full">
                    <thead class="bg-gray-50 border-y">
                        <tr>
                            @foreach (['No', 'Name', 'Description', 'Class Year', 'Campus', 'Actions'] as $head)
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase {{ $head === 'Actions' ? 'text-right' : '' }}">
                                    {{ $head }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody id="TableBody" class="divide-y">
                        @include('components.admin.internship_class.table', ['internshipClasses' => $internshipClasses])
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @include('components.general.pagination', ['datas' => $internshipClasses])
        </div>
    </div>

    <!-- Modal -->
    @include('components.admin.internship_class.add', ['show' => 'addModal'])
</div>

<!-- Search Script -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(function () {
        $('#searchInput').on('input', function () {
            $.get("{{ route('internshipClasses.filter') }}", { search: $(this).val() }, function (data) {
                $('#TableBody').html(data);
            });
        });
    });
</script>
@endsection
