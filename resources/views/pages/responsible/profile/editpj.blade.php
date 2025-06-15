@extends('layouts.auth')

@section('content')
<div class="p-6">
    <div class="bg-white rounded-lg shadow">
        <!-- Profile Header -->
        <div class="relative bg-[#9CDBA6] rounded-t-lg pt-8 p-6">
            <div class="flex items-start gap-4">
                <div class="relative">
                    <div class="w-24 h-24 bg-gray-200 rounded-full">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name)  }}" 
                             alt="Profile Picture"
                             class="w-full h-full rounded-full">
                    </div>
                </div>
                <div class="flex-1 ml-4">
                    <div class="flex justify-between items-start">
                        <div>
                            <h1 class="text-2xl font-bold text-white">{{ $user->name }}</h1>
                            <p class="text-gray-100">{{ Auth::user()->roles()->first()->display_name }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Edit Profile -->
        <div class="p-6">
            <form method="POST" action="{{ route('responsible.profile.update') }}" id="editProfileForm">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-2 gap-6">
                    <!-- Tambahkan field Nama Lengkap -->
                    <div>
                        <label class="block text-sm text-gray-600">Nama Lengkap</label>
                        <input type="text" 
                               name="name" 
                               id="name"
                               value="{{ $user->name }}"
                               class="mt-1 block w-full p-2 border border-gray-200 rounded-lg @error('name') border-red-500 @enderror">
                        @error('name')
                            <span class="text-sm text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm text-gray-600">Alamat Email</label>
                        <input type="email" 
                               name="email" 
                               id="email"
                               value="{{ $user->email }}"
                               class="mt-1 block w-full p-2 border border-gray-200 rounded-lg @error('email') border-red-500 @enderror">
                        <span id="emailError" class="text-sm text-red-500 hidden">Email tidak valid</span>
                        @error('email')
                            <span class="text-sm text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm text-gray-600">Nomor Telepon</label>
                        <input type="text" 
                               name="telp" 
                               id="telp"
                               value="{{ $user->responsible->telp ?? '' }}"
                               class="mt-1 block w-full p-2 border border-gray-200 rounded-lg @error('telp') border-red-500 @enderror">
                        <span id="telpError" class="text-sm text-red-500 hidden">Nomor telepon harus berupa angka</span>
                        @error('telp')
                            <span class="text-sm text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <a href="{{ route('responsible.profile') }}" 
                       class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        Batal
                    </a>
                    <button type="submit" 
                            class="px-4 py-2 bg-[#9CDBA6] text-black rounded-lg hover:bg-[#9CDBA6]/90">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

<!-- Add JavaScript validation -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('editProfileForm');
    const emailInput = document.getElementById('email');
    const telpInput = document.getElementById('telp');
    const emailError = document.getElementById('emailError');
    const telpError = document.getElementById('telpError');

    function validateEmail(email) {
        return email.includes('@') && email.includes('.');
    }

    function validateTelp(telp) {
        return /^\d+$/.test(telp);
    }

    form.addEventListener('submit', function(e) {
        let isValid = true;

        // Validate email
        if (!validateEmail(emailInput.value)) {
            emailError.classList.remove('hidden');
            emailInput.classList.add('border-red-500');
            isValid = false;
        } else {
            emailError.classList.add('hidden');
            emailInput.classList.remove('border-red-500');
        }

        // Validate phone number
        if (!validateTelp(telpInput.value)) {
            telpError.classList.remove('hidden');
            telpInput.classList.add('border-red-500');
            isValid = false;
        } else {
            telpError.classList.add('hidden');
            telpInput.classList.remove('border-red-500');
        }

        if (!isValid) {
            e.preventDefault();
        }
    });

    // Real-time validation
    emailInput.addEventListener('input', function() {
        if (!validateEmail(this.value)) {
            emailError.classList.remove('hidden');
            this.classList.add('border-red-500');
        } else {
            emailError.classList.add('hidden');
            this.classList.remove('border-red-500');
        }
    });

    telpInput.addEventListener('input', function() {
        if (!validateTelp(this.value)) {
            telpError.classList.remove('hidden');
            this.classList.add('border-red-500');
        } else {
            telpError.classList.add('hidden');
            this.classList.remove('border-red-500');
        }
    });
});
</script>