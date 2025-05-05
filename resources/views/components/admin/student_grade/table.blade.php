@foreach ($studentGrades as $i => $studentGrade)
    <tr class="hover:bg-gray-50">
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
            {{ $i + 1 }}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
            {{ $studentGrade->student->user->name }}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
            {{ $studentGrade->stase->name }}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
            {{ $studentGrade->stase->departement->name }}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
            <span
                class="px-2 py-1 text-sm font-medium rounded-full
                                    {{ $studentGrade->avg_grades > 75 ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                {{ $studentGrade->avg_grades }}
            </span>

        </td>
    </tr>
@endforeach
