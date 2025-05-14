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
                                alt="Profile Picture" class="w-full h-full rounded-full">
                        </div>
                    </div>
                    <div class="flex-1 ml-4">
                        <div class="flex justify-between items-start">
                            <div>
                                <h1 class="text-2xl font-bold text-white">{{ $user->name }}</h1>
                                <p class="text-gray-100">Role: {{ $user->roles()->first()->display_name ?? 'Admin' }}</p>
                                <p class="text-gray-100 mt-1">Administrator Sistem</p>
                            </div>
                            <div class="text-white">
                                <p class="text-sm font-medium mb-2">Akses Terakhir</p>
                                <div class="text-xs">Login pada: {{ $user->updated_at->format('d M Y H:i') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end gap-2 px-6 mt-4">
                <button class="p-2 bg-white text-gray-700 rounded-full shadow-sm hover:bg-gray-50">
                    <i class="bi bi-bell text-lg"></i>
                </button>
                <a href="{{ route('admin.admins.edit', $user->id) }}"
                    class="px-6 py-2 bg-[#E8F3DC] text-[#637F26] rounded-lg shadow-sm hover:bg-[#E8F3DC]/80 font-medium">
                    Edit
                </a>
            </div>

            <!-- Profile Content -->
            <div class="p-6">
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm text-gray-600">Nama Lengkap</label>
                        <input type="text" value="{{ $user->name }}"
                            class="mt-1 block w-full p-2 border border-gray-200 rounded-lg bg-gray-50" readonly>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600">Username</label>
                        <input type="text" value="{{ $user->username }}"
                            class="mt-1 block w-full p-2 border border-gray-200 rounded-lg bg-gray-50" readonly>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600">Alamat Email</label>
                        <input type="email" value="{{ $user->email }}"
                            class="mt-1 block w-full p-2 border border-gray-200 rounded-lg bg-gray-50" readonly>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600">Tanggal Dibuat</label>
                        <input type="text" value="{{ $user->created_at->format('d M Y') }}"
                            class="mt-1 block w-full p-2 border border-gray-200 rounded-lg bg-gray-50" readonly>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
