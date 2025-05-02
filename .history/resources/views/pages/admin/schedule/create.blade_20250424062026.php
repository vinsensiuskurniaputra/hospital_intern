@extends('layouts.auth')

@section('title', 'Tambah Jadwal')

@section('content')
<div class="p-6 min-h-screen bg-gray-50">
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <div class="mb-6 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.schedules.index') }}" class="text-gray-400 hover:text-gray-600">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <h2 class="text-xl font-medium text-gray-800">Tambah Jadwal</h2>
            </div>
        </div>

        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded">
                <div class="flex items-center mb-2">
                    <svg class="h-5 w-5 text-red-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="text-sm font-medium text-red-800">Ada beberapa kesalahan dalam pengisian form:</h3>
                </div>
                <ul class="list-disc list-inside text-sm text-red-700">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.schedules.store') }}" method="POST" class="max-w-4xl mx-auto">
            @csrf

            <div class="grid grid-cols-2 gap-6">
                <div>
                    <!-- Kelas Magang -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kelas Magang</label>
                        <select name="internship_class_id" class="w-full border border-gray-300 rounded-md px-3 py-2.5 focus:ring-1 focus:ring-green-500 focus:border-green-500">
                            <option value="">Pilih Kelas Magang</option>
                            @foreach($internshipClasses as $class)
                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Stase -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Stase</label>
                        <select name="stase_id" id="stase_id" class="w-full border border-gray-300 rounded-md px-3 py-2.5 focus:ring-1 focus:ring-green-500 focus:border-green-500">
                            <option value="">Pilih Stase</option>
                            @foreach($stases as $stase)
                                <option value="{{ $stase->id }}">{{ $stase->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Departemen -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Departemen</label>
                        <select name="departement_id" class="w-full border border-gray-300 rounded-md px-3 py-2.5 focus:ring-1 focus:ring-green-500 focus:border-green-500">
                            <option value="">Pilih Departemen</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <!-- Pembimbing (readonly, will be populated based on stase) -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pembimbing Magang</label>
                        <input type="text" id="pembimbing" class="w-full border border-gray-300 rounded-md px-3 py-2.5 bg-gray-50" readonly>
                        <p class="mt-1 text-sm text-gray-500">Pembimbing ditentukan berdasarkan Stase yang dipilih</p>
                    </div>

                    <!-- Additional Info -->
                    <div class="mb-6 p-4 bg-blue-50 rounded-md">
                        <h4 class="text-sm font-medium text-blue-800 mb-2">Informasi Penting</h4>
                        <ul class="text-sm text-blue-700 list-disc list-inside space-y-1">
                            <li>Pastikan periode rotasi tidak bertabrakan dengan jadwal lain</li>
                            <li>Jam praktik disesuaikan dengan ketentuan departemen</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Periode Rotasi -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Periode Rotasi</label>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Tanggal Mulai</label>
                        <input type="date" name="start_date" class="w-full border border-gray-300 rounded-md px-3 py-2.5 focus:ring-1 focus:ring-green-500 focus:border-green-500">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Tanggal Selesai</label>
                        <input type="date" name="end_date" class="w-full border border-gray-300 rounded-md px-3 py-2.5 focus:ring-1 focus:ring-green-500 focus:border-green-500">
                    </div>
                </div>
            </div>

            <!-- Jam -->
            <div class="mb-8">
                <label class="block text-sm font-medium text-gray-700 mb-2">Jam Praktik</label>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Jam Mulai</label>
                        <input type="time" name="start_time" class="w-full border border-gray-300 rounded-md px-3 py-2.5 focus:ring-1 focus:ring-green-500 focus:border-green-500">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Jam Selesai</label>
                        <input type="time" name="end_time" class="w-full border border-gray-300 rounded-md px-3 py-2.5 focus:ring-1 focus:ring-green-500 focus:border-green-500">
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end gap-3 border-t pt-6">
                <a href="{{ route('admin.schedules.index') }}" 
                    class="px-6 py-2.5 text-sm font-medium text-red-600 border border-red-600 rounded-md hover:bg-red-50 transition-colors duration-200">
                    Cancel
                </a>
                <button type="submit" 
                    class="px-6 py-2.5 text-sm font-medium text-white bg-green-600 rounded-md hover:bg-green-700 transition-colors duration-200">
                    Simpan Jadwal
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('stase_id').addEventListener('change', function() {
    const staseId = this.value;
    if (staseId) {
        // Fetch pembimbing data based on stase
        fetch(`/admin/stases/${staseId}/responsible`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('pembimbing').value = data.name;
            });
    } else {
        document.getElementById('pembimbing').value = '';
    }
});
</script>
@endsection