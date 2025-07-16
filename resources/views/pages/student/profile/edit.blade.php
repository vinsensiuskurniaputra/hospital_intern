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

        <!-- Form Edit Profile -->
        <div class="p-6">
            <form method="POST" action="{{ route('student.profile.update') }}" id="editProfileForm">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm text-gray-600">Alamat Email</label>
                        <input type="email" 
                               name="email" 
                               id="email"
                               value="{{ $results['email'] }}"
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
                               value="{{ $results['telp'] }}"
                               class="mt-1 block w-full p-2 border border-gray-200 rounded-lg @error('telp') border-red-500 @enderror">
                        <span id="telpError" class="text-sm text-red-500 hidden">Nomor telepon harus dimulai dengan 0 dan terdiri dari 10-13 digit</span>
                        @error('telp')
                            <span class="text-sm text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <a href="{{ route('student.profile') }}" 
                       class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        Batal
                    </a>
                    <button type="submit" 
                            id="submitBtn"
                            class="px-4 py-2 bg-[#9CDBA6] text-black rounded-lg hover:bg-[#9CDBA6]/90">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('editProfileForm');
    const emailInput = document.getElementById('email');
    const telpInput = document.getElementById('telp');
    const emailError = document.getElementById('emailError');
    const telpError = document.getElementById('telpError');
    const submitBtn = document.getElementById('submitBtn');

    function validateEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    function validateTelp(telp) {
        return /^0\d{9,12}$/.test(telp); // Must start with 0 and be 10-13 digits
    }

    function checkFormValidity() {
        const isEmailValid = validateEmail(emailInput.value);
        const isTelpValid = validateTelp(telpInput.value);
        
        submitBtn.disabled = !(isEmailValid && isTelpValid);
        return isEmailValid && isTelpValid;
    }

    // Real-time validation for email
    emailInput.addEventListener('input', function() {
        if (!validateEmail(this.value)) {
            emailError.classList.remove('hidden');
            this.classList.add('border-red-500');
        } else {
            emailError.classList.add('hidden');
            this.classList.remove('border-red-500');
        }
        checkFormValidity();
    });

    // Real-time validation for phone
    telpInput.addEventListener('input', function() {
        // Remove any non-digit characters
        let value = this.value.replace(/\D/g, '');
        
        // Ensure it starts with 0
        if (value.length > 0 && !value.startsWith('0')) {
            value = '0' + value;
        }
        
        // Limit to 13 digits
        if (value.length > 13) {
            value = value.substring(0, 13);
        }
        
        this.value = value;
        
        if (!validateTelp(this.value)) {
            telpError.classList.remove('hidden');
            this.classList.add('border-red-500');
        } else {
            telpError.classList.add('hidden');
            this.classList.remove('border-red-500');
        }
        checkFormValidity();
    });

    // Form submission validation
    form.addEventListener('submit', function(e) {
        if (!checkFormValidity()) {
            e.preventDefault();
            alert('Mohon periksa kembali data yang Anda masukkan');
        }
    });

    // Initial validation check
    checkFormValidity();
});
</script>
@endsection