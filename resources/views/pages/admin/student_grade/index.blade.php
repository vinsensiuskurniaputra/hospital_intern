@extends('layouts.auth')

@section('title', 'Nilai Mahasiswa')

@section('content')
    <div class="p-6 space-y-6">
        <!-- Kartu Konten Utama -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <!-- Filter -->
            <div class="p-6 border-b border-gray-100">
                <form method="GET" action="{{ route('admin.studentScores.index') }}">
                    <div class="flex flex-col lg:flex-row lg:items-center gap-4">
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Program Studi</label>
                            <select name="study_program"
                                class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#637F26]">
                                <option value="">Semua Program Studi</option>
                                @foreach ($studyPrograms as $studyProgram)
                                    <option value="{{ $studyProgram->id }}"
                                        {{ request('study_program') == $studyProgram->id ? 'selected' : '' }}>
                                        {{ $studyProgram->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Departemen</label>
                            <select name="departement"
                                class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#637F26]">
                                @foreach ($departements as $departement)
                                    <option value="{{ $departement->id }}"
                                        {{ request('departement') == $departement->id ? 'selected' : '' }}>
                                        {{ $departement->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Per Halaman</label>
                            <select name="per_page"
                                class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#637F26]">
                                <option value="5" {{ request('per_page') == 5 ? 'selected' : '' }}>5</option>
                                <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                                <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                            </select>
                        </div>
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Cari Mahasiswa</label>
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Cari mahasiswa..."
                                class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#637F26]">
                        </div>
                        <div class="flex-1">
                            <button type="submit"
                                class="px-4 py-2 text-sm font-medium text-white bg-[#637F26] rounded-lg hover:bg-[#85A832']">
                                Filter
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Tabel -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <tbody>
                        @include('components.admin.student_grade.table', [
                            'students' => $students,
                            'stases' => $stases,
                        ])
                    </tbody>
                </table>
            </div>

            <!-- Paginasi -->
            @include('components.general.pagination', [
                'datas' => $students,
            ])
        </div>
    </div>
@endsection
