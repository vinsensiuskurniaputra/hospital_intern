@extends('layouts.auth')

@section('title', 'Notifications')

@section('content')
    <div class="p-6 space-y-6">
        <!-- Header -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-800">Notifications</h1>
                    <p class="mt-1 text-sm text-gray-500">Stay updated with your latest activities</p>
                </div>
            </div>
        </div>

        <!-- Notifications Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            @forelse ($notifications as $notification)
                <div
                    class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden {{ !$notification->is_read ? 'bg-[#F5F7F0]' : '' }}">
                    <div class="p-6">
                        <div class="flex items-start gap-4">
                            <!-- Icon -->
                            <div class="p-3 rounded-lg {{ $notification->icon }}">
                                <i class="bi {{ $notification->icon }}"></i>
                            </div>

                            <!-- Content -->
                            <div class="flex-1">
                                <div class="flex items-start justify-between">
                                    <h3 class="font-medium text-gray-900">{{ $notification->title }}</h3>
                                    <span
                                        class="text-xs text-gray-500">{{ $notification->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="mt-1 text-sm text-gray-600">{{ $notification->message }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-gray-500">No notifications available.</p>
            @endforelse
        </div>
    </div>
@endsection
