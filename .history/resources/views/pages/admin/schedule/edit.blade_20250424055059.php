@extends('layouts.auth')

@section('title', 'Edit Schedule')

@section('content')
    <div class="p-10">
        <div class="flex items-center mb-6">
            <a href="{{ route('admin.schedules.index') }}"><i class="bi bi-chevron-left mr-4 fw-bold"></i></a>
            <h2 class="text-2xl font-semibold text-gray-800">Edit Schedule</h2>
        </div>

        <!-- Error Summary -->
        @if ($errors->any())
            <div class="mb-6 p-4 rounded-lg bg-red-50 border-l-4 border-red-500">
                <div class="flex items-center mb-2">
                    <i class="bi bi-exclamation-circle text-red-500 mr-2"></i>
                    <h3 class="text-sm font-medium text-red-800">There were errors with your submission</h3>
                </div>
                <ul class="ml-4 text-sm text-red-700 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form Input -->
        <form action="{{ route('admin.schedules.update', $schedule->id) }}" method="POST" class="bg-white p-6 shadow-lg rounded-lg">
            @csrf
            @method('PUT')

            <div class="space-y-4">
                <!-- Internship Class Selection -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kelas Magang</label>
                    <select name="internship_class_id" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-600">
                        <option value="">Select Class</option>
                        @foreach ($internshipClasses as $class)
                            <option value="{{ $class->id }}" {{ $schedule->internship_class_id == $class->id ? 'selected' : '' }}>
                                {{ $class->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('internship_class_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Stase Selection -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Stase</label>
                    <select name="stase_id" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-600">
                        <option value="">Select Stase</option>
                        @foreach ($stases as $stase)
                            <option value="{{ $stase->id }}" {{ $schedule->stase_id == $stase->id ? 'selected' : '' }}>
                                {{ $stase->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('stase_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Department Selection -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Departemen</label>
                    <select name="departement_id" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-600">
                        <option value="">Select Department</option>
                        @foreach ($departments as $department)
                            <option value="{{ $department->id }}" {{ $schedule->departement_id == $department->id ? 'selected' : '' }}>
                                {{ $department->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('departement_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Rotation Period -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                        <input type="date" name="start_date" value="{{ old('start_date', $schedule->start_date) }}" required
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-600">
                        @error('start_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai</label>
                        <input type="date" name="end_date" value="{{ old('end_date', $schedule->end_date) }}" required
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-600">
                        @error('end_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Time Schedule -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jam Mulai</label>
                        <input type="time" name="start_time" value="{{ old('start_time', $schedule->start_time) }}" required
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-600">
                        @error('start_time')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jam Selesai</label>
                        <input type="time" name="end_time" value="{{ old('end_time', $schedule->end_time) }}" required
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-600">
                        @error('end_time')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-end gap-3 pt-6">
                    <a href="{{ route('admin.schedules.index') }}"
                        class="px-4 py-2 text-sm font-medium text-red-600 bg-white border border-red-600 rounded-lg hover:bg-red-50">
                        Cancel
                    </a>
                    <button type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700">
                        Save Changes
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add any JavaScript functionality here if needed
            // For example, you might want to add validation or dynamic field updates
        });
    </script>
@endsection