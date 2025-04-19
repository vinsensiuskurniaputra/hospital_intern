@extends('layouts.admin.edit')

@section('title', 'Edit Responsible')
@section('the_title', 'Edit Responsible')
@section('route_back', route('admin.responsibles.index'))
@section('route', route('admin.responsibles.update', $responsible->id))
@section('is_has_photo', 'true')
@section('photo_profile_url', $responsible->user->photo_profile_url)

@section('input_contents')
    @include('components.general.input_field', [
        'label' => 'Username',
        'name' => 'username',
        'type' => 'text',
        'value' => old('username', $responsible->user->username),
        'required' => true,
        'placeholder' => 'Enter username...',
    ])

    @include('components.general.input_field', [
        'label' => 'Name',
        'name' => 'name',
        'type' => 'text',
        'value' => old('name', $responsible->user->name),
        'required' => true,
        'placeholder' => 'Enter name...',
    ])

    @include('components.general.input_field', [
        'label' => 'Email',
        'name' => 'email',
        'type' => 'email',
        'value' => old('email', $responsible->user->email),
        'required' => true,
        'placeholder' => 'Enter email...',
    ])

    @include('components.general.input_field', [
        'label' => 'Telphone',
        'name' => 'telp',
        'type' => 'telp',
        'value' => old('telp', $responsible->telp),
        'required' => true,
        'placeholder' => 'Enter telp...',
    ])
@endsection
