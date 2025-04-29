@extends('layouts.auth')

@section('content')
<div class="p-6">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('student.notifications.index') }}" 
           class="inline-flex items-center text-gray-600 hover:text-gray-800">
            <i class="bi bi-arrow-left mr-2"></i>
            Kembali ke Daftar Notifikasi
        </a>
    </div>

    <!-- Notification Detail Card -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <!-- Header -->
        <div class="p-6 border-b border-gray-200">
            <div class="flex justify-between items-start">
                <div class="flex-1">
                    <h1 class="text-xl font-semibold text-gray-800">{{ $notification['title'] }}</h1>
                    <p class="text-sm text-gray-500 mt-2">{{ $notification['date'] }}</p>
                </div>
                <span class="px-3 py-1 text-sm font-medium rounded-full whitespace-nowrap ml-4
                    @if($notification['type'] === 'Umum') bg-emerald-100 text-emerald-800
                    @elseif($notification['type'] === 'Jadwal') bg-amber-100 text-amber-800
                    @elseif($notification['type'] === 'Evaluasi') bg-green-100 text-green-800
                    @elseif($notification['type'] === 'Kebijakan') bg-red-100 text-red-800
                    @else bg-slate-100 text-slate-800 @endif">
                    {{ $notification['type'] }}
                </span>
            </div>
        </div>

        <!-- Content -->
        <div class="p-6">
            <div class="prose max-w-none text-gray-600">
                {!! nl2br(e($notification['content'])) !!}
            </div>

            @if(isset($notification['action']))
            <div class="mt-8">
                <a href="{{ $notification['action']['url'] }}" 
                   class="inline-flex items-center px-6 py-2.5 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors">
                    <i class="bi bi-download mr-2"></i>
                    {{ $notification['action']['text'] }}
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection