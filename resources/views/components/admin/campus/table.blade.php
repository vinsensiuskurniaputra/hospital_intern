@foreach ($campuses as $campus)
    <tr class="hover:bg-gray-50">
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
            {{ $campus->name }}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
            {{ $campus->detail }}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
            {{ $campus->studyPrograms->count() }}</td>

        <td class="px-6 py-4 flex whitespace-nowrap text-right text-sm font-medium space-x-2">
            <a href="{{ route('admin.campuses.edit', $campus->id) }}" class="text-[#637F26] hover:text-[#85A832]">
                <i class="bi bi-pencil"></i>
            </a>
            <form id="delete-student-{{ $campus->id }}"
                action="{{ route('admin.campuses.destroy', $campus->id) }}" method="POST"
                onsubmit="return confirm('Apakah Anda yakin ingin menghapus admin ini?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-500 hover:text-red-600">
                    <i class="bi bi-trash"></i>
                </button>
            </form>
        </td>
    </tr>
@endforeach
