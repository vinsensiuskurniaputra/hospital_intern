@extends('layouts.auth')

@section('content')
<div class="p-6">
    <div class="bg-white rounded-lg shadow">
        <!-- Profile Header -->
        <div class="relative bg-[#9CDBA6] rounded-t-lg pt-8 p-6">
            <div class="flex items-start gap-4">
                <div class="relative">
                    <div class="w-24 h-24 bg-gray-200 rounded-full">
                        <img src="{{ $user->photo ?? 'https://ui-avatars.com/api/?name=Ujang+Kedu' }}" 
                             alt="Profile Picture"
                             class="w-full h-full rounded-full">
                    </div>
                    <button class="absolute bottom-0 right-0 p-1 bg-white rounded-full shadow-lg">
                        <i class="bi bi-pencil text-sm text-gray-600"></i>
                    </button>
                </div>
                <div class="flex-1 ml-4">
                    <div>
                        <h1 class="text-2xl font-bold text-white">Ujang Kedu</h1>
                        <p class="text-gray-100">{{ Auth::user()->roles()->first()->display_name }}</p>
                        <p class="text-gray-100 mt-1">Dokter Spesialisasi Bedah Saraf</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-end gap-2 px-6 mt-4">
            <button class="p-2 bg-white text-gray-700 rounded-full shadow-sm hover:bg-gray-50">
                <i class="bi bi-bell text-lg"></i>
            </button>
            <button class="px-6 py-2 bg-[#E8F3DC] text-[#637F26] rounded-lg shadow-sm hover:bg-[#E8F3DC]/80 font-medium">
                Edit
            </button>
        </div>

        <!-- Profile Content -->
        <div class="p-6">
            <!-- Personal Information -->
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm text-gray-600">Nama Lengkap</label>
                    <input type="text" value="Ujang Kedu" 
                           class="mt-1 block w-full p-2 border border-gray-200 rounded-lg bg-gray-50" readonly>
                </div>
                <div>
                    <label class="block text-sm text-gray-600">Alamat Email</label>
                    <input type="text" value="martingarox123@gmail.com" 
                           class="mt-1 block w-full p-2 border border-gray-200 rounded-lg bg-gray-50" readonly>
                </div>
                <div>
                    <label class="block text-sm text-gray-600">Nomor Induk Pegawai</label>
                    <input type="email" value="112233445566" 
                           class="mt-1 block w-full p-2 border border-gray-200 rounded-lg bg-gray-50" readonly>
                </div>
                <div>
                    <label class="block text-sm text-gray-600">Nomor Telepon</label>
                    <input type="text" value="056978151005" 
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
                    <button class="w-full px-4 py-3 text-left hover:bg-gray-50 flex justify-between items-center">
                        <span class="font-medium">Ganti Password</span>
                        <i class="bi bi-chevron-right"></i>
                    </button>
                </div>
            </div>

            <!-- Notification Settings -->
            <div class="mt-8" x-data="{ emailToggle: true, smsToggle: false }">
                <div class="border rounded-lg">
                    <!-- Notification Title -->
                    <div class="p-4 border-b">
                        <h2 class="text-lg font-semibold text-gray-800">Pengaturan Notifikasi</h2>
                    </div>
                    
                    <div class="space-y-4 p-4">
                        <!-- Email Notification -->
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="font-medium">Notifikasi Email</h3>
                                <p class="text-sm text-gray-500">menerima pembaruan email tentang magang Anda</p>
                            </div>
                            <div class="relative flex-shrink-0">
                                <input type="checkbox" id="email-toggle" class="hidden" x-model="emailToggle">
                                <label for="email-toggle" class="cursor-pointer">
                                    <div class="w-11 h-6 flex items-center rounded-full duration-300 ease-in-out" 
                                         :class="emailToggle ? 'bg-[#637F26]' : 'bg-gray-300'">
                                        <div class="bg-white w-5 h-5 rounded-full shadow-md transform duration-300 ml-0.5"
                                             :class="emailToggle ? 'translate-x-5' : 'translate-x-0'">
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- SMS Notification -->
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="font-medium">Notifikasi SMS</h3>
                                <p class="text-sm text-gray-500">menerima peringatan SMS untuk pembaruan penting</p>
                            </div>
                            <div class="relative flex-shrink-0">
                                <input type="checkbox" id="sms-toggle" class="hidden" x-model="smsToggle">
                                <label for="sms-toggle" class="cursor-pointer">
                                    <div class="w-11 h-6 flex items-center rounded-full duration-300 ease-in-out"
                                         :class="smsToggle ? 'bg-[#637F26]' : 'bg-gray-300'">
                                        <div class="bg-white w-5 h-5 rounded-full shadow-md transform duration-300 ml-0.5"
                                             :class="smsToggle ? 'translate-x-5' : 'translate-x-0'">
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
