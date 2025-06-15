@extends('layouts.auth')

@section('content')
<div class="min-h-screen bg-[#f3f3f3] py-6">
    <div class="max-w-5xl mx-auto">
        <!-- Profile Header -->
        <div class="bg-[#9CDBA6] rounded-lg p-6 mb-6">
            <div class="flex items-center gap-6">
                <div class="w-24 h-24 bg-gray-300 rounded-full flex items-center justify-center text-2xl">
                    {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                </div>
                <div class="text-white">
                    <h1 class="text-2xl font-bold">{{ Auth::user()->name }}</h1>
                    <p>Penanggung Jawab</p>
                    <div class="flex gap-4 mt-2">
                       
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Ganti Password -->
        <div class="bg-white rounded-lg p-6">
            <h2 class="text-xl font-bold mb-6">Ganti Password</h2>
            
            <form method="POST" action="{{ route('responsible.profile.update-password') }}" id="passwordForm">
                @csrf
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="block text-gray-700 mb-2">Password Lama</label>
                        <input type="password" 
                               name="current_password" 
                               class="w-full px-4 py-2 rounded-md border border-gray-200 focus:outline-none focus:border-[#9CDBA6]"
                               placeholder="Masukkan Password Saat Ini"
                               required>
                        @error('current_password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-gray-700 mb-2">Password Baru</label>
                        <input type="password" 
                               name="new_password" 
                               id="new_password"
                               class="w-full px-4 py-2 rounded-md border border-gray-200 focus:outline-none focus:border-[#9CDBA6]"
                               placeholder="Masukkan Kata Sandi Baru"
                               required>
                        @error('new_password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-gray-700 mb-2">Konfirmasi Password Baru</label>
                        <input type="password" 
                               name="new_password_confirmation" 
                               id="new_password_confirmation"
                               class="w-full px-4 py-2 rounded-md border border-gray-200 focus:outline-none focus:border-[#9CDBA6]"
                               placeholder="Konfirmasi Kata Sandi Baru"
                               required>
                        <p id="password_error" class="mt-1 text-sm text-red-600 hidden">Password tidak cocok</p>
                    </div>
                </div>

                <div class="flex justify-end gap-4 mt-8">
                    <button type="button" 
                            onclick="window.history.back()"
                            class="px-6 py-2 rounded-md border border-gray-300 hover:bg-gray-50">
                        Batal
                    </button>
                    <button type="submit" 
                            id="submit_btn"
                            class="px-6 py-2 bg-[#9CDBA6] text-white rounded-md hover:bg-[#8BC996]">
                        Ganti Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

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
            submitBtn.disabled = true;
            return false;
        } else {
            passwordError.classList.add('hidden');
            submitBtn.disabled = false;
            return true;
        }
    }

    confirmPassword.addEventListener('input', validatePasswords);
    newPassword.addEventListener('input', validatePasswords);

    form.addEventListener('submit', function(e) {
        if (!validatePasswords()) {
            e.preventDefault();
        }
    });
});
</script>
@endsection