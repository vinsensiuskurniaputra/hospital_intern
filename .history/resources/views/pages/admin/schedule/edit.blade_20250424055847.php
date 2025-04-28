@extends('layouts.auth')

@section('title', 'Edit Schedule')

@section('content')
<div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full" id="editModal">
    <div class="relative top-20 mx-auto p-5 w-96 shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold">Edit Jadwal</h3>
            <button class="text-gray-400 hover:text-gray-600" onclick="window.history.back()">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form action="{{ route('admin.schedules.update', $schedule->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Kelas Magang -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Kelas Magang</label>
                <select name="internship_class_id" class="w-full border border-gray-300 rounded-md px-3 py-2">
                    @foreach($internshipClasses as $class)
                        <option value="{{ $class->id }}" {{ $schedule->internship_class_id == $class->id ? 'selected' : '' }}>
                            {{ $class->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Stase -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Stase</label>
                <select name="stase_id" class="w-full border border-gray-300 rounded-md px-3 py-2">
                    @foreach($stases as $stase)
                        <option value="{{ $stase->id }}" {{ $schedule->stase_id == $stase->id ? 'selected' : '' }}>
                            {{ $stase->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Departemen -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Departemen</label>
                <select name="departement_id" class="w-full border border-gray-300 rounded-md px-3 py-2">
                    @foreach($departments as $department)
                        <option value="{{ $department->id }}" {{ $schedule->departement_id == $department->id ? 'selected' : '' }}>
                            {{ $department->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Tahun Angkatan -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Tahun Angkatan</label>
                <select name="class_year" class="w-full border border-gray-300 rounded-md px-3 py-2">
                    <option value="2025/2026" {{ $schedule->internshipClass->classYear->class_year == '2025/2026' ? 'selected' : '' }}>2025/2026</option>
                    <option value="2024/2025" {{ $schedule->internshipClass->classYear->class_year == '2024/2025' ? 'selected' : '' }}>2024/2025</option>
                </select>
            </div>

            <!-- Pembimbing -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Pembimbing Magang</label>
                <input type="text" value="{{ $schedule->stase->responsibleUser->user->name ?? '' }}" class="w-full border border-gray-300 rounded-md px-3 py-2" readonly>
            </div>

            <!-- Periode Rotasi -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Periode Rotasi</label>
                <div class="grid grid-cols-2 gap-2">
                    <input type="date" name="start_date" value="{{ $schedule->start_date }}" class="border border-gray-300 rounded-md px-3 py-2">
                    <input type="date" name="end_date" value="{{ $schedule->end_date }}" class="border border-gray-300 rounded-md px-3 py-2">
                </div>
            </div>

            <!-- Jam -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Jam Mulai</label>
                <div class="grid grid-cols-2 gap-2">
                    <input type="time" name="start_time" value="{{ $schedule->start_time }}" class="border border-gray-300 rounded-md px-3 py-2">
                    <input type="time" name="end_time" value="{{ $schedule->end_time }}" class="border border-gray-300 rounded-md px-3 py-2">
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex justify-end gap-2">
                <button type="button" onclick="window.history.back()" class="px-4 py-2 text-sm border border-red-500 text-red-500 rounded-md hover:bg-red-50">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 text-sm bg-green-500 text-white rounded-md hover:bg-green-600">
                    Save
                </button>
            </div>
        </form>
    </div>
</div>
@endsection