@extends('layouts.admin.edit')

@section('title', 'Edit Departement')
@section('the_title', 'Edit Departement')
@section('route_back', route('admin.departements.index'))
@section('route', route('admin.departements.update', $departement->id))
@section('is_has_photo', 'false')

@section('input_contents')
    @include('components.general.input_field', [
        'label' => 'Name',
        'name' => 'name',
        'type' => 'text',
        'value' => old('name', $departement->name),
        'required' => true,
        'placeholder' => 'Enter name...',
    ])
    @include('components.general.input_field', [
        'label' => 'Description',
        'name' => 'description',
        'type' => 'text',
        'value' => old('description', $departement->description),
        'required' => true,
        'placeholder' => 'Enter description...',
    ])
@endsection
