@foreach ($roles as $i => $role)
    <tr class="hover:bg-gray-50">
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
            {{ $i + 1 }}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
            {{ $role->name }}</td>
        <td class="px-6 py-4 whitespace-normal max-full text-sm text-gray-900">
            <div class="flex flex-wrap gap-1">
                @foreach ($role->menus as $menu)
                    <span class="px-2 py-1 bg-green-100 text-green-700 text-xs font-medium rounded">
                        {{ $menu->name }}
                    </span>
                @endforeach
            </div>
        </td>

        <td class="px-6 py-4 flex space-x-2 text-center whitespace-nowrap text-sm font-medium">
            <a href="{{ route('admin.roles.edit', $role->id) }}"
                class="text-[#637F26] hover:text-[#85A832]">
                <i class="bi bi-pencil"></i>
            </a>
            <form id="delete-student-{{ $role->id }}" action="{{ route('admin.roles.destroy', $role->id) }}"
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
