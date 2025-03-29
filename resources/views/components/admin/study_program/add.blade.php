@extends('layouts.admin.add')
@section('the_title', 'Add Study Program')
@section('route', route('admin.studyPrograms.store'))
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
    @include('components.general.dropdown_field', [
        'label' => 'Campus',
        'name' => 'campus_id',
        'default_item' => 'Select Camppus',
        'datas' => $campuses
    ])
@endsection
