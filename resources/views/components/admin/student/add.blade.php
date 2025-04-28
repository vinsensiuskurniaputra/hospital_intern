<div x-show="{{ $show }}" class="fixed z-20 inset-0 bg-gray-600 bg-opacity-50 flex justify-end"
    @click="{{ $show }} = false" x-cloak x-data="{
        selectedCampus: '',
        selectedClassYear: '',
        programStudies: [],
        internshipClasses: [],
        allProgramStudies: {{ json_encode($campuses->mapWithKeys(fn($campus) => [$campus->id => $campus->studyPrograms])) }},
        allInternshipClasses: {{ json_encode($classYears->mapWithKeys(fn($classYear) => [$classYear->id => $classYear->internshipClasses])) }}
    }">
    <div class="bg-white p-6 shadow-lg lg:w-1/3 w-full h-screen overflow-y-scroll" @click.stop>
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-semibold text-gray-800">Tambah Siswa Baru</h2>
            <button @click="{{ $show }} = false" class="text-gray-500 hover:text-gray-700">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <!-- Form Input -->
        <form action="{{ route('admin.students.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Profile Picture Upload -->
            <div x-data="imageUpload()" class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Foto Profil</label>

                <!-- Upload Area -->
                <div class="w-full border-2 border-dashed rounded-lg p-4 relative flex flex-col items-center justify-center"
                    :class="{ 'border-gray-300 bg-gray-50': !imageUrl, 'border-green-500 bg-white': imageUrl }">

                    <!-- Image Preview (di dalam area upload) -->
                    <template x-if="imageUrl">
                        <div class="relative flex flex-col items-center">
                            <img :src="imageUrl"
                                class="w-32 h-32 rounded-full object-cover border-4 border-gray-200 shadow">
                            <!-- Remove Image Button -->
                            <button type="button" @click="removeImage"
                                class="absolute top-0 right-0 bg-red-500 text-white rounded-full w-8 h-8 flex items-center justify-center shadow-lg hover:bg-red-600">
                                <i class="bi bi-x"></i>
                            </button>
                        </div>
                    </template>

                    <!-- Label Upload (hanya muncul jika belum ada gambar) -->
                    <label x-show="!imageUrl"
                        class="flex flex-col items-center w-full p-6 cursor-pointer hover:bg-gray-100 transition rounded-lg">
                        <i class="bi bi-cloud-arrow-up text-3xl text-gray-400"></i>
                        <span class="mt-2 text-sm text-gray-500">Seret dan lepaskan fotomu di sini, atau</span>
                        <span class="mt-1 text-sm font-medium text-green-700">Telusuri Berkas</span>
                        <input type="file" name="photo_profile" class="hidden" x-ref="fileInput"
                            @change="handleFileSelect" accept="image/*">
                    </label>

                    @error('photo_profile')
                        <span class="text-red-500 text-sm mt-2">{{ $message }}</span>
                    @enderror
                </div>
            </div>


            <!-- Other Form Fields -->
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                    <input type="text" name="username" value="{{ old('username') }}" required
                        class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#637F26] focus:border-[#637F26]">
                    @error('username')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">NIM</label>
                    <input type="text" name="nim" value="{{ old('nim') }}" required
                        class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#637F26] focus:border-[#637F26]">
                    @error('nim')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#637F26] focus:border-[#637F26]">
                    @error('name')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                        class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#637F26] focus:border-[#637F26]">
                    @error('email')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Campus Selection -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Campus</label>
                    <select x-init="new TomSelect($el, { create: false, sortField: 'text' })" x-model="selectedCampus"
                        @change="programStudies = allProgramStudies[selectedCampus] || []"
                        class="w-full px-4 py-2 border border-gray-200 rounded-lg">
                        <option value="">Select Campus</option>
                        @foreach ($campuses as $campus)
                            <option value="{{ $campus->id }}">{{ $campus->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Study Program -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Study Program</label>
                    <select name="study_program_id" required class="w-full px-4 py-2 border border-gray-200 rounded-lg">
                        <option value="">Select Study Program</option>
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
                        @change="internshipClasses = allInternshipClasses[selectedClassYear] || []"
                        class="w-full px-4 py-2 border border-gray-200 rounded-lg">
                        <option value="">Select Class Year</option>
                        @foreach ($classYears as $classYear)
                            <option value="{{ $classYear->id }}">{{ $classYear->class_year }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Internship Class -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Internship Class</label>
                    <select name="internship_class_id" class="w-full px-4 py-2 border border-gray-200 rounded-lg">
                        <option value="">Select Internship Class</option>
                        <template x-for="internshipClass in internshipClasses" :key="internshipClass.id">
                            <option :value="internshipClass.id" x-text="internshipClass.name"></option>
                        </template>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">No. Telp</label>
                    <input type="text" name="telp" value="{{ old('telp') }}" required
                        class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#637F26] focus:border-[#637F26]">
                    @error('telp')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-end gap-3 pt-6">
                    <button type="button" @click="{{ $show }} = false"
                        class="px-4 py-2 text-sm font-medium text-red-600 bg-white border border-red-600 rounded-lg 
                        hover:bg-red-50 transition-colors duration-200">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-[#637F26] rounded-lg 
                        hover:bg-[#85A832] transition-colors duration-200 shadow-sm hover:shadow-md">
                        Add Student
                    </button>
                </div>
            </div>
        </form>

    </div>
</div>

<script>
    function imageUpload(initialUrl = '') {
        return {
            imageUrl: initialUrl || null,
            handleFileSelect(event) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.imageUrl = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            },
            removeImage() {
                this.imageUrl = null;
                this.$refs.fileInput.value = null; // Reset input file
            }
        };
    }
</script>
