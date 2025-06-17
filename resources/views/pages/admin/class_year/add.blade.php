@extends('layouts.admin.add')

@section('the_title', 'Add Class Year')
@section('route', route('admin.classYears.store'))
@section('is_has_photo', 'false')

@section('input_contents')
    @include('components.general.input_field', [
        'label' => 'Class Year',
        'name' => 'class_year',
        'type' => 'text',
        'value' => old('class_year'),
        'required' => true,
        'placeholder' => 'Enter class year...',
    ])
@endsection

