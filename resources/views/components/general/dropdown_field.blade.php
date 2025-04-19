<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">{{ $label }}</label>
    <select name="{{ $name }}" class="w-full px-4 py-2 border border-gray-200 rounded-lg">
        <option value="{{ $default_value ?? null }}">{{ $default_item }}</option>
        @foreach ($datas as $data)
            <option value="{{ $data->id }}">{{ $data->name }}</option>
        @endforeach
    </select>
    @error($name)
        <span class="text-red-500 text-sm">{{ $message }}</span>
    @enderror
</div>
