<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>Kelas Magang</th>
                <th>Stase</th>
                <th>Departemen</th>
                <th>Tahun Angkatan</th>
                <th>Pembimbing</th>
                <th>Periode Rotasi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($schedules as $schedule)
            <tr>
                <td>{{ $schedule->internshipClass->name }}</td>
                <td>{{ $schedule->stase->name }}</td>
                <td>{{ $schedule->stase->departement->name }}</td>
                <td>{{ $schedule->internshipClass->classYear->year }}</td>
                <td>{{ $schedule->stase->responsibleUser->user->name }}</td>
                <td>{{ \Carbon\Carbon::parse($schedule->start_date)->format('d-m-Y') }} s/d {{ \Carbon\Carbon::parse($schedule->end_date)->format('d-m-Y') }}</td>
                <td>
                    <div class="d-flex gap-2">
                        <a href="{{ route('presences.schedules.edit', $schedule) }}" class="btn btn-sm btn-primary">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('presences.schedules.destroy', $schedule) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus?')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    @if($allSchedules->total() > 0)
        <div class="px-6 py-4 border-t">
            <div class="text-sm text-gray-700">
                Showing {{ $allSchedules->firstItem() }} to {{ $allSchedules->lastItem() }} of {{ $allSchedules->total() }} results
            </div>
            {{ $allSchedules->links() }}
        </div>
    @endif
</div>