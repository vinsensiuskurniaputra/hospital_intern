@extends('layouts.auth')

@section('title', 'Detail Kelas Magang')

@section('content')
    <div class="p-6 space-y-6">
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <h1 class="text-2xl text-gray-800 pb-6">Detail Kelas Magang: {{ $internshipClass->name }}</h1>
            <p class="text-gray-600">Deskripsi: {{ $internshipClass->description }}</p>
            <p class="text-gray-600">Tahun Kelas: {{ $internshipClass->classYear->class_year }}</p>
            <p class="text-gray-600">Kampus: {{ $internshipClass->campus->name }}</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="border-b border-gray-100 p-6">
                <h2 class="text-xl text-gray-800">Daftar Mahasiswa</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="table-auto w-full">
                    <thead class="bg-gray-50 border-y border-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">NIM</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($internshipClass->students as $i => $student)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $i + 1 }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $student->user->name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $student->nim }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $student->user->email }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
