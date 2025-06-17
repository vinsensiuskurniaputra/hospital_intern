@extends('layouts.auth')

@section('title', 'Manajemen Kelas Magang')

@section('content')
    <div x-data="{ addModal: false }">
        <!-- Pesan Notifikasi -->
        @include('components.general.notification')

        <div class="p-6 space-y-6">
            <!-- Kartu Ringkasan -->
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <h1 class="text-2xl text-gray-800 pb-6">Kelas Magang</h1>
            </div>

            <!-- Kartu Konten Utama -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <!-- Header Kartu -->
                <div class="border-b border-gray-100 p-6">
                    <div class="flex flex-col lg:items-center lg:justify-between gap-4">
                        <!-- Tombol Aksi -->
                        <div class="flex flex-col lg:flex-row w-full justify-between gap-3">
                            <!-- Pencarian -->
                            <div class="w-full lg:w-[360px]">
                                <div class="relative">
                                    <input type="text" id="searchInput" placeholder="Cari Kelas Magang..."
                                        class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-200 focus:border-[#637F26] focus:ring-2 focus:ring-[#637F26]">
                                    <i class="bi bi-search absolute left-3 top-2.5 text-gray-400"></i>
                                </div>
                            </div>
                            <div>
                                <a href="{{ route('admin.internshipClasses.insertStudent') }}"
                                    class="px-4 py-2 text-sm font-medium text-white bg-[#637F26] rounded-lg hover:bg-[#85A832]">
                                    <i class="bi bi-plus-lg mr-2"></i>Inputkan Mahasiswa
                                </a>
                                <button @click="addModal = true"
                                    class="px-4 py-2 ml-2 text-sm font-medium text-white bg-[#637F26] rounded-lg hover:bg-[#85A832]">
                                    <i class="bi bi-plus-lg mr-2"></i>Tambah Kelas Magang
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabel -->
                <div class="overflow-x-auto">
                    <table class="table-auto w-full ">
                        <thead class="bg-gray-50 border-y border-gray-100">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Deskripsi</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                    Tahun Kelas
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                    Kampus
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                    Jumlah Mahasiswa
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="TableBody" class="divide-y divide-gray-100">
                            @include('components.admin.internship_class.table', [
                                'internshipClasses' => $internshipClasses,
                            ])
                        </tbody>
                    </table>
                </div>

                <!-- Paginasi -->
                @include('components.general.pagination', [
                    'datas' => $internshipClasses,
                ])

            </div>
        </div>
        @include('components.admin.internship_class.add', [
            'show' => 'addModal',
        ])
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            function fetchSearch() {
                var search = $('#searchInput').val();

                $.ajax({
                    url: "{{ route('internshipClasses.filter') }}", // Pastikan route ini dibuat
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
                    fetchSearch();
                });
        });
    </script>

@endsection
