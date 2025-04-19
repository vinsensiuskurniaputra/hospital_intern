@extends('layouts.admin.edit')

@section('title', 'Edit Menu')
@section('the_title', 'Edit Menu')
@section('route_back', route('admin.menus.index'))
@section('route', route('admin.menus.update', $menu->id))
@section('is_has_photo', 'false')

@section('input_contents')
    @include('components.general.input_field', [
        'label' => 'Name',
        'name' => 'name',
        'type' => 'text',
        'value' => old('name', $menu->name),
        'required' => true,
        'placeholder' => 'Enter name...',
    ])
    @include('components.general.input_field', [
        'label' => 'Url',
        'name' => 'url',
        'type' => 'text',
        'value' => old('url', $menu->url),
        'required' => true,
        'placeholder' => 'Enter url...',
    ])

    @include('components.general.input_field', [
        'label' => 'Icon',
        'name' => 'icon',
        'type' => 'text',
        'value' => old('icon', $menu->icon),
        'required' => true,
        'placeholder' => 'Enter icon...',
    ])

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Parent</label>
        <select name="parent_id" class="w-full px-4 py-2 border border-gray-200 rounded-lg">
            <option value="">None</option>
            @foreach ($menus as $menuOption)
                <option value="{{ $menuOption->id }}" @if ($menu->parent && $menu->parent->id == $menuOption->id) selected @endif>
                    {{ $menuOption->name }}
                </option>
            @endforeach
        </select>
        @error('parent_id')
            <span class="text-red-500 text-sm">{{ $message }}</span>
        @enderror
    </div>

    @include('components.general.input_field', [
        'label' => 'Order',
        'name' => 'order',
        'type' => 'number',
        'value' => old('order', $menu->order),
        'required' => true,
        'placeholder' => 'Enter order...',
    ])
@endsection
