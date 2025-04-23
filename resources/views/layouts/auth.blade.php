@extends('layouts.app')

@section('base-content')
    <div class="flex" x-data="{ sidebarOpen: true, open: {}, userDropdownOpen: false }">
        <!-- Sidebar -->
        <aside :class="{ 'w-72': sidebarOpen, 'w-20': !sidebarOpen }"
            class="w-72 flex flex-col h-screen bg-white border-r border-gray-200 shadow-sm transition-all duration-300">
            <div class="flex items-center p-4 border-b border-gray-200 min-h-[72px]">
                <img src="{{ asset('images/logo.png') }}" alt="Medical Illustration" class="drop-shadow-xl">
                <h2 class="text-lg font-bold text-[#637F26] ml-2" :class="{ 'hidden': !sidebarOpen }">Sistem Magang RS
                </h2>
            </div>

            <nav class="p-4 space-y-2 overflow-y-auto overflow-x-hidden custom-scroll">
                @if (Auth::check())
                    @foreach ($menus as $menu)
                        @if (isset($menu->children) && count($menu->children) > 0)
                            <h1 class="border-t border-gray-200" :class="{ 'hidden': sidebarOpen }"></h1>
                            <h1 class="text-xs text-gray-500 py-2 " :class="{ 'hidden': !sidebarOpen }">{{ $menu->name }}
                            </h1>
                            @foreach ($menu->children as $child)
                                <a href="{{ url($child->url) }}" id="menu-{{ $child->id }}"
                                    @mouseenter="Alpine.store('tooltip').show('{{ $child->name }}', document.getElementById('menu-{{ $child->id }}'))"
                                    @mouseleave="Alpine.store('tooltip').hide()" :class="{ 'justify-center': !sidebarOpen }"
                                    class="group relative flex items-center p-3 transition-colors
{{ request()->path() == ltrim($child->url, '/')
    ? 'text-[#637F26] border-l-2 border-[#637F26] bg-gradient-to-r from-[#F5F7F0] to-transparent font-medium'
    : 'text-gray-700 hover:bg-[#F5F7F0] hover:text-[#637F26]' }}">

                                    <i class="{{ $child->icon }} w-5 {{ request()->path() == ltrim($child->url, '/') ? 'text-[#637F26]' : 'text-gray-500' }}"
                                        :class="{ 'mr-0': !sidebarOpen }"></i>

                                    <span :class="{ 'hidden': !sidebarOpen }"
                                        class="text-sm ml-3">{{ $child->name }}</span>
                                </a>
                            @endforeach
                        @elseif ($menu->parent_id == null)
                            <!-- Single menu item -->
                            <a href="{{ url($menu->url) }}" :class="{ 'justify-center': !sidebarOpen }"
                                id="menu-{{ $menu->id }}"
                                @mouseenter="Alpine.store('tooltip').show('{{ $menu->name }}', document.getElementById('menu-{{ $menu->id }}'))"
                                @mouseleave="Alpine.store('tooltip').hide()" :class="{ 'justify-center': !sidebarOpen }"
                                class="flex items-center p-3 transition-colors
                                {{ request()->path() == ltrim($menu->url, '/')
                                    ? 'text-[#637F26] border-l-2 border-[#637F26] bg-gradient-to-r from-[#F5F7F0] to-transparent font-medium'
                                    : 'text-gray-700 hover:bg-[#F5F7F0] hover:text-[#637F26]' }}">
                                <i class="{{ $menu->icon }} w-5 {{ request()->path() == ltrim($menu->url, '/') ? 'text-[#637F26]' : 'text-gray-500' }}"
                                    :class="{ 'mr-0': !sidebarOpen }"></i>
                                <span :class="{ 'hidden': !sidebarOpen }" class="text-sm ml-3 ">{{ $menu->name }}</span>
                            </a>
                        @endif
                    @endforeach
                @endif
            </nav>

            <div class="mt-auto p-4 border-t border-gray-200">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" :class="{ 'justify-center': !sidebarOpen }"
                        class="flex items-center w-full p-3 rounded-lg text-gray-700 hover:bg-red-50 hover:text-red-600 transition-colors">
                        <i class="bi bi-box-arrow-right w-5" :class="{ 'mr-3': sidebarOpen }"></i>
                        <span :class="{ 'hidden': !sidebarOpen }">Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content with Navbar -->
        <div class="flex-1 min-h-screen transition-all duration-300">

            <!-- Top Navbar -->
            <nav class="left-72 bg-white border-b border-gray-200 fixed right-0 z-10 transition-all duration-300"
                :class="{ 'left-72': sidebarOpen, 'left-20': !sidebarOpen }">
                <div class="px-4 py-3">
                    <div class="flex items-center justify-between">
                        {{-- Button Open And Close Bar --}}
                        <button @click="sidebarOpen = !sidebarOpen" class="p-1 rounded-lg hover:bg-gray-100">
                            <i class="bi bi-list text-2xl text-[#637F26]"></i>
                        </button>
                        <!-- Search Bar -->
                        <div class="flex-1 max-w-lg">
                            <div class="relative">
                                <input type="text"
                                    class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-200 focus:border-[#637F26] focus:ring-2 focus:ring-[#637F26] text-sm"
                                    placeholder="Search...">
                                <div class="absolute left-3 top-2.5 text-gray-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Right Side Nav Items -->
                        <div class="flex items-center space-x-4">
                            <!-- Notifications -->
                            <button class="p-2 text-gray-500 hover:text-[#637F26] rounded-lg hover:bg-gray-100">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                            </button>

                            <!-- User Profile Dropdown -->
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open"
                                    class="flex items-center space-x-3 p-2 rounded-lg hover:bg-gray-100">
                                    <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}" alt="Profile"
                                        class="w-8 h-8 rounded-full">
                                    <span class="text-sm font-medium text-gray-700">{{ Auth::user()->name }}</span>
                                </button>

                                <!-- Dropdown Menu -->
                                <div x-show="open" @click.away="open = false"
                                    x-transition:enter="transition ease-out duration-100"
                                    x-transition:enter-start="opacity-0 scale-95"
                                    x-transition:enter-end="opacity-100 scale-100"
                                    class="absolute right-0 mt-2 w-48 py-2 bg-white rounded-lg shadow-lg border border-gray-200"
                                    style="display: none;">
                                    <a href="#"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                                    <a href="#"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Settings</a>
                                    <hr class="my-2 border-gray-200">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit"
                                            class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                            Sign out
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <div class="pt-[73px] bg-gray-50 h-screen overflow-y-scroll "
                :style="{ width: sidebarOpen ? 'calc(100vw - 18rem)' : 'calc(100vw - 5rem)' }">
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
