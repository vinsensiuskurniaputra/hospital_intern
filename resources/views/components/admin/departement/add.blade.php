@extends('layouts.admin.add')
@section('the_title', 'Add Departement')
@section('route', route('admin.departements.store'))
@section('is_has_photo', 'false')
@section('input_contents')
    @include('components.general.input_field', [
        'label' => 'Nama Departemen',
        'name' => 'name',
        'type' => 'text',
        'value' => old('name'),
        'required' => true,
        'placeholder' => 'Masukan nama...',
    ])
    @include('components.general.input_field', [
        'label' => 'Keterangan',
        'name' => 'description',
        'type' => 'text',
        'value' => old('description'),
        'required' => false,
        'placeholder' => 'Masukan Keterangan...',
    ])
@endsection
