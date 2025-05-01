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
    <div class="space-y-4">
        <label class="block text-sm font-medium text-gray-700">Assign Menu Access:</label>
        <div class="space-y-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
            @foreach ($menus->whereNull('parent_id') as $parentMenu)
                <div class="space-y-2">
                    <!-- Parent Menu -->
                    <label class="flex items-center p-2 hover:bg-white rounded transition-colors">
                        <input type="checkbox" name="menus[]" value="{{ $parentMenu->id }}"
                            class="rounded border-gray-300 text-[#637F26] focus:ring-[#637F26]"
                            onchange="toggleChildren(this, 'children-{{ $parentMenu->id }}')">
                        <span class="ml-2 text-sm font-medium text-gray-700">{{ $parentMenu->name }}</span>
                    </label>

                    <!-- Child Menus -->
                    @if ($parentMenu->children && $parentMenu->children->count() > 0)
                        <div class="ml-6 pl-4 border-l border-gray-200" id="children-{{ $parentMenu->id }}">
                            @foreach ($parentMenu->children as $childMenu)
                                <label class="flex items-center p-2 hover:bg-white rounded transition-colors">
                                    <input type="checkbox" name="menus[]" value="{{ $childMenu->id }}"
                                        class="rounded border-gray-300 text-[#637F26] focus:ring-[#637F26] child-of-{{ $parentMenu->id }}">
                                    <span class="ml-2 text-sm text-gray-600">{{ $childMenu->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
    @error('menus')
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
    @enderror

    <script>
        function toggleChildren(parentCheckbox, childrenId) {
            const children = document.querySelectorAll(`#${childrenId} input[type="checkbox"]`);
            children.forEach(child => {
                child.checked = parentCheckbox.checked;
            });
        }
    </script>
@endsection
