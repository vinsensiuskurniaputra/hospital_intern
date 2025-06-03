@extends('layouts.auth')

@section('content')
<div class="p-6">
    <div class="bg-white rounded-lg shadow">
        <!-- Profile Header -->
        <div class="relative bg-[#9CDBA6] rounded-t-lg pt-8 p-6">
            <div class="flex items-start gap-4">
                <div class="relative">
                    <div class="w-24 h-24 bg-gray-200 rounded-full">
                        <img src="{{ $user->photo_profile_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) }}" 
                             alt="Profile Picture"
                             class="w-full h-full rounded-full">
                    </div>
                    <button class="absolute bottom-0 right-0 p-1 bg-white rounded-full shadow-lg">
                        <i class="bi bi-pencil text-sm text-gray-600"></i>
                    </button>
                </div>
                <div class="flex-1 ml-4">
                    <div>
                        <h1 class="text-2xl font-bold text-white">{{ $user->name }}</h1>
                        <p class="text-gray-100">{{ $user->roles()->first()->display_name }}</p>
                        <p class="text-gray-100 mt-1">Dokter Spesialisasi Bedah Saraf</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-end gap-2 px-6 mt-4">
            <a href="{{ route('responsible.profile.edit') }}" class="px-6 py-2 bg-[#E8F3DC] text-[#637F26] rounded-lg shadow-sm hover:bg-[#E8F3DC]/80 font-medium">
                Edit
            </a>
        </div>

        <!-- Profile Content -->
        <div class="p-6">
            <!-- Personal Information -->
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm text-gray-600">Nama Lengkap</label>
                    <input type="text" value="{{ $user->name }}" 
                           class="mt-1 block w-full p-2 border border-gray-200 rounded-lg bg-gray-50" readonly>
                </div>
                <div>
                    <label class="block text-sm text-gray-600">Alamat Email</label>
                    <input type="text" value="{{ $user->email }}" 
                           class="mt-1 block w-full p-2 border border-gray-200 rounded-lg bg-gray-50" readonly>
                </div>
                <div>
                    <label class="block text-sm text-gray-600">Nomor Induk Pegawai</label>
                    <input type="email" value="1119999212121" 
                           class="mt-1 block w-full p-2 border border-gray-200 rounded-lg bg-gray-50" readonly>
                </div>
                <div>
                    <label class="block text-sm text-gray-600">Nomor Telepon</label>
                    <input type="text" value="{{ $responsible->telp }}" 
                           class="mt-1 block w-full p-2 border border-gray-200 rounded-lg bg-gray-50" readonly>
                </div>
                <div class="col-span-2">
                    <label class="block text-sm text-gray-600">Spesialisasi</label>
                    <input type="text" value="Dokter Spesialisasi Bedah Saraf" 
                           class="mt-1 block w-full p-2 border border-gray-200 rounded-lg bg-gray-50" readonly>
                </div>
            </div>

            <!-- Security Settings -->
            <div class="mt-8">
                <div class="border rounded-lg">
                    <a href="{{ route('responsible.profile.change-password') }}" 
                       class="w-full px-4 py-3 text-left hover:bg-gray-50 flex justify-between items-center">
                        <span class="font-medium">Ganti Password</span>
                        <i class="bi bi-chevron-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection