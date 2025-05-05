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
    <select name="responsible_user_id" class="w-full px-4 py-2 border border-gray-200 rounded-lg">
        <option value="{{ $stase->responsible_user_id }}">{{ $stase->responsibleUser->user->name }}</option>
        @foreach ($responsibles as $data)
        <option value="{{ $data->responsibleUser->id }}">{{ $data->name }}</option>
        @endforeach
    </select>
    @error('responsible_user_id')
    <span class="text-red-500 text-sm">{{ $message }}</span>
    @enderror
    <label class="block text-sm font-medium text-gray-700 mb-1">Departements</label>
    <select name="departement_id" class="w-full px-4 py-2 border border-gray-200 rounded-lg">
        <option value="{{ $stase->departement_id }}">{{ $stase->departement->name }}</option>
        @foreach ($departements as $data)
            <option value="{{ $data->id }}">{{ $data->name }}</option>
        @endforeach
    </select>
    @error('departement_id')
        <span class="text-red-500 text-sm">{{ $message }}</span>
    @enderror
   
@endsection
