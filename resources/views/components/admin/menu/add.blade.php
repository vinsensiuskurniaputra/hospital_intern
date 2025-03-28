@extends('layouts.admin.add')
@section('the_title', 'Add Menu')
@section('route', route('admin.menus.store'))
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
    @include('components.general.input_field', [
        'label' => 'Url',
        'name' => 'url',
        'type' => 'text',
        'value' => old('url'),
        'required' => true,
        'placeholder' => 'Enter url...',
    ])

    @include('components.general.input_field', [
        'label' => 'Icon',
        'name' => 'icon',
        'type' => 'text',
        'value' => old('icon'),
        'required' => true,
        'placeholder' => 'Enter icon...',
    ])

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Parent</label>
        <select name="parent_id"  class="w-full px-4 py-2 border border-gray-200 rounded-lg">
            <option value="">None</option>
            @foreach ($menus as $menu)
                <option value="{{ $menu->id }}">{{ $menu->name }}</option>
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
        'value' => old('order'),
        'required' => true,
        'placeholder' => 'Enter order...',
    ])
@endsection
