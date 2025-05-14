@extends('layouts.admin.add')
@section('the_title', 'Add Stase')
@section('route', route('admin.stases.store'))
@section('is_has_photo', 'false')
@section('input_contents')
    @include('components.general.input_field', [
        'label' => 'Name',
        'name' => 'name',
        'type' => 'text',
        'value' => old('name'),
        'required' => true,
        'placeholder' => 'Enter name...',
    ])
    @include('components.general.input_field', [
        'label' => 'Detail',
        'name' => 'detail',
        'type' => 'text',
        'value' => old('detail'),
        'required' => true,
        'placeholder' => 'Enter detail...',
    ])
    <label class="block text-sm font-medium text-gray-700 mb-1">PIC</label>
    <div class="space-y-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
        @foreach ($responsibles as $responsible)
            <div class="space-y-2">
                <!-- Parent Menu -->
                <label class="flex items-center p-2 hover:bg-white rounded transition-colors">
                    <input type="checkbox" name="responsibleUsers[]" value="{{ $responsible->responsibleUser->id }}"
                        class="rounded border-gray-300 text-[#637F26] focus:ring-[#637F26]">
                    <span class="ml-2 text-sm font-medium text-gray-700">{{ $responsible->name }}</span>
                </label>
            </div>
        @endforeach
    </div>
    </div>
    @error('responsibleUsers')
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
    @enderror
    @error('responsible_user_id')
        <span class="text-red-500 text-sm">{{ $message }}</span>
    @enderror
    <label class="block mt-2 text-sm font-medium text-gray-700 mb-1">Departement</label>
    <select name="departement_id" class="w-full px-4 py-2 border border-gray-200 rounded-lg">
        <option value="{{ null }}">Select Departement</option>
        @foreach ($departements as $data)
            <option value="{{ $data->id }}">{{ $data->name }}</option>
        @endforeach
    </select>
    @error('departement_id')
        <span class="text-red-500 text-sm">{{ $message }}</span>
    @enderror
@endsection
