@extends('layouts.auth')

@section('title', 'Edit Students')

@section('content')
    <div class="p-10" x-data="{
        selectedCampus: '',
        selectedClassYear: '',
        programStudies: [],
        internshipClasses: [],
        allProgramStudies: {{ json_encode($campuses->mapWithKeys(fn($campus) => [$campus->id => $campus->studyPrograms])) }},
        allInternshipClasses: {{ json_encode($classYears->mapWithKeys(fn($classYear) => [$classYear->id => $classYear->internshipClasses])) }}
    }">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6">Edit Student</h2>

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
        <form action="{{ route('admin.students.update', $student->id) }}" method="POST" enctype="multipart/form-data"
            class="bg-white p-6 shadow-lg rounded-lg">
            @csrf
            @method('PUT')

            <!-- Profile Picture Upload -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Profile Picture</label>
                <div class="flex flex-col items-center justify-center">
                    <div id="imagePreviewContainer" class="mb-4 {{ $student->photo_profile_url ? '' : 'hidden' }}">
                        <img id="imagePreview"
                            src="{{ $student->photo_profile_url ? asset('storage/' . $student->photo_profile_url) : '' }}"
                            class="w-32 h-32 rounded-full object-cover border-4 border-gray-300">
                    </div>
                    <div class="w-full">
                        <label
                            class="flex flex-col items-center w-full px-4 py-6 border-2 border-dashed rounded-lg cursor-pointer hover:bg-gray-50">
                            <i class="bi bi-cloud-arrow-up text-3xl text-gray-400"></i>
                            <span class="mt-2 text-sm text-gray-500">Drag and drop your photo here, or</span>
                            <span class="mt-1 text-sm font-medium text-green-700">Browse Files</span>
                            <input type="file" name="photo_profile_url" id="imageInput" class="hidden" accept="image/*">
                        </label>
                    </div>
                </div>
            </div>

            <!-- Other Form Fields -->
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                    <input type="text" name="username" value="{{ old('username', $student->user->username) }}" required
                        class="w-full px-4 py-2 border rounded-lg @error('username') border-red-300 ring-red-100 @else focus:ring-[#637F26] focus:border-[#637F26] @enderror">
                    @error('username')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">NIM</label>
                    <input type="text" name="nim" value="{{ old('nim', $student->nim) }}" required
                        class="w-full px-4 py-2 border rounded-lg @error('nim') border-red-300 ring-red-100 @else focus:ring-[#637F26] focus:border-[#637F26] @enderror">
                    @error('nim')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                    <input type="text" name="name" value="{{ old('name', $student->user->name) }}" required
                        class="w-full px-4 py-2 border rounded-lg @error('name') border-red-300 ring-red-100 @else focus:ring-[#637F26] focus:border-[#637F26] @enderror">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email', $student->user->email) }}"
                        class="w-full px-4 py-2 border rounded-lg @error('email') border-red-300 ring-red-100 @else focus:ring-[#637F26] focus:border-[#637F26] @enderror">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Campus Selection -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Campus</label>
                    <select x-init="new TomSelect($el, { create: false, sortField: 'text' })" x-model="selectedCampus"
                        @change="programStudies = allProgramStudies[selectedCampus] || []" class="w-full">
                        <option value="{{ $student->studyProgram->campus->id }}">
                            {{ $student->studyProgram->campus->name }}</option>
                        @foreach ($campuses as $campus)
                            <option value="{{ $campus->id }}">{{ $campus->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Study Program -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Study Program</label>
                    <select name="study_program_id" required class="w-full px-4 py-2 border border-gray-200 rounded-lg">
                        <option value="{{ $student->studyProgram->id }}">{{ $student->studyProgram->name }}</option>
                        <template x-for="study in programStudies" :key="study.id">
                            <option :value="study.id" x-text="study.name"></option>
                        </template>
                    </select>
                    @error('study_program_id')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Class Year Selection -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Class Year</label>
                    <select x-init="new TomSelect($el, { create: false, sortField: 'text' })" x-model="selectedClassYear"
                        @change="internshipClasses = allInternshipClasses[selectedClassYear] || []" class="w-full">
                        <option value="{{ $student->internshipClass->classYear->id ?? null }}">
                            {{ $student->internshipClass->classYear->id ?? 'Select Class Year' }}</option>
                        @foreach ($classYears as $classYear)
                            <option value="{{ $classYear->id }}">{{ $classYear->class_year }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Internship Class -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Internship Class</label>
                    <select name="internship_class_id" class="w-full px-4 py-2 border border-gray-200 rounded-lg">
                        <option value="{{ $student->internshipClass->id ?? null }}">
                            {{ $student->internshipClass->name ?? 'Select Internship Class' }}</option>
                        <template x-for="internshipClass in internshipClasses" :key="internshipClass.id">
                            <option :value="internshipClass.id" x-text="internshipClass.name"></option>
                        </template>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">No. Telp</label>
                    <input type="text" name="telp" value="{{ old('telp', $student->telp) }}" required
                        class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#637F26] focus:border-[#637F26]">
                    @error('telp')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-end gap-3 pt-6">
                    <a href="{{ route('admin.students.index') }}"
                        class="px-4 py-2 text-sm font-medium text-red-600 bg-white border border-red-600 rounded-lg hover:bg-red-50">
                        Cancel
                    </a>
                    <button type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-green-700 rounded-lg hover:bg-green-800">
                        Save Changes
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection
