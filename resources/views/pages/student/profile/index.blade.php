@extends('layouts.auth')

@section('content')
<div class="p-6">
    <div class="bg-white rounded-lg shadow">
        <!-- Profile Header -->
        <div class="relative bg-[#9CDBA6] rounded-t-lg pt-8 p-6">
            <div class="flex items-start gap-4">
                <div class="relative">
                    <div class="w-24 h-24 bg-gray-200 rounded-full overflow-hidden">
                        @if(isset($results['photo']))
                            <img src="{{ asset('storage/' . $results['photo']) }}" 
                                 alt="Profile Picture"
                                 class="w-full h-full object-cover rounded-full">
                        @else
                            <?php
                            $nameParts = explode(' ', $results['namaLengkap']);
                            $initials = '';
                            foreach($nameParts as $part) {
                                $initials .= substr($part, 0, 1);
                            }
                            $initials = substr($initials, 0, 2);
                            ?>
                            <div class="w-full h-full flex items-center justify-center bg-gray-300">
                                <span class="text-3xl font-bold text-gray-600">{{ strtoupper($initials) }}</span>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="flex-1 ml-4">
                    <div>
                        <h1 class="text-2xl font-bold text-white">{{ $results['namaLengkap'] }}</h1>
                        <p class="text-gray-100">{{ Auth::user()->roles()->first()->display_name }}</p>
                        <p class="text-gray-100 mt-1">Mahasiswa {{ $results['prodi']}}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-end gap-2 px-6 mt-4">
            <a href="{{ route('student.profile.edit') }}" 
               class="px-6 py-2 bg-[#E8F3DC] text-[#637F26] rounded-lg shadow-sm hover:bg-[#E8F3DC]/80 font-medium">
                Edit
            </a>
        </div>

        <!-- Profile Content -->
        <div class="p-6">
            <!-- Personal Information -->
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm text-gray-600">Nama Lengkap</label>
                    <input type="text" value="{{ $results['namaLengkap'] }}" 
                           class="mt-1 block w-full p-2 border border-gray-200 rounded-lg bg-gray-50" readonly>
                </div>
                <div>
                    <label class="block text-sm text-gray-600">Nomor Induk Mahasiswa</label>
                    <input type="text" value="{{$results['nim']}}" 
                           class="mt-1 block w-full p-2 border border-gray-200 rounded-lg bg-gray-50" readonly>
                </div>
                <div>
                    <label class="block text-sm text-gray-600">Asal Kampus</label>
                    <input type="text" value="{{$results['campus']}}" 
                           class="mt-1 block w-full p-2 border border-gray-200 rounded-lg bg-gray-50" readonly>
                </div>
                <div>
                    <label class="block text-sm text-gray-600">Alamat Email</label>
                    <input type="email" value="{{ $results['email'] }}" 
                           class="mt-1 block w-full p-2 border border-gray-200 rounded-lg bg-gray-50" readonly>
                </div>
                <div>
                    <label class="block text-sm text-gray-600">Program Studi</label>
                    <input type="text" value="{{ $results['prodi'] }}" 
                           class="mt-1 block w-full p-2 border border-gray-200 rounded-lg bg-gray-50" readonly>
                </div>
                <div>
                    <label class="block text-sm text-gray-600">Nomor Telepon</label>
                    <input type="text" value="{{$results['telp']}}" 
                           class="mt-1 block w-full p-2 border border-gray-200 rounded-lg bg-gray-50" readonly>
                </div>
                <div>
                    <label class="block text-sm text-gray-600">Angkatan</label>
                    <input type="text" value="{{$results['angkatan']}}" 
                           class="mt-1 block w-full p-2 border border-gray-200 rounded-lg bg-gray-50" readonly>
                </div>
            </div>

            <!-- Security Settings -->
            <div class="mt-8">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Keamanan</h2>
                <div class="border rounded-lg">
                    <a href="{{ route('student.profile.change-password') }}" 
                       class="w-full px-4 py-3 text-left hover:bg-gray-50 flex justify-between items-center">
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
