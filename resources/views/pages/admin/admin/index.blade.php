@extends('layouts.auth')

@section('title', 'Users Management')

@section('content')
    <div x-data="{ addModal: false, selectedStudent: {} }">
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
                            {{ session('error') ??
                                "
                                                                                                                                                                                                                                                            There is something wrong in your input !" }}
                        </p>
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
                <h1 class="text-2xl text-gray-800 pb-6">Admin/h1>
            </div>

            <!-- Main Content Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <!-- Card Header -->
                <div class="border-b border-gray-100 p-6">
                    <div class="flex flex-col lg:items-center lg:justify-between gap-4">
                        <div class="flex flex-col lg:flex-row w-full justify-between gap-3">
                            <!-- Search -->
                            <div class="w-full lg:w-[360px]">
                                <div class="relative">
                                    <input type="text" id="searchAdmin" placeholder="Search admin..."
                                        class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-200 focus:border-[#637F26] focus:ring-2 focus:ring-[#637F26]">
                                    <i class="bi bi-search absolute left-3 top-2.5 text-gray-400"></i>
                                </div>
                            </div>
                            <div class="flex gap-3">
                                <button
                                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-gray-50">
                                    <i class="bi bi-upload mr-2"></i>Impor CSV
                                </button>
                                <button @click="addModal = true"
                                    class="px-4 py-2 text-sm font-medium text-white bg-[#637F26] rounded-lg hover:bg-[#85A832]">
                                    <i class="bi bi-plus-lg mr-2"></i>Tambah Admin
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
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Pengguna</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody id="adminTableBody" class="divide-y divide-gray-100">
                            @include('components.admin.admin.table', [
                                'users' => $users,
                            ])
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="flex items-center justify-between px-6 py-4 border-t border-gray-100">
                    <div class="text-sm text-gray-500">
                        Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }}
                        entries
                    </div>
                    <div class="flex items-center gap-2">
                        <!-- Previous Button -->
                        @if ($users->onFirstPage())
                            <button class="px-3 py-1 text-sm text-gray-400 disabled:opacity-50" disabled>
                                Previous
                            </button>
                        @else
                            <a href="{{ $users->previousPageUrl() }}"
                                class="px-3 py-1 text-sm text-gray-500 hover:text-gray-600">
                                Previous
                            </a>
                        @endif

                        <!-- Pagination Numbers -->
                        <div class="flex gap-2">
                            {{-- Tombol First Page --}}
                            @if ($users->currentPage() > 2)
                                <a href="{{ $users->url(1) }}"
                                    class="px-3 py-1 text-sm font-medium text-black bg-gray-200 rounded-lg hover:bg-gray-300">
                                    1
                                </a>
                                @if ($users->currentPage() > 3)
                                    <span class="px-3 py-1 text-sm text-gray-500">...</span>
                                @endif
                            @endif

                            {{-- Loop Nomor Halaman (Menampilkan halaman di sekitar halaman aktif) --}}
                            @for ($page = max(1, $users->currentPage() - 1); $page <= min($users->lastPage(), $users->currentPage() + 1); $page++)
                                @if ($page == $users->currentPage())
                                    <a href="{{ $users->url($page) }}"
                                        class="px-3 py-1 text-sm font-medium text-white bg-[#6B7126] rounded-lg shadow-md">
                                        {{ $page }}
                                    </a>
                                @else
                                    <a href="{{ $users->url($page) }}"
                                        class="px-3 py-1 text-sm font-medium text-black bg-gray-200 rounded-lg hover:bg-gray-300">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endfor

                            {{-- Tombol Last Page --}}
                            @if ($users->currentPage() < $users->lastPage() - 1)
                                @if ($users->currentPage() < $users->lastPage() - 2)
                                    <span class="px-3 py-1 text-sm text-gray-500">...</span>
                                @endif
                                <a href="{{ $users->url($users->lastPage()) }}"
                                    class="px-3 py-1 text-sm font-medium text-black bg-gray-200 rounded-lg hover:bg-gray-300">
                                    {{ $users->lastPage() }}
                                </a>
                            @endif
                        </div>


                        <!-- Next Button -->
                        @if ($users->hasMorePages())
                            <a href="{{ $users->nextPageUrl() }}"
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
        @include('components.admin.admin.add', [
            'show' => 'addModal',
        ])
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            function fetchAdmins() {
                var search = $('#searchAdmin').val();

                $.ajax({
                    url: "{{ route('admins.filter') }}", // Pastikan route ini dibuat
                    type: "GET",
                    data: {
                        search: search
                    },
                    success: function(data) {
                        console.log(data);
                        $('#adminTableBody').html(data);
                    }
                });
            }

            // Event listener untuk setiap filter
            $('#searchAdmin').on('change keyup',
                function() {
                    fetchAdmins();
                });
        });
    </script>

@endsection
