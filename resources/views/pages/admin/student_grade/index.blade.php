@extends('layouts.auth')

@section('title', 'Nilai Mahasiswa')

@section('content')
    <div class="p-6 space-y-6">
        <!-- Header Card -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-800">Nilai Mahasiswa</h1>
                    <p class="mt-1 text-sm text-gray-500">Kelola dan lihat nilai mahasiswa</p>
                </div>
            </div>
        </div>

        <!-- Main Content Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <!-- Filter Section -->
            <div class="p-6 border-b border-gray-100">
                <form method="GET" action="{{ route('admin.studentScores.index') }}">
                    <div class="space-y-6">
                        <!-- Academic Filters -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Filter Akademik</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Program Studi</label>
                                    <select name="study_program"
                                        class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#637F26] bg-white">
                                        <option value="">Semua Program Studi</option>
                                        @foreach ($studyPrograms as $studyProgram)
                                            <option value="{{ $studyProgram->id }}"
                                                {{ request('study_program') == $studyProgram->id ? 'selected' : '' }}>
                                                {{ $studyProgram->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Departemen</label>
                                    <select name="departement"
                                        class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#637F26] bg-white">
                                        @foreach ($departements as $departement)
                                            <option value="{{ $departement->id }}"
                                                {{ request('departement') == $departement->id ? 'selected' : '' }}>
                                                {{ $departement->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Tahun Kelas</label>
                                    <select name="class_year"
                                        class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#637F26] bg-white">
                                        <option value="">Semua Tahun</option>
                                        @foreach ($classYears as $classYear)
                                            <option value="{{ $classYear->id }}"
                                                {{ request('class_year') == $classYear->id ? 'selected' : '' }}>
                                                {{ $classYear->class_year }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Kelas Internship</label>
                                    <select name="internship_class"
                                        class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#637F26] bg-white">
                                        <option value="">Semua Kelas</option>
                                        @foreach ($internshipClasses as $internshipClass)
                                            <option value="{{ $internshipClass->id }}"
                                                {{ request('internship_class') == $internshipClass->id ? 'selected' : '' }}>
                                                {{ $internshipClass->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Display & Search Filters -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Tampilan & Pencarian</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Per Halaman</label>
                                    <select name="per_page"
                                        class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#637F26] bg-white">
                                        <option value="5" {{ request('per_page') == 5 ? 'selected' : '' }}>5 Data
                                        </option>
                                        <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10 Data
                                        </option>
                                        <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25 Data
                                        </option>
                                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 Data
                                        </option>
                                    </select>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Cari Mahasiswa</label>
                                    <div class="relative">
                                        <input type="text" name="search" value="{{ request('search') }}"
                                            placeholder="Cari berdasarkan nama mahasiswa..."
                                            class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#637F26] bg-white">
                                        <i class="bi bi-search absolute left-3 top-2.5 text-gray-400"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex justify-end gap-3">
                            <button type="reset"
                                class="px-4 py-2 text-sm font-medium text-gray-600 bg-gray-50 rounded-lg hover:bg-gray-100">
                                Reset Filter
                            </button>
                            <button type="submit"
                                class="px-4 py-2 text-sm font-medium text-white bg-[#637F26] rounded-lg hover:bg-[#85A832']">
                                Terapkan Filter
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
