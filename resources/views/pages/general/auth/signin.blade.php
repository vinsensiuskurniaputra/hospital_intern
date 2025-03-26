@extends('layouts.app')

@section('title', 'Sign-in')

@section('base-content')
    <div class="min-h-screen flex bg-gradient-to-br from-[#F5F7F0] to-[#E8EDE0]">
        <!-- Left side - Illustration -->
        <div class="hidden lg:flex lg:w-1/2 items-center justify-center p-8">
            <div class="max-w-md text-center">
                <img src="{{ asset('images/login.png') }}" alt="Medical Illustration"
                    class="mx-auto w-[24rem] drop-shadow-xl transform transition-all duration-500 hover:scale-105 hover:rotate-1">
                <h2
                    class="mt-8 text-3xl font-bold bg-gradient-to-r from-[#637F26] to-[#85A832] bg-clip-text text-transparent">
                    Hospital Intern</h2>
                <p class="mt-3 text-gray-600 text-lg">Managing medical internships made simple</p>
            </div>
        </div>

        <!-- Right side - Login Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-6">
            <div
                class="w-full max-w-md space-y-8 bg-white/80 backdrop-blur-sm p-10 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.08)]">
                <div>
                    <h1 class="text-3xl font-bold text-center text-gray-800">Welcome Back</h1>
                    <p class="mt-3 text-center text-gray-600">Sign in to continue your journey</p>
                </div>

                <form action="{{ route('login') }}" method="POST" class="mt-10 space-y-6">
                    @csrf
                    <div class="space-y-6">
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700" for="username">Username</label>
                            <input type="text" id="username" name="username" value="{{ old('username') }}" required
                                class="mt-1 block w-full px-4 py-3 border border-gray-200 rounded-xl shadow-sm 
                                focus:ring-2 focus:ring-[#637F26] focus:border-[#637F26] transition-all duration-200
                                hover:border-[#637F26]"
                                placeholder="Enter your username">
                            @error('username')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700" for="password">Password</label>
                            <input type="password" id="password" name="password" required
                                class="mt-1 block w-full px-4 py-3 border border-gray-200 rounded-xl shadow-sm 
                                focus:ring-2 focus:ring-[#637F26] focus:border-[#637F26] transition-all duration-200
                                hover:border-[#637F26]"
                                placeholder="Enter your password">
                            @error('password')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-between pt-2">
                            <div class="flex items-center">
                                <input type="checkbox" id="remember" name="remember"
                                    class="h-4 w-4 rounded border-gray-300 text-[#637F26] focus:ring-[#637F26]">
                                <label for="remember" class="ml-2 block text-sm text-gray-700">Remember me</label>
                            </div>
                            <a href="#" class="text-sm text-[#637F26] hover:text-[#85A832] font-medium">Forgot
                                password?</a>
                        </div>

                        <button type="submit"
                            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl text-sm font-semibold
                            text-white bg-[#637F26] hover:bg-[#85A832] focus:outline-none focus:ring-2 focus:ring-offset-2 
                            focus:ring-[#637F26] transition-all duration-200 shadow-sm hover:shadow-md mt-4">
                            Sign in
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
