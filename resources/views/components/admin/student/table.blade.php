@foreach ($students as $i => $student)
    <tr class="hover:bg-gray-50">
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="flex items-center">
                <img class="h-8 w-8 rounded-full"
                    src="{{ $student->user->photo_profile_url ? asset('storage/' . $student->user->photo_profile_url) : 'https://ui-avatars.com/api/?name=' . urlencode($student->user->name) }}"
                    alt="">
                <div class="ml-4 ">
                    <div class="text-sm font-medium text-gray-900">{{ $student->user->name }}
                    </div>
                    <div class="text-sm text-gray-500">{{ $student->user->email }}
                    </div>
                </div>
            </div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
            {{ $student->user->username }}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
            {{ $student->internshipClass->name }}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
            {{ $student->studyProgram->name }}</td>
        <td class="px-6 py-4 whitespace-normal break-words max-w-sm text-sm text-gray-900">
            {{ $student->studyProgram->campus->name }}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
            {{ $student->internshipClass->classYear->class_year }}</td>
        <td class="px-6 py-4 whitespace-nowrap">
            <span
                class="px-2 py-1 text-xs font-medium rounded-full {{ $student->status == 0 ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                {{ $student->status == 0 ? 'On Study' : 'Passed' }}
            </span>
        </td>
        <td class="px-6 py-4 flex whitespace-nowrap text-right text-sm font-medium space-x-2">
            <a href="{{ route('admin.students.show', $student->id) }}" class="text-[#637F26] hover:text-[#85A832]">
                <i class="bi bi-pencil"></i>
            </a>
            <form id="delete-student-{{ $student->id }}" action="{{ route('admin.students.destroy', $student->id) }}"
                method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus student ini?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-500 hover:text-red-600">
                    <i class="bi bi-trash"></i>
                </button>
            </form>
        </td>
    </tr>
@endforeach
