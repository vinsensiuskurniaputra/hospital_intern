@extends('layouts.auth')

@section('title', 'Edit Schedule')

@section('content')
<div class="p-6">
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="mb-6 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.schedules.index') }}" class="text-gray-400 hover:text-gray-600">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <h2 class="text-xl font-medium text-gray-800">Edit Jadwal</h2>
            </div>
        </div>

        <form action="{{ route('admin.schedules.update', $schedule->id) }}" method="POST" class="max-w-xl">
            @csrf
            @method('PUT')

            <!-- Kelas Magang -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Kelas Magang</label>
                <select name="internship_class_id" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-1 focus:ring-green-500 focus:border-green-500">
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
                <select name="stase_id" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-1 focus:ring-green-500 focus:border-green-500">
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
                <select name="departement_id" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-1 focus:ring-green-500 focus:border-green-500">
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
                <select name="class_year" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-1 focus:ring-green-500 focus:border-green-500">
                    <option value="2025/2026" {{ $schedule->internshipClass->classYear->class_year == '2025/2026' ? 'selected' : '' }}>2025/2026</option>
                    <option value="2024/2025" {{ $schedule->internshipClass->classYear->class_year == '2024/2025' ? 'selected' : '' }}>2024/2025</option>
                </select>
            </div>

            <!-- Pembimbing -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Pembimbing Magang</label>
                <input type="text" value="{{ $schedule->stase->responsibleUser->user->name ?? '' }}" 
                    class="w-full border border-gray-300 rounded-md px-3 py-2 bg-gray-50" readonly>
            </div>

            <!-- Periode Rotasi -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Periode Rotasi</label>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <input type="date" name="start_date" value="{{ $schedule->start_date }}" 
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-1 focus:ring-green-500 focus:border-green-500">
                    </div>
                    <div>
                        <input type="date" name="end_date" value="{{ $schedule->end_date }}" 
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-1 focus:ring-green-500 focus:border-green-500">
                    </div>
                </div>
            </div>

            <!-- Jam -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Jam</label>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <input type="time" name="start_time" value="{{ $schedule->start_time }}" 
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-1 focus:ring-green-500 focus:border-green-500">
                    </div>
                    <div>
                        <input type="time" name="end_time" value="{{ $schedule->end_time }}" 
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-1 focus:ring-green-500 focus:border-green-500">
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('admin.schedules.index') }}" 
                    class="px-4 py-2 text-sm font-medium text-red-600 border border-red-600 rounded-md hover:bg-red-50">
                    Cancel
                </a>
                <button type="submit" 
                    class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-md hover:bg-green-700">
                    Save
                </button>
            </div>
        </form>
    </div>
</div>
@endsection