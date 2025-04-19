@extends('layouts.admin.add')
@section('the_title', 'Add Campus')
@section('route', route('admin.campuses.store'))
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
@endsection
