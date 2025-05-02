@extends('layouts.admin.add')
@section('the_title', 'Add Departement')
@section('route', route('admin.departements.store'))
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
@endsection
