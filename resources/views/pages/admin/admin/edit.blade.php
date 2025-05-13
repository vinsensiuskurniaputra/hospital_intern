@extends('layouts.auth')

@section('title', 'Edit Admin')

@section('content')
    <div class="p-10">
        <div class="flex items-center mb-6">
            <a href="{{ url()->previous() }}"><i class="bi bi-chevron-left mr-4 fw-bold"></i></a>
            <h2 class="text-2xl font-semibold text-gray-800">Edit Admin</h2>
        </div>

        <!-- Ringkasan Error -->
        @if ($errors->any())
            <div class="mb-6 p-4 rounded-lg bg-red-50 border-l-4 border-red-500">
                <div class="flex items-center mb-2">
                    <i class="bi bi-exclamation-circle text-red-500 mr-2"></i>
                    <h3 class="text-sm font-medium text-red-800">Terdapat kesalahan pada pengisian form</h3>
                </div>
                <ul class="ml-4 text-sm text-red-700 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form Input -->
        <form action="{{ route('admin.admins.update', $user->id) }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 shadow-lg rounded-lg">
            @csrf
            @method('PUT')

            <div x-data="imageUpload('{{ $user->photo_profile_url ? asset('storage/' . $user->photo_profile_url) : '' }}')" class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Foto Profil</label>
                <div class="w-full border-2 border-dashed rounded-lg p-4 relative flex flex-col items-center justify-center">
                    <template x-if="imageUrl">
                        <div class="relative flex flex-col items-center">
                            <img :src="imageUrl" class="w-32 h-32 rounded-full object-cover border-4 border-gray-200 shadow">
                            <button type="button" @click="removeImage" class="absolute top-0 right-0 bg-red-500 text-white rounded-full w-8 h-8 flex items-center justify-center shadow-lg hover:bg-red-600">
                                <i class="bi bi-x"></i>
                            </button>
                        </div>
                    </template>

                    <label x-show="!imageUrl" class="flex flex-col items-center w-full p-6 cursor-pointer hover:bg-gray-100 transition rounded-lg">
                        <i class="bi bi-cloud-arrow-up text-3xl text-gray-400"></i>
                        <span class="mt-2 text-sm text-gray-500">Seret dan jatuhkan foto Anda di sini, atau</span>
                        <span class="mt-1 text-sm font-medium text-green-700">Pilih Berkas</span>
                        <input type="file" name="photo_profile" class="hidden" x-ref="fileInput" @change="handleFileSelect" accept="image/*">
                    </label>
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                    <input type="text" name="username" value="{{ old('username', $user->username) }}" required class="w-full px-4 py-2 border rounded-lg">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full px-4 py-2 border rounded-lg">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full px-4 py-2 border rounded-lg">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kata Sandi</label>
                    <input type="password" name="password" class="w-full px-4 py-2 border rounded-lg">
                </div>

                <div class="flex items-center justify-end gap-3 pt-6">
                    <a href="{{ route('admin.admins.index') }}" class="px-4 py-2 text-sm font-medium text-red-600 bg-white border border-red-600 rounded-lg hover:bg-red-50">Batal</a>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-green-700 rounded-lg hover:bg-green-800">Simpan Perubahan</button>
                </div>
            </div>
        </form>
    </div>

@endsection