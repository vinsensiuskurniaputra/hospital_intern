@extends('layouts.admin.edit')

@section('title', 'Edit Role')
@section('the_title', 'Edit Role')
@section('route_back', route('admin.roles.index'))
@section('route', route('admin.roles.update', $role->id))
@section('is_has_photo', 'false')

@section('input_contents')
    <!-- Input Hidden untuk User ID -->
    <input type="hidden" name="role_id" value="{{ $role->id }}">
    @include('components.general.input_field', [
        'label' => 'Name',
        'name' => 'name',
        'type' => 'text',
        'value' => old('name', $role->name),
        'required' => true,
        'placeholder' => 'Enter name...',
    ])

    <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 mb-2">Tugaskan Menu:</label>
        <div class="grid grid-cols-2 gap-2">
            @foreach ($menus as $menu)
                <label class="flex items-center space-x-2">
                    <input type="checkbox" name="menus[]" value="{{ $menu->id }}"
                        {{ $role->menus->contains($menu->id) ? 'checked' : '' }} class="rounded border-gray-300">
                    <span class="text-gray-800">{{ $menu->name }}</span>
                </label>
            @endforeach
        </div>

        @error('roles')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>
@endsection
