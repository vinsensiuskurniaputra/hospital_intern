@foreach ($roles as $i => $role)
    <tr class="hover:bg-gray-50">
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
            {{ $i + 1 }}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
            {{ $role->name }}</td>
        <td class="px-6 py-4 whitespace-normal max-w-md text-sm text-gray-900">
            <div class="space-y-2">
                @foreach ($role->menus->whereNull('parent_id') as $parentMenu)
                    <div>
                        <!-- Parent Menu -->
                        <span
                            class="inline-flex items-center px-2 py-1 bg-[#F5F7F0] text-[#637F26] text-xs font-medium rounded">
                            <i class="{{ $parentMenu->icon ?? 'bi bi-circle' }} mr-1"></i>
                            {{ $parentMenu->name }}
                        </span>

                        <!-- Child Menus -->
                        @if ($role->menus->where('parent_id', $parentMenu->id)->count() > 0)
                            <div class="ml-4 mt-1 flex flex-wrap gap-1">
                                @foreach ($role->menus->where('parent_id', $parentMenu->id) as $childMenu)
                                    <span class="px-2 py-0.5 bg-gray-100 text-gray-600 text-xs font-medium rounded-full">
                                        {{ $childMenu->name }}
                                    </span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </td>

        <td class="px-6 py-4 flex space-x-2 text-center whitespace-nowrap text-sm font-medium">
            <a href="{{ route('admin.roles.edit', $role->id) }}" class="text-[#637F26] hover:text-[#85A832]">
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
