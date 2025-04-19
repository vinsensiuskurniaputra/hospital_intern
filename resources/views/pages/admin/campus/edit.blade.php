@extends('layouts.admin.edit')

@section('title', 'Edit Campus')
@section('the_title', 'Edit Campus')
@section('route_back', route('admin.campuses.index'))
@section('route', route('admin.campuses.update', $campus->id))
@section('is_has_photo', 'false')

@section('input_contents')

    @include('components.general.input_field', [
        'label' => 'Name',
        'name' => 'name',
        'type' => 'text',
        'value' => old('name', $campus->name),
        'required' => true,
        'placeholder' => 'Enter name...',
    ])

    @include('components.general.input_field', [
        'label' => 'Detail',
        'name' => 'detail',
        'type' => 'detail',
        'value' => old('detail', $campus->detail),
        'required' => true,
        'placeholder' => 'Enter detail...',
    ])

@endsection
