@foreach($schedules as $schedule)
    <tr>
        <td class="py-3 px-4">{{ $schedule->internshipClass->name }}</td>
        <td class="py-3 px-4">{{ $schedule->stase }}</td>
        <td class="py-3 px-4">{{ $schedule->departement->name }}</td>
        <td class="py-3 px-4">{{ $schedule->internshipClass->classYear->class_year }}</td>
        <td class="py-3 px-4">{{ $schedule->responsibleUser->user->name }}</td>
        <td class="py-3 px-4">
            {{ \Carbon\Carbon::parse($schedule->rotation_period_start)->format('d-m-Y') }} s/d 
            {{ \Carbon\Carbon::parse($schedule->rotation_period_end)->format('d-m-Y') }}
        </td>
        <td class="py-3 px-4">
            {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - 
            {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
        </td>
        <td class="py-3 px-4">
            <!-- Action buttons same as above -->
        </td>
    </tr>
@endforeach