@extends('layouts.auth')

@section('content')
<div class="p-6">
    <!-- Back Link -->
    <div class="flex items-center mb-6">
        <a href="{{ route('responsible.notifications') }}" 
           class="text-gray-600 hover:text-gray-800 flex items-center">
            <i class="bi bi-chevron-left mr-2"></i>
            Kembali
        </a>
    </div>

    <!-- Notification Title -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-xl font-semibold text-gray-800">Notifikasi / Pengumuman Penting</h1>
    </div>

    <!-- Notification Card -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="p-6">
            <!-- Header -->
            <div>
                <h2 class="text-2xl font-semibold text-gray-900 text-center">{{ $notification->title }}</h2>
            </div>

            <!-- Date -->
            <div class="flex justify-center text-sm text-gray-500 mb-6">
                <span>{{ $notification->created_at->format('d F Y - H:i') }}</span>
            </div>

            <!-- Content -->
            <div class="prose max-w-none">
                <div class="text-gray-700 leading-relaxed space-y-4">
                    {!! nl2br(e($notification->message)) !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection