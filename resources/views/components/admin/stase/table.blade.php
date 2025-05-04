@foreach ($stases as $i => $stase)
    <tr class="hover:bg-gray-50">
        <td class="px-6 py-4 w-12 whitespace-nowrap text-sm text-gray-900">
            {{ $i + 1 }}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
            {{ $stase->name }}</td>
        <td class="px-6 py-4 text-sm text-gray-900">
            {{ $stase->detail }}</td>
        <td class="px-6 py-4 text-sm text-gray-900">
            {{ $stase->responsibleUser->user->name }}</td>
        <td class="px-6 py-4 text-sm text-gray-900">
            {{ $stase->departement->name }}</td>

        <td class="px-6 py-4 flex space-x-2 text-center whitespace-nowrap text-sm font-medium">
            <a href="{{ route('admin.stases.edit', $stase->id) }}" class="text-[#637F26] hover:text-[#85A832] ml-auto">
                <i class="bi bi-pencil"></i>
            </a>
            <form id="delete-student-{{ $stase->id }}" action="{{ route('admin.stases.destroy', $stase->id) }}"
                method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus Stase ini?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-500 hover:text-red-600">
                    <i class="bi bi-trash"></i>
                </button>
            </form>
        </td>
    </tr>
@endforeach
