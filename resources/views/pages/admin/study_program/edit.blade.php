@extends('layouts.admin.edit')

@section('title', 'Edit Study Program')
@section('the_title', 'Edit Study Program')
@section('route_back', route('admin.studyPrograms.index'))
@section('route', route('admin.studyPrograms.update', $studyProgram->id))
@section('is_has_photo', 'false')

@section('input_contents')
    @include('components.general.input_field', [
        'label' => 'Name',
        'name' => 'name',
        'type' => 'text',
        'value' => old('name', $studyProgram->name),
        'required' => true,
        'placeholder' => 'Enter name...',
    ])
    @include('components.general.dropdown_field', [
        'label' => 'Campus',
        'name' => 'campus_id',
        'default_value' => $studyProgram->campus->id,
        'default_item' => $studyProgram->campus->name,
        'datas' => $campuses
    ])
@endsection
