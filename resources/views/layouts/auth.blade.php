@extends('layouts.app')

@section('base-content')
    <div class="flex" x-data="{ sidebarOpen: true, open: {}, userDropdownOpen: false }">
        <!-- Sidebar -->
        <aside :class="{ 'w-72': sidebarOpen, 'w-20': !sidebarOpen }"
            class="w-72 flex flex-col overflow-y-auto custom-scroll h-screen bg-white border-r border-gray-200 shadow-sm transition-all duration-300">
            <!-- Logo -->
            <div class="flex items-center justify-between p-4 border-b border-gray-200 min-h-[72px]">
                <img src="{{ asset('images/logo.png') }}" alt="Medical Illustration" class="mx-auto drop-shadow-xl">
                <h2 class="text-xl font-bold text-[#637F26]" :class="{ 'hidden': !sidebarOpen }">Sistem Magang RS</h2>
            </div>

            <!-- Navigation Menu -->
            <nav class="flex-1 p-4 space-y-2">
                @if (Auth::check())
                    @foreach ($menus as $menu)
                        <a href="{{ url($menu->url) }}" 
                           :class="{ 'justify-center': !sidebarOpen }"
                           class="flex items-center p-3 transition-colors {{ request()->is(ltrim($menu->url, '/')) ? 'bg-[#F5F7F0] text-[#637F26]' : 'text-gray-700 hover:bg-[#F5F7F0] hover:text-[#637F26]' }}">
                            <i class="{{ $menu->icon }} mr-3 w-5 {{ request()->is(ltrim($menu->url, '/')) ? 'text-[#637F26]' : 'text-gray-500' }}"
                               :class="{ 'mr-0': !sidebarOpen }"></i>
                            <span :class="{ 'hidden': !sidebarOpen }">{{ $menu->name }}</span>
                        </a>
                    @endforeach
                @endif
            </nav>
        </aside>

        <!-- Main Content with Navbar -->
        <div class="flex-1 min-h-screen transition-all duration-300">
            <!-- Top Navbar -->
            <nav class="left-72 bg-white border-b border-gray-200 fixed right-0 z-10 transition-all duration-300"
                :class="{ 'left-72': sidebarOpen, 'left-20': !sidebarOpen }">
                <div class="px-4 py-3">
                    <div class="flex items-center justify-between">
                        <!-- Toggle Sidebar Button -->
                        <button @click="sidebarOpen = !sidebarOpen" class="p-1 rounded-lg hover:bg-gray-100">
                            <i class="bi bi-list text-2xl text-[#637F26]"></i>
                        </button>

                        <!-- Right Side Nav Items -->
                        <div class="flex items-center space-x-3">
                            <!-- Notifications moved closer to profile -->
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('student.notifications') }}" class="p-2 hover:bg-gray-100 rounded-full">
                                    <i class="bi bi-bell text-xl text-gray-600 hover:text-[#637F26]"></i>
                                </a>
                                
                                <!-- User Profile Dropdown -->
                                <div class="relative" x-data="{ open: false }">
                                    <button @click="open = !open" class="flex items-center space-x-3 p-2 rounded-lg hover:bg-gray-100">
                                        <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}" alt="Profile" class="w-8 h-8 rounded-full">
                                        <span class="text-sm font-medium text-gray-700">{{ Auth::user()->name }}</span>
                                    </button>
                                    <!-- ...existing dropdown code... -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <div class="pt-[73px]">
                @yield('content')
            </div>
        </div>

        <!-- Tooltip container -->
        <div x-data x-show="$store.tooltip.visible" x-cloak x-text="$store.tooltip.text"
            x-bind:style="{
                top: $store.tooltip.y + 'px',
                left: $store.tooltip.x + 'px'
            }"
            :class="{ 'hidden': sidebarOpen }"
            class="fixed z-50 px-2 py-1 text-xs text-white bg-black rounded pointer-events-none transition-all duration-200"
            style="transform: translateY(-50%);">
        </div>
    </div>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('tooltip', {
                text: '',
                x: 0,
                y: 0,
                visible: false,
                show(text, el) {
                    const rect = el.getBoundingClientRect();
                    this.text = text;
                    this.x = rect.right + 12;
                    this.y = rect.top + rect.height / 2 + window.scrollY;
                    this.visible = true;
                },
                hide() {
                    this.visible = false;
                }
            });
        });
    </script>
@endsection
