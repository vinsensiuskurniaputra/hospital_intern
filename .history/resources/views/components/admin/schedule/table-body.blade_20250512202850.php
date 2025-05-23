<?php
@foreach ($allSchedules as $schedule)
<tr>
    <td class="py-3 px-4">{{ $schedule->internshipClass->name ?? 'N/A' }}</td>
    <td class="py-3 px-4">{{ $schedule->stase->name ?? 'N/A' }}</td>
    <td class="py-3 px-4">{{ $schedule->stase->departement->name ?? 'N/A' }}</td>
    <td class="py-3 px-4">{{ $schedule->internshipClass->classYear->class_year ?? 'N/A' }}</td>
    <td class="py-3 px-4">
        @foreach ($schedule->stase->responsibleUsers as $responsible)
            <div class="py-1">{{ $responsible->user->name }}</div>
        @endforeach
    </td>
    <td class="py-3 px-4">
        @if ($schedule->start_date && $schedule->end_date)
            {{ \Carbon\Carbon::parse($schedule->start_date)->format('d-m-Y') }} s/d
            {{ \Carbon\Carbon::parse($schedule->end_date)->format('d-m-Y') }}
        @else
            N/A
        @endif
    </td>
    <td class="py-3 px-4">
        <div class="flex gap-2">
            <a href="{{ route('presences.schedules.edit', $schedule->id) }}" class="text-blue-500">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
            </a>
            <form action="{{ route('presences.schedules.destroy', $schedule->id) }}" method="POST" class="inline delete-form">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-500">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </button>
            </form>
        </div>
    </td>
</tr>
@endforeach