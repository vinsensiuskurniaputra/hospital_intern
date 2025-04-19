@extends('layouts.admin.add')
@section('the_title', 'Add Role')
@section('route', route('admin.roles.store'))
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
    <label class="block text-sm font-medium text-gray-700 mb-2">Assign Menu:</label>
    <div class="grid grid-cols-2 gap-2">
        @foreach ($menus as $menu)
            <label class="flex items-center space-x-2">
                <input type="checkbox" name="menus[]" value="{{ $menu->id }}" class="rounded border-gray-300">
                <span class="text-gray-800">{{ $menu->name }}</span>
            </label>
        @endforeach
    </div>
    @error('roles')
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
    @enderror
@endsection
