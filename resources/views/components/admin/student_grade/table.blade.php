<table class="w-full border-collapse border border-gray-300" id="TableBody">
    <thead>
        <tr>
            <th rowspan="2" class="border border-gray-300 px-4 py-2">No</th>
            <th rowspan="2" class="border border-gray-300 px-4 py-2">Name</th>
            <th rowspan="2" class="border border-gray-300 px-4 py-2">Departement</th>
            <th colspan="{{ $stases->count() }}" class="border border-gray-300 px-4 py-2">Nilai</th>
            <th rowspan="2" class="border border-gray-300 px-4 py-2">Status</th>
        </tr>
        <tr>
            @foreach ($stases as $stase)
                <th class="border border-gray-300 px-4 py-2">{{ $stase->name }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($students as $i => $student)
            <tr class="hover:bg-gray-100">
                <td class="border border-gray-300 px-4 py-2 whitespace-nowrap text-sm font-medium text-gray-900">
                    {{ $i + 1 }}
                </td>
                <td class="border border-gray-300 px-4 py-2 whitespace-nowrap text-sm text-gray-900">
                    {{ $student->user->name }}
                </td>
                <td class="border border-gray-300 px-4 py-2 whitespace-nowrap text-sm text-gray-900">
                    {{ $student->internshipClass?->schedules->first()?->stase?->departement?->name ?? 'N/A' }}
                </td>
                @foreach ($stases as $stase)
                    <td class="border text-center border-gray-300 px-4 py-2 whitespace-nowrap text-sm text-gray-900">
                        {{ $student->grades->where('stase.name', $stase->name)->first()?->avg_grades ?? '-' }}
                    </td>
                @endforeach
                <td class="border border-gray-300 px-4 py-2 whitespace-nowrap text-sm text-gray-900">
                    <form id="change-status-{{ $student->id }}"
                        action="{{ route('students.change_status', $student->id) }}" method="POST"
                        onsubmit="return confirm('Apakah Anda yakin ingin mengubah status mahasiswa ini?');">
                        @csrf
                        @method('PUT')
                        <div class="flex justify-center items-center gap-2">
                            <span
                                class="{{ $student->is_finished ? 'text-green-600' : 'text-yellow-600' }} 
                                  font-medium text-sm">
                                {{ $student->is_finished ? 'Sudah Lulus' : 'Masih Magang' }}
                            </span>
                            @if ($student->is_finished)
                                <button type="submit"
                                    class="inline-flex items-center justify-center p-2 rounded-full 
                                    bg-green-100 text-green-600 
                                    hover:bg-red-600 hover:text-white
                                    transition-all duration-300 ease-in-out 
                                    focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2
                                    transform hover:scale-105">
                                    <i class="bi bi-x-lg text-lg"></i>
                                    <span class="sr-only">Cancel Status</span>
                                </button>
                            @else
                                <button type="submit"
                                    class="inline-flex items-center justify-center p-2 rounded-full 
                                    bg-yellow-100 text-yellow-600 
                                    hover:bg-green-600 hover:text-white
                                    transition-all duration-300 ease-in-out 
                                    focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2
                                    transform hover:scale-105">
                                    <i class="bi bi-check-lg text-lg"></i>
                                    <span class="sr-only">Complete Status</span>
                                </button>
                            @endif

                        </div>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
