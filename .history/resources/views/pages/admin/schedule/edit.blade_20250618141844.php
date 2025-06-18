@extends('layouts.auth')

@section('title', 'Edit Schedule')

@section('content')
<div class="p-6 min-h-screen bg-gray-50">
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <div class="mb-6 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('presences.schedules.index') }}" class="text-gray-400 hover:text-gray-600">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <h2 class="text-xl font-medium text-gray-800">Edit Jadwal</h2>
            </div>
        </div>

        <!-- Error Messages -->
        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded">
                <div class="flex items-center mb-2">
                    <svg class="h-5 w-5 text-red-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="text-sm font-medium text-red-800">Ada beberapa kesalahan dalam pengisian form:</h3>
                </div>
                <ul class="list-disc list-inside text-sm text-red-700">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('presences.schedules.update', $schedule->id) }}" method="POST">
            @method('PUT')
            @csrf

            <div class="grid grid-cols-2 gap-6">
                <div>
                    <!-- Kelas Magang -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kelas Magang</label>
                        <select name="internship_class_id" class="w-full border border-gray-300 rounded-md px-3 py-2.5 focus:ring-1 focus:ring-green-500 focus:border-green-500">
                            @foreach($internshipClasses as $class)
                                <option value="{{ $class->id }}" {{ $schedule->internship_class_id == $class->id ? 'selected' : '' }}>
                                    {{ $class->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Stase -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Stase</label>
                        <select name="stase_id" class="w-full border border-gray-300 rounded-md px-3 py-2.5 focus:ring-1 focus:ring-green-500 focus:border-green-500">
                            @foreach($stases as $stase)
                                <option value="{{ $stase->id }}" {{ $schedule->stase_id == $stase->id ? 'selected' : '' }}>
                                    {{ $stase->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <!-- Additional Info -->
                    <div class="mb-6 p-4 bg-blue-50 rounded-md">
                        <h4 class="text-sm font-medium text-blue-800 mb-2">Informasi Penting</h4>
                        <ul class="text-sm text-blue-700 list-disc list-inside space-y-1">
                            <li>Pastikan periode rotasi tidak bertabrakan dengan jadwal lain</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Periode Rotasi -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Periode Rotasi</label>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Tanggal Mulai</label>
                        <input type="date" 
                               name="start_date" 
                               value="{{ \Carbon\Carbon::parse($schedule->start_date)->format('Y-m-d') }}" 
                               class="w-full border border-gray-300 rounded-md px-3 py-2.5 focus:ring-1 focus:ring-green-500 focus:border-green-500">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Tanggal Selesai</label>
                        <input type="date" 
                               name="end_date" 
                               value="{{ \Carbon\Carbon::parse($schedule->end_date)->format('Y-m-d') }}" 
                               class="w-full border border-gray-300 rounded-md px-3 py-2.5 focus:ring-1 focus:ring-green-500 focus:border-green-500">
                    </div>
                </div>
            </div>

            <!-- Jam
            <div class="mb-8">
                <label class="block text-sm font-medium text-gray-700 mb-2">Jam Praktik</label>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Jam Mulai</label>
                        <input type="time" name="start_time" value="{{ $schedule->start_time }}" 
                            class="w-full border border-gray-300 rounded-md px-3 py-2.5 focus:ring-1 focus:ring-green-500 focus:border-green-500">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Jam Selesai</label>
                        <input type="time" name="end_time" value="{{ $schedule->end_time }}" 
                            class="w-full border border-gray-300 rounded-md px-3 py-2.5 focus:ring-1 focus:ring-green-500 focus:border-green-500">
                    </div>
                </div>
            </div> -->

            <!-- Action Buttons -->
            <div class="flex items-center justify-end gap-3 border-t pt-6">
                <a href="{{ route('presences.schedules.index') }}" class="px-6 py-2.5 text-sm font-medium text-red-600 border border-red-600 rounded-md hover:bg-red-50">
                    Cancel
                </a>
                <button type="submit" 
                    class="px-6 py-2.5 text-sm font-medium text-white bg-green-600 rounded-md hover:bg-green-700 transition-colors duration-200">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection