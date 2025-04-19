@php $is_has_photo = trim($__env->yieldContent('is_has_photo', 'true')) === 'true'; @endphp

<div x-show="{{ $show }}" class="fixed z-20 inset-0 bg-gray-600 bg-opacity-50 flex justify-end"
    @click="{{ $show }} = false" x-cloak>
    <div class="bg-white p-6 shadow-lg lg:w-1/3 w-full h-screen overflow-y-scroll" @click.stop>
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-semibold text-gray-800">@yield('the_title', 'Add')</h2>
            <button @click="{{ $show }} = false" class="text-gray-500 hover:text-gray-700">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <!-- Form Input -->
        <form action="@yield('route')" method="POST" enctype="multipart/form-data">
            @csrf
            @method('POST')

            @if (isset($is_has_photo) && $is_has_photo)
                <!-- Profile Picture Upload -->
                <div x-data="imageUpload()" class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Profile Picture</label>

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
                            <span class="mt-2 text-sm text-gray-500">Drag and drop your photo here, or</span>
                            <span class="mt-1 text-sm font-medium text-green-700">Browse Files</span>
                            <input type="file" name="photo_profile" class="hidden" x-ref="fileInput"
                                @change="handleFileSelect" accept="image/*">
                        </label>

                        @error('photo_profile')
                            <span class="text-red-500 text-sm mt-2">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            @endif

            <div class="space-y-4">
                @yield('input_contents')
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
                        @yield('the_title', 'Add')
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
