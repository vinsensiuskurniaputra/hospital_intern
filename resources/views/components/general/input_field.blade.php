<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">{{ $label }}</label>
    <input type="{{ $type }}" name="{{ $name }}" value="{{ $value }}"
        {{ $required ? 'required' : '' }} placeholder="{{ $placeholder }}"
        class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#637F26] focus:border-[#637F26]">
    @error($name)
        <span class="text-red-500 text-sm">{{ $message }}</span>
    @enderror
</div>
