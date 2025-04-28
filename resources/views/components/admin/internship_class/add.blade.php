@extends('layouts.admin.add')
@section('the_title', 'Add Internship Class')
@section('route', route('admin.internshipClasses.store'))
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
        'label' => 'Description',
        'name' => 'description',
        'type' => 'text',
        'value' => old('description'),
        'required' => true,
        'placeholder' => 'Enter description...',
    ])
    <label class="block text-sm font-medium text-gray-700 mb-1">Class Year</label>
    <select name="class_year_id" class="w-full px-4 py-2 border border-gray-200 rounded-lg">
        <option value="{{ null }}">Select Class Year</option>
        @foreach ($classYears as $data)
            <option value="{{ $data->id }}">{{ $data->class_year }}</option>
        @endforeach
    </select>
    @error('class_year_id')
        <span class="text-red-500 text-sm">{{ $message }}</span>
    @enderror
@endsection
