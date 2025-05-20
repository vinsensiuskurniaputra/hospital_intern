@extends('layouts.admin.edit')

@section('title', 'Insert Students to Class')
@section('the_title', 'Insert Students to Class')
@section('route_back', route('admin.internshipClasses.index'))
@section('route', route('admin.internshipClasses.insertStudent.store'))
@section('is_has_photo', 'false')
@section('method')
    @method('POST')
@endsection
@section('input_contents')
    <div x-data="{
        selectedCampus: '',
        students: [],
        internshipClasses: [],
        searchTerm: '',
        selectedClassId: '',
        allStudents: {{ json_encode(
            $campuses->mapWithKeys(function ($campus) {
                return [
                    $campus->id => $campus->studyPrograms->flatMap(function ($program) {
                        return $program->students->map(
                            fn($student) => [
                                'id' => $student->id,
                                'name' => $student->user->name,
                                'nim' => $student->nim,
                                'selected' => !is_null($student->internship_class_id),
                                'current_class_id' => $student->internship_class_id,
                            ],
                        );
                    }),
                ];
            }),
        ) }},
        allInternshipClasses: {{ json_encode($campuses->mapWithKeys(fn($campus) => [$campus->id => $campus->internshipClasses])) }},
        step: 1,
        get progress() {
            return (this.step / 3) * 100;
        }
    }" class="space-y-6">
        <!-- Progress Bar -->
        <div class="relative pt-8">
            <div class="w-full h-2 bg-gray-200 rounded-full overflow-hidden">
                <div class="h-full bg-[#637F26] transition-all duration-300" :style="{ width: `${progress}%` }"></div>
            </div>
            <div class="absolute top-0 w-full flex justify-between">
                <div class="flex flex-col items-center">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center"
                        :class="step >= 1 ? 'bg-[#637F26] text-white' : 'bg-gray-200 text-gray-500'">1</div>
                    <span class="mt-2 text-xs font-medium" :class="step >= 1 ? 'text-[#637F26]' : 'text-gray-500'">Select
                        Campus</span>
                </div>
                <div class="flex flex-col items-center">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center"
                        :class="step >= 2 ? 'bg-[#637F26] text-white' : 'bg-gray-200 text-gray-500'">2</div>
                    <span class="mt-2 text-xs font-medium" :class="step >= 2 ? 'text-[#637F26]' : 'text-gray-500'">Choose
                        Class</span>
                </div>
                <div class="flex flex-col items-center">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center"
                        :class="step >= 3 ? 'bg-[#637F26] text-white' : 'bg-gray-200 text-gray-500'">3</div>
                    <span class="mt-2 text-xs font-medium" :class="step >= 3 ? 'text-[#637F26]' : 'text-gray-500'">Select
                        Students</span>
                </div>
            </div>
        </div>

        <!-- Step 1: Campus Selection -->
        <div x-show="step === 1" x-transition class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <label class="block text-sm font-medium text-gray-700 mb-2">Select Campus</label>
            <select x-model="selectedCampus"
                @change="internshipClasses = allInternshipClasses[selectedCampus] || []; step = 2"
                class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#637F26]">
                <option value="">Choose a campus...</option>
                @foreach ($campuses as $campus)
                    <option value="{{ $campus->id }}">{{ $campus->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Step 2: Class Selection -->
        <div x-show="step === 2" x-transition class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <label class="block text-sm font-medium text-gray-700 mb-2">Select Internship Class</label>
            <!-- Update the class selection part -->
            <select name="internship_class_id" x-model="selectedClassId"
                @change="if($event.target.value) { 
                step = 3; 
                students = allStudents[selectedCampus] || [];
                // Update selected status based on current class
                students.forEach(s => {
                    s.selected = (s.current_class_id == selectedClassId);
                });
            }"
                class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#637F26]">
                <option value="">Choose a class...</option>
                <template x-for="cls in internshipClasses" :key="cls.id">
                    <option :value="cls.id" x-text="cls.name"></option>
                </template>
            </select>
        </div>

        <!-- Step 3: Student Selection -->
        <div x-show="step === 3" x-transition class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-800">Select Students</h3>
                <span class="text-sm bg-[#F5F7F0] text-[#637F26] px-3 py-1 rounded-full"
                    x-text="`${students.filter(s => s.selected).length} selected`"></span>
            </div>

            <!-- Search and Select All -->
            <div class="flex gap-4 mb-6">
                <div class="flex-1 relative">
                    <input type="text" x-model="searchTerm"
                        class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#637F26]"
                        placeholder="Search students...">
                    <i class="bi bi-search absolute left-3 top-2.5 text-gray-400"></i>
                </div>
                <label class="flex items-center gap-2 text-sm text-gray-600">
                    <input type="checkbox" @click="students.forEach(s => s.selected = $event.target.checked)"
                        class="rounded border-gray-300 text-[#637F26] focus:ring-[#637F26]">
                    <span>Select all</span>
                </label>
            </div>

            <!-- Students Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 max-h-[400px] overflow-y-auto p-1">
                <template
                    x-for="student in students.filter(s => !searchTerm || s.name.toLowerCase().includes(searchTerm.toLowerCase()))"
                    :key="student.id">
                    <label
                        class="flex items-center p-3 rounded-lg border border-gray-100 hover:bg-[#F5F7F0] cursor-pointer transition-colors">
                        <input type="checkbox" name="students[]" :value="student.id" x-model="student.selected"
                            class="rounded border-gray-300 text-[#637F26] focus:ring-[#637F26]">
                        <div class="ml-3 flex-1">
                            <p class="text-sm font-medium text-gray-800" x-text="student.name"></p>
                            <p class="text-xs text-gray-500" x-text="student.nim"></p>
                        </div>
                        <div class="w-2 h-2 rounded-full" :class="student.selected ? 'bg-[#637F26]' : 'bg-gray-200'"></div>
                    </label>
                </template>
            </div>
        </div>

        <!-- Navigation Buttons -->
        <div class="flex justify-between pt-6">

            <a @click="step--" x-show="step > 1"
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                <i class="bi bi-arrow-left mr-2"></i>Previous
            </a>

        </div>
    </div>
@endsection
