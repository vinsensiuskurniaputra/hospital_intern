@extends('layouts.auth')

@section('title', 'View Schedule')

@section('content')
<div class="p-6 min-h-screen bg-gray-50">
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <div class="mb-6 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.schedules.index') }}" class="text-gray-400 hover:text-gray-600">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <h2 class="text-xl font-medium text-gray-800">Detail Jadwal</h2>
            </div>
        </div>

        <div class="max-w-4xl mx-auto">
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <!-- Kelas Magang -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kelas Magang</label>
                        <div class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-md">
                            {{ $schedule->internshipClass->name }}
                        </div>
                    </div>

                    <!-- Stase -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Stase</label>
                        <div class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-md">
                            {{ $schedule->stase->name }}
                        </div>
                    </div>

                    <!-- Departemen -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Departemen</label>
                        <div class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-md">
                            {{ $schedule->departement->name }}
                        </div>
                    </div>
                </div>

                <div>
                    <!-- Tahun Angkatan -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tahun Angkatan</label>
                        <div class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-md">
                            {{ $schedule->internshipClass->classYear->class_year }}
                        </div>
                    </div>

                    <!-- Pembimbing -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pembimbing Magang</label>
                        <div class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-md">
                            {{ $schedule->stase->responsibleUser->user->name ?? 'N/A' }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Periode Rotasi -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Periode Rotasi</label>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Tanggal Mulai</label>
                        <div class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-md">
                            {{ \Carbon\Carbon::parse($schedule->start_date)->format('d/m/Y') }}
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Tanggal Selesai</label>
                        <div class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-md">
                            {{ \Carbon\Carbon::parse($schedule->end_date)->format('d/m/Y') }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Jam -->
            <div class="mb-8">
                <label class="block text-sm font-medium text-gray-700 mb-2">Jam Praktik</label>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Jam Mulai</label>
                        <div class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-md">
                            {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Jam Selesai</label>
                        <div class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-md">
                            {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Back Button -->
            <div class="flex justify-end">
                <a href="{{ route('admin.schedules.index') }}" 
                    class="px-6 py-2.5 text-sm font-medium text-gray-600 bg-gray-100 rounded-md hover:bg-gray-200 transition-colors duration-200">
                    Kembali
                </a>
            </div>
        </div>
    </div>
</div>
@endsection