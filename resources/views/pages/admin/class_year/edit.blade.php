@extends('layouts.admin.edit')

@section('title', 'Edit Class Year')
@section('the_title', 'Edit Class Year')
@section('route_back', route('admin.classYears.index'))
@section('route', route('admin.classYears.update', $classYear->id))
@section('is_has_photo', 'false')

@section('input_contents')
    @include('components.general.input_field', [
        'label' => 'Class Year',
        'name' => 'class_year',
        'type' => 'text',
        'value' => old('class_year', $classYear->class_year),
        'required' => true,
        'placeholder' => 'Enter class year...',
    ])
@endsection
