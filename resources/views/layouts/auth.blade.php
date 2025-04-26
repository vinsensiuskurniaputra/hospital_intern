@extends('layouts.app')

@section('base-content')
    <div class="flex" x-data="{ sidebarOpen: true, open: {}, userDropdownOpen: false }">
        <!-- Sidebar -->
        <aside :class="{ 'w-72': sidebarOpen, 'w-20': !sidebarOpen }"
            class="w-72 flex flex-col overflow-y-auto custom-scroll h-screen bg-white border-r border-gray-200 shadow-sm transition-all duration-300">
            <div class="flex items-center justify-between p-4 border-b border-gray-200 min-h-[72px]">
                <img src="{{ asset('images/logo.png') }}" alt="Medical Illustration" class="mx-auto drop-shadow-xl">
                <h2 class="text-xl font-bold text-[#637F26]" :class="{ 'hidden': !sidebarOpen }">Sistem Magang RS
                </h2>
            </div>

            <nav class="p-4 space-y-2">
                @if (Auth::check())
                    @foreach ($menus as $menu)
                        @if (isset($menu->children) && count($menu->children) > 0)
                            <!-- Menu with dropdown -->
                            <div class="relative" x-data="{ isOpen: {{ request()->is($menu->url . '*') ? 'true' : 'false' }} }">
                                <button @click="isOpen = !isOpen"
                                    :class="{ 'justify-center': !sidebarOpen, 'justify-start': sidebarOpen }"
                                    class="flex items-center w-full p-3 transition-colors
                                    {{ request()->is(ltrim($menu->url, '/') . '*')
                                        ? 'text-[#637F26] border-l-2 border-[#637F26] bg-gradient-to-r from-[#F5F7F0] to-transparent font-medium'
                                        : 'text-gray-700 hover:bg-[#F5F7F0] hover:text-[#637F26]' }}">
                                    <i class="{{ $menu->icon }} mr-3 w-5 {{ request()->is($menu->url . '*') ? 'text-[#637F26]' : 'text-gray-500' }}"
                                        :class="{ 'mr-0': !sidebarOpen }"></i>
                                    <span :class="{ 'hidden': !sidebarOpen }" class="text-start">{{ $menu->name }}</span>
                                    <svg :class="{ 'hidden': !sidebarOpen, 'rotate-180': isOpen }"
                                        class="w-4 h-4 ml-auto transition-transform duration-200" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <!-- Dropdown items -->
                                <div x-show="isOpen && sidebarOpen" x-transition x-cloak class="pl-8 mt-1 space-y-1">
                                    @foreach ($menu->children as $child)
                                        <a href="{{ url($child->url) }}"
                                            class="block p-3 text-sm transition-colors
                                            {{ request()->is(ltrim($child->url, '/'))
                                                ? 'text-[#637F26] bg-[#F5F7F0] font-medium'
                                                : 'text-gray-600 hover:bg-[#F5F7F0] hover:text-[#637F26]' }}">
                                            <i class="{{ $child->icon }} mr-3 w-5 {{ request()->is(ltrim($child->url, '/')) ? 'text-[#637F26]' : 'text-gray-500' }}"
                                                :class="{ 'mr-0': !sidebarOpen }"></i>
                                            {{ $child->name }}
                                        </a>
                                    @endforeach
                                </div>
                                <!-- Tooltip for collapsed state -->
                                <div x-show="!sidebarOpen && isOpen" x-cloak
                                    class="fixed left-20 z-50 w-48 bg-white rounded-lg shadow-lg border border-gray-200">
                                    @foreach ($menu->children as $child)
                                        <a href="{{ url($child->url) }}"
                                            class="block p-3 text-sm transition-colors
                                            {{ request()->is(ltrim($child->url, '/'))
                                                ? 'text-[#637F26] bg-[#F5F7F0] font-medium'
                                                : 'text-gray-600 hover:bg-[#F5F7F0] hover:text-[#637F26]' }}">
                                            {{ $child->name }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @elseif ($menu->parent_id == null)
                            <!-- Single menu item -->
                            <a href="{{ url($menu->url) }}" :class="{ 'justify-center': !sidebarOpen }"
                                class="flex items-center p-3 transition-colors
                                {{ request()->path() == ltrim($menu->url, '/')
                                    ? 'text-[#637F26] border-l-2 border-[#637F26] bg-gradient-to-r from-[#F5F7F0] to-transparent font-medium'
                                    : 'text-gray-700 hover:bg-[#F5F7F0] hover:text-[#637F26]' }}">
                                <i class="{{ $menu->icon }} mr-3 w-5 {{ request()->path() == ltrim($menu->url, '/') ? 'text-[#637F26]' : 'text-gray-500' }}"
                                    :class="{ 'mr-0': !sidebarOpen }"></i>
                                <span :class="{ 'hidden': !sidebarOpen }">{{ $menu->name }}</span>
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
                            @php
                                $userRole = Auth::user()->roles()->first()->name ?? '';
                                $hideSearchBar = in_array($userRole, ['student', 'responsible']);
                            @endphp
                            
                            @if(!$hideSearchBar)
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
                            @else
                            <!-- Spacer to maintain layout -->
                            <div class="invisible"></div>
                            @endif
                        </div>

                        <!-- Right Side Nav Items -->
                        <div class="flex items-center space-x-4">
                            <!-- Notifications -->
                            <a href="{{ route('student.notifications.index') }}" class="p-2 text-gray-500 hover:text-[#637F26] rounded-lg hover:bg-gray-100 relative">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                                @if(isset($unreadNotifications) && $unreadNotifications > 0)
                                    <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs w-5 h-5 flex items-center justify-center rounded-full">{{ $unreadNotifications }}
                                    </span>
                                @endif
                            </a>
                        </div>

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
    </div>
@endsection
