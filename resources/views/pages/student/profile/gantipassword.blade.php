@extends('layouts.auth')

@section('content')
<div class="p-6">
    <div class="bg-white rounded-lg shadow">
        <!-- Profile Header -->
        <div class="relative bg-[#9CDBA6] rounded-t-lg pt-8 p-6">
            <div class="flex items-start gap-4">
                <div class="relative">
                    <div class="w-24 h-24 bg-gray-200 rounded-full overflow-hidden">
                        @if($results['foto'])
                            <img src="{{ asset('storage/' . $results['foto']) }}" 
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
                        <p class="text-gray-100 mt-1">Mahasiswa {{ $results['prodi'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Ganti Password -->
        <div class="p-6">
            <h2 class="text-lg font-semibold mb-6">Ganti Password</h2>
            <form method="POST" action="{{ route('student.profile.update-password') }}" class="space-y-6" id="passwordForm">
                @csrf
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm text-gray-600 mb-2">Password Lama</label>
                        <input type="password" 
                               name="current_password" 
                               placeholder="Masukkan Password Saat Ini"
                               class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-lg @error('current_password') border-red-500 @enderror">
                        @error('current_password')
                            <span class="text-sm text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm text-gray-600 mb-2">Password Baru</label>
                        <input type="password" 
                               name="new_password" 
                               id="new_password"
                               placeholder="Masukkan Kata Sandi Baru"
                               class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-lg @error('new_password') border-red-500 @enderror">
                        @error('new_password')
                            <span class="text-sm text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm text-gray-600 mb-2">Konfirmasi Password Baru</label>
                        <input type="password" 
                               name="new_password_confirmation" 
                               id="new_password_confirmation"
                               placeholder="Konfirmasi Kata Sandi Baru"
                               class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-lg">
                        <span id="password_error" class="text-sm text-red-500 hidden">Password tidak sesuai</span>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <a href="{{ route('student.profile') }}" 
                       class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        Batal
                    </a>
                    <button type="submit" 
                            id="submit_btn"
                            class="px-4 py-2 bg-[#9CDBA6] text-black rounded-lg hover:bg-[#9CDBA6]/90">
                        Ganti Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('passwordForm');
    const newPassword = document.getElementById('new_password');
    const confirmPassword = document.getElementById('new_password_confirmation');
    const passwordError = document.getElementById('password_error');
    const submitBtn = document.getElementById('submit_btn');

    function validatePasswords() {
        if(newPassword.value !== confirmPassword.value) {
            passwordError.classList.remove('hidden');
            confirmPassword.classList.add('border-red-500');
            submitBtn.disabled = true;
            return false;
        } else {
            passwordError.classList.add('hidden');
            confirmPassword.classList.remove('border-red-500');
            submitBtn.disabled = false;
            return true;
        }
    }

    // Validate on input
    confirmPassword.addEventListener('input', validatePasswords);
    newPassword.addEventListener('input', validatePasswords);

    // Validate on form submit
    form.addEventListener('submit', function(e) {
        if (!validatePasswords()) {
            e.preventDefault();
        }
    });
});
</script>