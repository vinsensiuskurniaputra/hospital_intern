@extends('layouts.admin.edit')

@section('title', 'Edit User Roles')
@section('the_title', 'Edit User Roles')
@section('route_back', route('admin.user_authorizations.index'))
@section('route', route('admin.user_authorizations.update', $user->id))
@section('is_has_photo', 'false')

@section('input_contents')
    <!-- Input Hidden untuk User ID -->
    <input type="hidden" name="user_id" value="{{ $user->id }}">

    <!-- Daftar Role -->
    <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 mb-2">Menugaskan Peran:</label>
        <div class="grid grid-cols-2 gap-2">
            @foreach ($roles as $role)
                <label class="flex items-center space-x-2">
                    <input type="checkbox" name="roles[]" value="{{ $role->id }}"
                        {{ $user->roles->contains($role->id) ? 'checked' : '' }} class="rounded border-gray-300">
                    <span class="text-gray-800">{{ $role->name }}</span>
                </label>
            @endforeach
        </div>

        @error('roles')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>
@endsection
