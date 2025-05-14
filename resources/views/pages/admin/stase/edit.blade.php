@extends('layouts.admin.edit')

@section('title', 'Edit Stase')
@section('the_title', 'Edit Stase')
@section('route_back', route('admin.stases.index'))
@section('route', route('admin.stases.update', $stase->id))
@section('is_has_photo', 'false')

@section('input_contents')
    @include('components.general.input_field', [
        'label' => 'Name',
        'name' => 'name',
        'type' => 'text',
        'value' => old('name', $stase->name),
        'required' => true,
        'placeholder' => 'Enter name...',
    ])
    @include('components.general.input_field', [
        'label' => 'Detail',
        'name' => 'detail',
        'type' => 'text',
        'value' => old('detail', $stase->detail),
        'required' => true,
        'placeholder' => 'Enter detail...',
    ])

    <label class="block text-sm font-medium text-gray-700 mb-1">PIC</label>
    <div class="space-y-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
        @foreach ($responsibles as $responsible)
            <div class="space-y-2">
                <label class="flex items-center p-2 hover:bg-white rounded transition-colors">
                    <input type="checkbox" name="responsibleUsers[]" value="{{ $responsible->responsibleUser->id }}"
                        class="rounded border-gray-300 text-[#637F26] focus:ring-[#637F26]"
                        {{ $stase->responsibleUsers->contains($responsible->responsibleUser->id) ? 'checked' : '' }}>
                    <span class="ml-2 text-sm font-medium text-gray-700">{{ $responsible->name }}</span>
                </label>
            </div>
        @endforeach
    </div>
    @error('responsibleUsers')
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
    @enderror

    <label class="block text-sm font-medium text-gray-700 mb-1">Departement</label>
    <select name="departement_id" class="w-full px-4 py-2 border border-gray-200 rounded-lg">
        <option value="">Select Departement</option>
        @foreach ($departements as $departement)
            <option value="{{ $departement->id }}" {{ $stase->departement_id == $departement->id ? 'selected' : '' }}>
                {{ $departement->name }}
            </option>
        @endforeach
    </select>
    @error('departement_id')
        <span class="text-red-500 text-sm">{{ $message }}</span>
    @enderror
@endsection
