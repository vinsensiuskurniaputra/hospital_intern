@extends('layouts.app')

@section('title', 'Reset Kata Sandi')

@section('base-content')
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-[#F5F7F0] to-[#E8EDE0]">
        <div class="w-full max-w-md bg-white/80 backdrop-blur-sm p-8 rounded-2xl shadow-lg">
            <h2 class="text-2xl font-bold text-center text-gray-800 mb-4">Reset Kata Sandi</h2>
            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <div class="mb-4">
                    {{-- <label for="email" class="block text-sm font-medium text-gray-700">Email</label> --}}
                    <input id="email" type="email" name="email" value="{{ old('email', $email ?? '') }}" required
                        autofocus hidden
                        class="mt-1 block w-full px-4 py-3 border border-gray-200 rounded-xl shadow-sm focus:ring-2 focus:ring-[#637F26] focus:border-[#637F26] transition-all duration-200 hover:border-[#637F26]">
                    @error('email')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700">Kata Sandi Baru</label>
                    <input id="password" type="password" name="password" required
                        class="mt-1 block w-full px-4 py-3 border border-gray-200 rounded-xl shadow-sm focus:ring-2 focus:ring-[#637F26] focus:border-[#637F26] transition-all duration-200 hover:border-[#637F26]">
                    @error('password')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-6">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Kata
                        Sandi</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required
                        class="mt-1 block w-full px-4 py-3 border border-gray-200 rounded-xl shadow-sm focus:ring-2 focus:ring-[#637F26] focus:border-[#637F26] transition-all duration-200 hover:border-[#637F26]">
                </div>
                <button type="submit"
                    class="w-full py-3 px-4 rounded-xl text-white bg-[#637F26] hover:bg-[#85A832] font-semibold transition-all duration-200 shadow-sm hover:shadow-md">
                    Reset Kata Sandi
                </button>
            </form>
            <div class="mt-6 text-center">
                <a href="{{ route('login') }}" class="text-[#637F26] hover:text-[#85A832] text-sm font-medium">Kembali ke
                    Login</a>
            </div>
        </div>
    </div>
@endsection
