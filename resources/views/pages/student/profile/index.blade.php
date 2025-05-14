@extends('layouts.auth')

@section('content')

    <div class="p-6">
        <div class="bg-white rounded-lg shadow">
            <!-- Profile Header -->
            <div class="relative bg-[#9CDBA6] rounded-t-lg pt-8 p-6"> <!-- Changed pt-10 to pt-8 -->
                <div class="flex items-start gap-4">
                    <div class="relative">
                        <div class="w-24 h-24 bg-gray-200 rounded-full">
                            <img src="https://ui-avatars.com/api/?name=Alwan+Danny+Latif" alt="Profile Picture"
                                class="w-full h-full rounded-full">
                        </div>
                        <button class="absolute bottom-0 right-0 p-1 bg-white rounded-full shadow-lg">
                            <i class="bi bi-pencil text-sm text-gray-600"></i>
                        </button>
                    </div>
                    <div class="flex-1 ml-4"> <!-- Added ml-4 here -->
                        <div class="flex justify-between items-start">
                            <div>
                                <h1 class="text-2xl font-bold text-white">{{ $user->name }}</h1>
                                <p class="text-gray-100">{{ Auth::user()->roles()->first()->display_name }}</p>
                                <p class="text-gray-100 mt-1">Mahasiswa {{ $student->studyProgram->name }}</p>
                            </div>
                            <div class="text-white">
                                <p class="text-sm font-medium mb-2">Proses Magang</p>
                                <div class="w-80">
                                    <div class="h-2 bg-white/30 rounded-full">
                                        <div class="h-full w-1/3 bg-[#3B82F6] rounded-full"></div>
                                        <!-- Changed bg-white to bg-[#3B82F6] -->
                                    </div>
                                    <div class="flex justify-between text-xs mt-2">
                                        <span>Start: 1 Januari 2025</span>
                                        <span>Selesai: 1 April 2025</span>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end gap-2 px-6 mt-4"> <!-- Keeping mt-4 -->
                <button class="p-2 bg-white text-gray-700 rounded-full shadow-sm hover:bg-gray-50">
                    <i class="bi bi-bell text-lg"></i>
                </button>
                <button
                    class="px-6 py-2 bg-[#E8F3DC] text-[#637F26] rounded-lg shadow-sm hover:bg-[#E8F3DC]/80 font-medium">
                    Edit
                </button>
            </div>

            <!-- Profile Content -->
            <div class="p-6"> <!-- Removed -mt-2 -->
                <!-- Personal Information -->
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm text-gray-600">Nama Lengkap</label>
                        <input type="text" value="{{ $user->name }}"
                            class="mt-1 block w-full p-2 border border-gray-200 rounded-lg bg-gray-50" readonly>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600">Nomor Induk Mahasiswa</label>
                        <input type="text" value="{{ $student->nim }}"
                            class="mt-1 block w-full p-2 border border-gray-200 rounded-lg bg-gray-50" readonly>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600">Asal Kampus</label>
                        <input type="text" value="{{ $student->studyProgram->campus->name }}"
                            class="mt-1 block w-full p-2 border border-gray-200 rounded-lg bg-gray-50" readonly>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600">Alamat Email</label>
                        <input type="email" value="{{ $user->email }}"
                            class="mt-1 block w-full p-2 border border-gray-200 rounded-lg bg-gray-50" readonly>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600">Program Studi</label>
                        <input type="text" value="{{ $user->studyProgram->name ?? null }}"
                            class="mt-1 block w-full p-2 border border-gray-200 rounded-lg bg-gray-50" readonly>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600">Nomor Telepon</label>
                        <input type="text" value="{{ $user->phone }}"
                            class="mt-1 block w-full p-2 border border-gray-200 rounded-lg bg-gray-50" readonly>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600">Angkatan</label>
                        <input type="text" value="{{ $student->internhipClass->classYear->class_year ?? null }}"
                            class="mt-1 block w-full p-2 border border-gray-200 rounded-lg bg-gray-50" readonly>
                    </div>
                </div>

                <!-- Security Settings -->
                <div class="mt-8">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Keamanan</h2>
                    <div class="border rounded-lg">
                        <button class="w-full px-4 py-3 text-left hover:bg-gray-50 flex justify-between items-center">
                            <span class="font-medium">Ganti Password</span>
                            <i class="bi bi-chevron-right"></i>
                        </button>
                    </div>
                </div>

                <!-- Notification Settings -->
                <div class="mt-8" x-data="{ emailToggle: true, smsToggle: false }">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Pengaturan Notifikasi</h2>
                    <div class="space-y-4 border rounded-lg p-4">
                        <div class="flex items-center justify-between pr-1">
                            <div>
                                <h3 class="font-medium">Notifikasi Email</h3>
                                <p class="text-sm text-gray-500">menerima pembaruan email tentang magang Anda</p>
                            </div>
                            <!-- Email Toggle -->
                            <div class="relative flex-shrink-0">
                                <input type="checkbox" id="email-toggle" class="hidden" x-model="emailToggle">
                                <label for="email-toggle" class="cursor-pointer">
                                    <div class="w-14 h-7 flex items-center rounded-full duration-300 ease-in-out"
                                        :class="emailToggle ? 'bg-[#637F26]' : 'bg-gray-300'">
                                        <div class="bg-white w-7 h-7 rounded-full shadow-md transform duration-300"
                                            :class="emailToggle ? 'translate-x-7' : 'translate-x-0'">
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                        <div class="flex items-center justify-between pr-1">
                            <div>
                                <h3 class="font-medium">Notifikasi SMS</h3>
                                <p class="text-sm text-gray-500">menerima peringatan SMS untuk pembaruan penting</p>
                            </div>
                            <!-- SMS Toggle -->
                            <div class="relative flex-shrink-0">
                                <input type="checkbox" id="sms-toggle" class="hidden" x-model="smsToggle">
                                <label for="sms-toggle" class="cursor-pointer">
                                    <div class="w-14 h-7 flex items-center rounded-full duration-300 ease-in-out"
                                        :class="smsToggle ? 'bg-[#637F26]' : 'bg-gray-300'">
                                        <div class="bg-white w-7 h-7 rounded-full shadow-md transform duration-300"
                                            :class="smsToggle ? 'translate-x-7' : 'translate-x-0'">
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('toggles', () => ({
                emailToggle: true,
                smsToggle: false
            }))
        })
    </script>
@endsection
