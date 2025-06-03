@foreach ($students as $i => $student)
    <tr class=" hover:bg-gray-100">
        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
            {{ $i + 1 }}
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
            {{ $student->user->name }}
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
            <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-700 rounded-full">
                {{ $student->grades->first()?->stase?->departement->name ?? 'N/A' }}
            </span>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
            @foreach ($student->grades as $studentGrade)
                <span class="block px-2 py-1 text-xs font-medium bg-green-100 text-green-700 rounded-full mb-1">
                    {{ $studentGrade->stase->name }}
                </span>
            @endforeach
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
            @foreach ($student->grades as $studentGrade)
                <span
                    class="block px-2 py-1 text-xs font-medium rounded-full mb-1
                    {{ $studentGrade->avg_grades > 75 ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}"
                    title="Grade: {{ $studentGrade->avg_grades }}">
                    {{ $studentGrade->avg_grades }}
                </span>
            @endforeach
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
            <span
                class="px-2 py-1 text-sm font-medium rounded-full
                {{ $student->grades->avg('avg_grades') > 75 ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                @if ($student->grades->count() > 0)
                    {{ number_format($student->grades->avg('avg_grades'), 2) }}
                @else
                    Belum ada nilai
                @endif
            </span>
        </td>
    </tr>
@endforeach
