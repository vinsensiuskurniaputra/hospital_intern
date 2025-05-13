@extends('layouts.admin.add')
@section('the_title', 'Add Responsible')
@section('route', route('admin.responsibles.store'))
@section('is_has_photo', 'true')
@section('input_contents')
    @include('components.general.input_field', [
        'label' => 'Username',
        'name' => 'username',
        'type' => 'text',
        'value' => old('username'),
        'required' => true,
        'placeholder' => 'Enter username...',
    ])

    @include('components.general.input_field', [
        'label' => 'Name',
        'name' => 'name',
        'type' => 'text',
        'value' => old('name'),
        'required' => true,
        'placeholder' => 'Enter name...',
    ])

    @include('components.general.input_field', [
        'label' => 'Email',
        'name' => 'email',
        'type' => 'email',
        'value' => old('email'),
        'required' => true,
        'placeholder' => 'Enter email...',
    ])

    @include('components.general.input_field', [
        'label' => 'Telephone',
        'name' => 'telp',
        'type' => 'telp',
        'value' => old('telp'),
        'required' => true,
        'placeholder' => 'Enter telp...',
    ])
@endsection
