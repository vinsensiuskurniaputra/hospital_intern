@extends('layouts.admin.edit')

@section('title', 'Edit Internship Class')
@section('the_title', 'Edit Internship Class')
@section('route_back', route('admin.internshipClasses.index'))
@section('route', route('admin.internshipClasses.update', $internshipClass->id))
@section('is_has_photo', 'false')

@section('input_contents')
    @include('components.general.input_field', [
        'label' => 'Name',
        'name' => 'name',
        'type' => 'text',
        'value' => old('name', $internshipClass->name),
        'required' => true,
        'placeholder' => 'Enter name...',
    ])
    @include('components.general.input_field', [
        'label' => 'Description',
        'name' => 'description',
        'type' => 'text',
        'value' => old('description', $internshipClass->description),
        'required' => true,
        'placeholder' => 'Enter description...',
    ])
    <label class="block text-sm font-medium text-gray-700 mb-1">Class Year</label>
    <select name="class_year_id" class="w-full px-4 py-2 border border-gray-200 rounded-lg">
        <option value="{{ $internshipClass->classYear->id }}">{{ $internshipClass->classYear->class_year }}</option>
        @foreach ($classYears as $data)
            <option value="{{ $data->id }}">{{ $data->class_year }}</option>
        @endforeach
    </select>
    @error('class_year_id')
        <span class="text-red-500 text-sm">{{ $message }}</span>
    @enderror
    <label class="block text-sm font-medium text-gray-700 mb-1">Campus</label>
    <select name="campus_id" class="w-full px-4 py-2 border border-gray-200 rounded-lg">
        <option value="{{ $internshipClass->campus->id }}">{{ $internshipClass->campus->name }}</option>
        @foreach ($campuses as $data)
            <option value="{{ $data->id }}">{{ $data->name }}</option>
        @endforeach
    </select>
    @error('campus_id')
        <span class="text-red-500 text-sm">{{ $message }}</span>
    @enderror

@endsection
