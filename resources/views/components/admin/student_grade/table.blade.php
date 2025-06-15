<table class="w-full border-collapse border border-gray-300" id="TableBody">
    <thead>
        <tr>
            <th rowspan="2" class="border border-gray-300 px-4 py-2">No</th>
            <th rowspan="2" class="border border-gray-300 px-4 py-2">Name</th>
            <th rowspan="2" class="border border-gray-300 px-4 py-2">Departement</th>
            <th colspan="{{ $stases->count() }}" class="border border-gray-300 px-4 py-2">Nilai</th>
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
                    <td class="border border-gray-300 px-4 py-2 whitespace-nowrap text-sm text-gray-900">
                        {{ $student->grades->where('stase.name', $stase->name)->first()?->avg_grades ?? '' }}
                    </td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>
