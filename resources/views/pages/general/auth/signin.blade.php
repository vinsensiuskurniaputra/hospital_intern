@extends('layouts.app')

@section('title', 'Masuk')

@section('base-content')
    <!-- Pesan Kesalahan Tetap -->
    @if ($errors->any())
        <div x-data="{ show: true }" x-show="show"
            class="fixed top-4 left-1/2 transform -translate-x-1/2 z-50 w-[90%] max-w-md"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-2"
            x-transition:enter-end="opacity-100 transform translate-y-0">
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="p-4 flex items-start border-l-4 border-red-500 bg-red-50">
                    <i class="bi bi-exclamation-circle text-red-500 mt-0.5"></i>
                    <div class="ml-3 w-full">
                        <p class="text-sm text-red-800 font-medium">{{ $errors->first() }}</p>
                    </div>
                    <button @click="show = false" class="text-gray-400 hover:text-gray-500">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
            </div>
        </div>
    @endif

    <div class="min-h-screen flex bg-gradient-to-br from-[#F5F7F0] to-[#E8EDE0]">
        <!-- Sisi Kiri - Ilustrasi -->
        <div class="hidden lg:flex lg:w-1/2 items-center justify-center p-8">
            <div class="max-w-md text-center">
                <img src="{{ asset('images/login.png') }}" alt="Ilustrasi Medis"
                    class="mx-auto w-[24rem] drop-shadow-xl transform transition-all duration-500 hover:scale-105 hover:rotate-1">
                <h2
                    class="mt-8 text-3xl font-bold bg-gradient-to-r from-[#637F26] to-[#85A832] bg-clip-text text-transparent">
                    Rumah Sakit Intern</h2>
                <p class="mt-3 text-gray-600 text-lg">Mengelola magang medis menjadi lebih mudah</p>
            </div>
        </div>

        <!-- Sisi Kanan - Formulir Masuk -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-6">
            <div
                class="w-full max-w-md space-y-8 bg-white/80 backdrop-blur-sm p-10 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.08)]">
                <div>
                    <h1 class="text-3xl font-bold text-center text-gray-800">Selamat Datang Kembali</h1>
                    <p class="mt-3 text-center text-gray-600">Masuk untuk melanjutkan perjalanan Anda</p>
                </div>

                <form action="{{ route('login') }}" method="POST" class="mt-10 space-y-6">
                    @csrf
                    <div class="space-y-6">
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700" for="username">Nama Pengguna</label>
                            <input type="text" id="username" name="username" value="{{ old('username') }}" required
                                class="mt-1 block w-full px-4 py-3 border border-gray-200 rounded-xl shadow-sm 
                                focus:ring-2 focus:ring-[#637F26] focus:border-[#637F26] transition-all duration-200
                                hover:border-[#637F26]"
                                placeholder="Masukkan nama pengguna Anda">
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700" for="password">Kata Sandi</label>
                            <input type="password" id="password" name="password" required
                                class="mt-1 block w-full px-4 py-3 border border-gray-200 rounded-xl shadow-sm 
                                focus:ring-2 focus:ring-[#637F26] focus:border-[#637F26] transition-all duration-200
                                hover:border-[#637F26]"
                                placeholder="Masukkan kata sandi Anda">
                        </div>

                        <div class="flex items-center justify-between pt-2">
                            {{-- <div class="flex items-center">
                                <input type="checkbox" id="remember" name="remember"
                                    class="h-4 w-4 rounded border-gray-300 text-[#637F26] focus:ring-[#637F26]">
                                <label for="remember" class="ml-2 block text-sm text-gray-700">Ingat saya</label>
                            </div> --}}
                            <a href="{{ route('password.request') }}"
                                class="text-sm ml-auto text-[#637F26] hover:text-[#85A832] font-medium">Lupa kata sandi?</a>
                        </div>

                        <button type="submit"
                            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl text-sm font-semibold
                            text-white bg-[#637F26] hover:bg-[#85A832] focus:outline-none focus:ring-2 focus:ring-offset-2 
                            focus:ring-[#637F26] transition-all duration-200 shadow-sm hover:shadow-md mt-4">
                            Masuk
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
