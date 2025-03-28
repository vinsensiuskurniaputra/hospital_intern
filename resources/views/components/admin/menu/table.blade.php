@foreach ($menus as $menu)
    <tr class="hover:bg-gray-50">
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 w-full">
            {{ $menu->name }}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
            {{ $menu->url }}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
            <i class="{{ $menu->icon }}"></i>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
            {{ $menu->parent->name ?? '-' }}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
            {{ $menu->order }}</td>


        <td class="px-6 py-4 flex space-x-2 text-center whitespace-nowrap text-sm font-medium">
            <a href="{{ route('admin.menus.edit', $menu->id) }}" class="text-[#637F26] hover:text-[#85A832]">
                <i class="bi bi-pencil"></i>
            </a>
            <form id="delete-student-{{ $menu->id }}" action="{{ route('admin.menus.destroy', $menu->id) }}"
                method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus admin ini?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-500 hover:text-red-600">
                    <i class="bi bi-trash"></i>
                </button>
            </form>
        </td>
    </tr>
@endforeach
