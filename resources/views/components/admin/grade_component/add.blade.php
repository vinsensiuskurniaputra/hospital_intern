@extends('layouts.admin.add')
@section('the_title', 'Add Grade Component')
@section('route', route('admin.gradeComponents.store'))
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
    <label class="block text-sm font-medium text-gray-700 mb-1">Stase</label>
    <select name="stase_id" class="w-full px-4 py-2 border border-gray-200 rounded-lg">
        <option value="">Select Stase</option>
        @foreach ($stases as $stase)
            <option value="{{ $stase->id }}">{{ $stase->name }}</option>
        @endforeach
    </select>
    @error('stase_id')
        <span class="text-red-500 text-sm">{{ $message }}</span>
    @enderror
@endsection
