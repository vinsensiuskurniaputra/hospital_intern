@extends('layouts.auth')

@section('title', 'Edit Admin')

@section('content')
    <div class="p-10">
        <div class="flex items-center mb-6">
            <a href="{{ url()->previous() }}"><i class="bi bi-chevron-left mr-4  fw-bold"></i></a>
            <h2 class="text-2xl font-semibold text-gray-800">Edit Admin</h2>
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
        <form action="{{ route('admin.admins.update', $user->id) }}" method="POST" enctype="multipart/form-data"
            class="bg-white p-6 shadow-lg rounded-lg">
            @csrf
            @method('PUT')

            <!-- Profile Picture Upload -->
            <div x-data="imageUpload('{{ $user->photo_profile_url ? asset('storage/' . $user->photo_profile_url) : '' }}')" class="mb-6">
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

            <!-- Other Form Fields -->
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                    <input type="text" name="username" value="{{ old('username', $user->username) }}" required
                        class="w-full px-4 py-2 border rounded-lg @error('username') border-red-300 ring-red-100 @else focus:ring-[#637F26] focus:border-[#637F26] @enderror">
                    @error('username')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                        class="w-full px-4 py-2 border rounded-lg @error('name') border-red-300 ring-red-100 @else focus:ring-[#637F26] focus:border-[#637F26] @enderror">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}"
                        class="w-full px-4 py-2 border rounded-lg @error('email') border-red-300 ring-red-100 @else focus:ring-[#637F26] focus:border-[#637F26] @enderror">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" name="password" value="{{ old('password') }}"
                        class="w-full px-4 py-2 border rounded-lg @error('password') border-red-300 ring-red-100 @else focus:ring-[#637F26] focus:border-[#637F26] @enderror">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password Confirmation</label>
                    <input type="password" name="password_confirmation" value="{{ old('password_confirmation') }}"
                        class="w-full px-4 py-2 border rounded-lg @error('password_confirmation') border-red-300 ring-red-100 @else focus:ring-[#637F26] focus:border-[#637F26] @enderror">
                    @error('password_confirmation')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>


                <!-- Action Buttons -->
                <div class="flex items-center justify-end gap-3 pt-6">
                    <a href="{{ route('admin.admins.index') }}"
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
