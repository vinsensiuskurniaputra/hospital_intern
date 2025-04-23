@extends('layouts.auth')

@section('content')
<div class="p-6">
    <div class="bg-white rounded-lg shadow p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-4">Profile Mahasiswa</h1>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="md:col-span-1">
                <div class="flex flex-col items-center p-4 border border-gray-200 rounded-lg">
                    <img src="{{ $user->photo_profile_url ?? 'https://ui-avatars.com/api/?name='.urlencode($user->name) }}" 
                         alt="Profile" class="w-32 h-32 rounded-full mb-4">
                    <h2 class="text-xl font-semibold">{{ $user->name }}</h2>
                    <p class="text-gray-600">{{ $student->nim ?? 'NIM tidak tersedia' }}</p>
                    <button class="mt-4 px-4 py-2 bg-[#637F26] text-white rounded-lg hover:bg-[#4e6320] transition">
                        Edit Profile
                    </button>
                </div>
            </div>
            
            <div class="md:col-span-2">
                <div class="border border-gray-200 rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4">Informasi Pribadi</h3>
                    
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-500">Nama Lengkap</p>
                                <p class="font-medium">{{ $user->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Email</p>
                                <p class="font-medium">{{ $user->email }}</p>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-500">NIM</p>
                                <p class="font-medium">{{ $student->nim ?? 'Tidak tersedia' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Program Studi</p>
                                <p class="font-medium">{{ $student->studyProgram->name ?? 'Tidak tersedia' }}</p>
                            </div>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-500">No. Telepon</p>
                            <p class="font-medium">{{ $student->telp ?? 'Tidak tersedia' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection