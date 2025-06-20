<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Magang Rumah Sakit - Sistem Magang Terintegrasi Rumah Sakit</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="fixed w-full bg-white/80 backdrop-blur-sm border-b border-gray-100 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <span class="text-2xl font-semibold text-[#2D5A27]">Magang<span
                            class="text-[#4F9546]">RumahSakit</span></span>
                </div>
                <div class="flex items-center">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/home') }}"
                                class="bg-[#2D5A27] text-white px-6 py-2 rounded-lg hover:bg-[#4F9546] transition-colors">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}"
                                class="bg-[#2D5A27] text-white px-6 py-2 rounded-lg hover:bg-[#4F9546] transition-colors">Login</a>
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="relative bg-gradient-to-br from-[#4F9546]/10 to-[#2D5A27]/10 pt-32 pb-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl font-bold text-gray-900 sm:text-5xl md:text-6xl">
                    <span class="block">Sistem Magang</span>
                    <span class="block text-[#2D5A27]">Terintegrasi Rumah Sakit</span>
                </h1>
                <p class="mt-3 max-w-md mx-auto text-base text-gray-500 sm:text-lg md:mt-5 md:text-xl md:max-w-3xl">
                    Platform terpadu untuk mengelola program magang mahasiswa di rumah sakit dengan efisien dan
                    terstruktur.
                </p>
            </div>
        </div>
    </div>

    <!-- Role Cards -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-16">
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <!-- Mahasiswa Card -->
            <div class="bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition-shadow border border-gray-100">
                <div class="w-12 h-12 bg-[#4F9546]/10 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-[#2D5A27]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                        </path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900">Mahasiswa</h3>
                <p class="mt-2 text-gray-500">Akses informasi program magang, pengajuan, dan monitoring progress magang
                    Anda.</p>
            </div>

            <!-- PIC RS Card -->
            <div class="bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition-shadow border border-gray-100">
                <div class="w-12 h-12 bg-[#4F9546]/10 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-[#2D5A27]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                        </path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900">PIC Rumah Sakit</h3>
                <p class="mt-2 text-gray-500">Kelola dan monitor mahasiswa magang, evaluasi kinerja, dan koordinasi
                    program.</p>
            </div>

            <!-- Admin Card -->
            <div class="bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition-shadow border border-gray-100">
                <div class="w-12 h-12 bg-[#4F9546]/10 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-[#2D5A27]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4">
                        </path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900">Admin</h3>
                <p class="mt-2 text-gray-500">Manajemen sistem, pengaturan program, dan koordinasi antar institusi.</p>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="mt-20 bg-white border-t border-gray-100">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <p class="text-center text-gray-500 text-sm">
                &copy; {{ date('Y') }} Magang Rumah Sakit. All rights reserved.
            </p>
        </div>
    </footer>
</body>

</html>
