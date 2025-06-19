@extends('layouts.auth')

@section('title', 'Add Schedule')

@section('content')
<div class="p-6 min-h-screen bg-gray-50">
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="mb-6 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('presences.schedules.index') }}" class="text-gray-400 hover:text-gray-600">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <h2 class="text-xl font-medium text-gray-800">Tambah Jadwal</h2>
            </div>
        </div>

        <form action="{{ route('presences.schedules.store') }}" method="POST" id="scheduleForm">
            @csrf
            <div class="space-y-6">
                <!-- Kelas Magang -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Kelas Magang
                    </label>
                    <select name="internship_class_id" required 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                        <option value="">Pilih Kelas Magang</option>
                        @foreach($internshipClasses as $class)
                            <option value="{{ $class->id }}">{{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Stase Container -->
                <div id="staseContainer" class="space-y-4">
                    <div class="stase-entry border rounded-lg p-4">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Stase
                                </label>
                                <select name="stases[0][stase_id]" required 
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                    <option value="">Pilih Stase</option>
                                    @foreach($departments as $dept)
                                        <optgroup label="{{ $dept->name }}">
                                            @foreach($dept->stases as $stase)
                                                <option value="{{ $stase->id }}">{{ $stase->name }}</option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Tanggal Mulai
                                </label>
                                <input type="date" name="stases[0][start_date]" required 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Tanggal Selesai
                                </label>
                                <input type="date" name="stases[0][end_date]" required 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            </div>
                        </div>
                        <button type="button" class="remove-stase mt-2 text-red-600 hover:text-red-800" style="display: none;">
                            Hapus Stase
                        </button>
                    </div>
                </div>

                <!-- Button Container -->
                <div class="flex justify-end gap-4 mt-6">
                    <!-- Add Stase Button -->
                    <button type="button" id="addStase" 
                            class="inline-flex items-center px-4 py-2 bg-[#637F26] hover:bg-[#85A832] text-white rounded-md shadow-sm text-sm font-medium transition-colors">
                        <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Tambah Stase
                    </button>

                    <!-- Submit Button -->
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 bg-[#637F26] hover:bg-[#85A832] text-white rounded-md shadow-sm text-sm font-medium transition-colors">
                        <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Simpan Jadwal
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const staseContainer = document.getElementById('staseContainer');
    const addStaseBtn = document.getElementById('addStase');
    let staseCount = 1;

    addStaseBtn.addEventListener('click', function() {
        const template = staseContainer.children[0].cloneNode(true);
        
        // Update names and IDs
        template.querySelectorAll('[name]').forEach(input => {
            input.name = input.name.replace('[0]', `[${staseCount}]`);
            input.value = '';
        });

        // Show remove button
        const removeBtn = template.querySelector('.remove-stase');
        removeBtn.style.display = 'block';
        removeBtn.addEventListener('click', function() {
            template.remove();
        });

        staseContainer.appendChild(template);
        staseCount++;
    });

    // Form validation
    document.getElementById('scheduleForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Collect all stase entries
        const stases = document.querySelectorAll('.stase-entry');
        const staseIds = new Set();
        let isValid = true;

        stases.forEach(stase => {
            const staseId = stase.querySelector('select').value;
            const startDate = stase.querySelector('input[name*="start_date"]').value;
            const endDate = stase.querySelector('input[name*="end_date"]').value;

            // Check for duplicate stase
            if (staseIds.has(staseId)) {
                alert('Stase tidak boleh duplikat!');
                isValid = false;
                return;
            }
            staseIds.add(staseId);

            // Check date range
            if (new Date(startDate) >= new Date(endDate)) {
                alert('Tanggal mulai harus sebelum tanggal selesai!');
                isValid = false;
                return;
            }
        });

        if (isValid) {
            this.submit();
        }
    });
});
</script>
@endpush
@endsection