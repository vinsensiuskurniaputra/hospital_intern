@extends('layouts.auth')

@section('content')
<div class="p-6">
    <div class="bg-white rounded-lg shadow p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-4">Notifikasi</h1>
        
        <div class="space-y-4">
            @for($i = 0; $i < 5; $i++)
            <div class="border-l-4 border-[#637F26] pl-4 py-2 @if($i < 2) bg-green-50 @endif hover:bg-gray-50 transition">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="font-medium mb-1">Pergantian Jadwal Praktikum</h3>
                        <p class="text-sm text-gray-600">
                            Jadwal praktikum pada tanggal 15 Mei 2025 diundur menjadi tanggal 20 Mei 2025 
                            di Departemen Kesehatan Ruang 301.
                        </p>
                    </div>
                    <div class="text-xs text-gray-500">{{ now()->subDays($i)->format('d M Y - H:i') }}</div>
                </div>
                @if($i < 2)
                <div class="mt-2 flex justify-end">
                    <button class="text-xs text-green-700 hover:text-green-900">Tandai sudah dibaca</button>
                </div>
                @endif
            </div>
            @endfor
            
            <!-- Empty State -->
            @if(false)
            <div class="py-10 text-center">
                <div class="inline-block p-4 rounded-full bg-gray-100 mb-4">
                    <i class="bi bi-bell-slash text-3xl text-gray-400"></i>
                </div>
                <h3 class="font-medium text-gray-700">Belum ada notifikasi</h3>
                <p class="text-sm text-gray-500 mt-1">Anda akan menerima notifikasi penting di sini</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection