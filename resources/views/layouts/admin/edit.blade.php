@extends('layouts.auth')
@php
    $is_has_photo = trim($__env->yieldContent('is_has_photo', 'true')) === 'true';
    $photoUrl = trim($__env->yieldContent('photo_profile_url', ''))
        ? asset('storage/' . trim($__env->yieldContent('photo_profile_url', '')))
        : '';
@endphp
@section('content')
    <div class="p-10">
        <div class="flex items-center mb-6">
            <a href="@yield('route_back', url()->previous())"><i class="bi bi-chevron-left mr-4  fw-bold"></i></a>
            <h2 class="text-2xl font-semibold text-gray-800">@yield('the_title')</h2>
        </div>

        <!-- Error Summary -->
        @if ($errors->any())
            <div class="mb-6 p-4 rounded-lg bg-red-50 border-l-4 border-red-500">
                <div class="flex items-center mb-2">
                    <i class="bi bi-exclamation-circle text-red-500 mr-2"></i>
                    <h3 class="text-sm font-medium text-red-800">Terdapat kesalahan pada pengiriman Anda</h3>
                </div>
                <ul class="ml-4 text-sm text-red-700 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form Input -->
        <form action="@yield('route')" method="POST" enctype="multipart/form-data"
            class="bg-white p-6 shadow-lg rounded-lg">
            @csrf
            @method('PUT')

            @if (isset($is_has_photo) && $is_has_photo)
                <!-- Profile Picture Upload -->
                <div x-data="imageUpload('{{ $photoUrl }}')" class="mb-6">
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
            @endif

            <!-- Other Form Fields -->
            <div class="space-y-4">
                @yield('input_contents')
                <!-- Action Buttons -->
                <div class="flex items-center justify-end gap-3 pt-6">
                    <a href="@yield('route_back', url()->previous())"
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
@endsection
