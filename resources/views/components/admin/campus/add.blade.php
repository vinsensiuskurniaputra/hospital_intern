@extends('layouts.admin.add')
@section('the_title', 'Add Campus')
@section('route', route('admin.campuses.store'))
@section('is_has_photo', 'false')
@section('input_contents')
    @include('components.general.input_field', [
        'label' => 'Nama Kampus',
        'name' => 'name',
        'type' => 'text',
        'value' => old('name'),
        'required' => true,
        'placeholder' => 'Masukan Nama...',
    ])

    @include('components.general.input_field', [
        'label' => 'Keterangan',
        'name' => 'detail',
        'type' => 'text',
        'value' => old('detail'),
        'required' => false,
        'placeholder' => 'Masukan keterangan...',
    ])
@endsection
