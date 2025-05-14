@extends('layouts.auth')

@section('title', 'User Authorization Management')

@section('content')
    <div>
        <!-- Notification Messages -->
        @include('components.general.notification')

        <div class="p-6 space-y-6">
            <!-- Summary Cards -->
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <h1 class="text-2xl text-gray-800 pb-6">Otorisasi Penggun</h1>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Jumlah Pengguna</p>
                            <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $userCount }}</h3>
                        </div>
                        <div class="p-3 bg-[#F5F7F0] rounded-lg">
                            <i class="bi bi-people text-xl text-[#637F26]"></i>
                        </div>
                    </div>
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Peran</p>
                            <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $roleCount }}</h3>
                        </div>
                        <div class="p-3 bg-[#F5F7F0] rounded-lg">
                            <i class="bi bi-person-badge text-xl text-[#637F26]"></i>
                        </div>
                    </div>
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Menu</p>
                            <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $menuCount }}</h3>
                        </div>
                        <div class="p-3 bg-[#F5F7F0] rounded-lg">
                            <i class="bi bi-list-task text-xl text-[#637F26]"></i>
                        </div>
                    </div>
                </div>
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
                                    <input type="text" id="searchInput" placeholder="Search User..."
                                        class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-200 focus:border-[#637F26] focus:ring-2 focus:ring-[#637F26]">
                                    <i class="bi bi-search absolute left-3 top-2.5 text-gray-400"></i>
                                </div>
                            </div>
                            <select id="roleFilter"
                                class="px-4 min-w-[100px] py-2 w-full rounded-lg border border-gray-200">
                                <option value="">Semua Role</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="table-auto  ">
                        <thead class="bg-gray-50 border-y border-gray-100">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Username</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Roles</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody id="TableBody" class="divide-y divide-gray-100">
                            @include('components.admin.user_authorization.table', [
                                'users' => $users,
                            ])
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @include('components.general.pagination', [
                    'datas' => $users,
                ])

            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            function fetchSearch() {
                var search = $('#searchInput').val();
                var role = $('#roleFilter').val();

                $.ajax({
                    url: "{{ route('users.filter') }}", // Pastikan route ini dibuat
                    type: "GET",
                    data: {
                        search: search,
                        role: role,
                    },
                    success: function(data) {
                        console.log(data);
                        $('#TableBody').html(data);
                    }
                });
            }

            // Event listener untuk setiap filter
            $('#searchInput, #roleFilter').on('change keyup',
                function() {
                    fetchSearch();
                });
        });
    </script>

@endsection
