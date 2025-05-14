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
    <label class="block text-sm font-medium text-gray-700 mb-1">Responsible</label>
    <select name="responsible_user_id" class="w-full px-4 py-2 border border-gray-200 rounded-lg">
        <option value="{{ null }}">Select Responsible</option>
        @foreach ($responsibles as $data)
            <option value="{{ $data->responsibleUser->id }}">{{ $data->name }}</option>
        @endforeach
    </select>
    @error('responsible_user_id')
        <span class="text-red-500 text-sm">{{ $message }}</span>
    @enderror
@endsection
