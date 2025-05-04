@extends('layouts.auth')

@section('title', 'Notifikasi')

@section('content')
<div class="p-6">
    <div class="bg-white rounded-lg shadow p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-4">Notifikasi</h1>
        
        <div class="divide-y">
            @forelse($notifications as $notification)
                <div class="py-4 {{ $notification['read_at'] ? 'opacity-70' : 'bg-gray-50' }}">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 mt-1">
                            <span class="inline-block rounded-full p-2 bg-[#F5F7F0] text-[#637F26]">
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                </svg>
                            </span>
                        </div>
                        <div class="ml-4 flex-1">
                            <p class="font-medium text-gray-900">
                                {{ $notification['data']['title'] ?? 'Notifikasi' }}
                            </p>
                            <p class="text-sm text-gray-600 mt-1">
                                {{ $notification['data']['message'] ?? '' }}
                            </p>
                            <p class="text-xs text-gray-500 mt-2">
                                {{ \Carbon\Carbon::parse($notification['created_at'])->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="py-8 text-center">
                    <p class="text-gray-500">Tidak ada notifikasi</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection