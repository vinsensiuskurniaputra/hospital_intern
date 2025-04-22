@extends('layouts.auth')

@section('content')
<div class="p-6">
    <div class="bg-white rounded-lg shadow p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-4">Dashboard Penanggung Jawab</h1>
        <p class="text-gray-600">Selamat datang di panel penanggung jawab rumah sakit.</p>
        
        <!-- Dashboard Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
            <!-- Total Students -->
            <div class="bg-blue-50 p-4 rounded-lg border border-blue-100">
                <h3 class="text-lg font-medium text-blue-800">Total Mahasiswa</h3>
                <p class="text-3xl font-bold text-blue-600">24</p>
            </div>
            
            <!-- Attendance Today -->
            <div class="bg-green-50 p-4 rounded-lg border border-green-100">
                <h3 class="text-lg font-medium text-green-800">Kehadiran Hari Ini</h3>
                <p class="text-3xl font-bold text-green-600">18</p>
            </div>
            
            <!-- Pending Tasks -->
            <div class="bg-amber-50 p-4 rounded-lg border border-amber-100">
                <h3 class="text-lg font-medium text-amber-800">Penilaian Tertunda</h3>
                <p class="text-3xl font-bold text-amber-600">5</p>
            </div>
        </div>
        
        <!-- Recent Activities -->
        <div class="mt-8">
            <h2 class="text-xl font-semibold mb-4">Aktivitas Terbaru</h2>
            <div class="space-y-4">
                @for($i = 0; $i < 5; $i++)
                <div class="border-l-4 border-[#637F26] pl-4 py-2">
                    <p class="text-gray-800 font-medium">Mahasiswa baru terdaftar</p>
                    <p class="text-sm text-gray-500">2 jam yang lalu</p>
                </div>
                @endfor
            </div>
        </div>
    </div>
</div>
@endsection