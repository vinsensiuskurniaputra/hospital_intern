@foreach ($responsibles as $responsible)
    <tr class="hover:bg-gray-50">
        <td class="px-6 py-4 whitespace-nowrap w-full">
            <div class="flex items-center">
                <img class="h-8 w-8 rounded-full"
                    src="{{ $responsible->user->photo_profile_url ? asset('storage/' . $responsible->user->photo_profile_url) : 'https://ui-avatars.com/api/?name=' . urlencode($responsible->user->name) }}"
                    alt="">
                <div class="ml-4 ">
                    <div class="text-sm font-medium text-gray-900">{{ $responsible->user->name }}
                    </div>
                </div>
            </div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
            {{ $responsible->user->username }}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
            {{ $responsible->user->email }}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
            {{ $responsible->telp }}</td>

        <td class="px-6 py-4 flex whitespace-nowrap text-right text-sm font-medium space-x-2">
            <a href="{{ route('admin.responsibles.edit', $responsible->id) }}" class="text-[#637F26] hover:text-[#85A832]">
                <i class="bi bi-pencil"></i>
            </a>
            <form id="delete-student-{{ $responsible->id }}"
                action="{{ route('admin.responsibles.destroy', $responsible->id) }}" method="POST"
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
