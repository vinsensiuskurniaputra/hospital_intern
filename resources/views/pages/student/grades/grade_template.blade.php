<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Nilai Mahasiswa</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        h2 { text-align: center; color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #aaa; padding: 8px; text-align: center; }
        th { background-color: #f0f0f0; color: #658E36; }
        .no-data { text-align: center; font-style: italic; }
    </style>
</head>
<body>
    <h2>Detail Nilai Mahasiswa</h2>
    <p><strong>Nama:</strong> {{ $student->user->name ?? 'N/A' }}</p>
    <p><strong>Tanggal Cetak:</strong> {{ $generated_at }}</p>

    <table>
        <thead>
            <tr>
                <th>Stase</th>
                <th>Penanggung Jawab</th>
                <th>Tanggal</th>
                <th>Skill</th>
                <th>Profesionalisme</th>
                <th>Komunikasi</th>
                <th>Manajemen Pasien</th>
                <th>Rata-rata</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($grades as $grade)
                <tr>
                    <td>{{ $grade->stase->name ?? 'N/A' }}</td>
                    <td>
                        @php
                            $pjName = 'N/A';
                            if ($grade->stase && isset($grade->stase->responsibleUsers) && $grade->stase->responsibleUsers->isNotEmpty()) {
                                $responsibleUser = $grade->stase->responsibleUsers->first();
                                if ($responsibleUser && isset($responsibleUser->user)) {
                                    $pjName = $responsibleUser->user->name;
                                }
                            }
                        @endphp
                        {{ $pjName }}
                    </td>
                    <td>{{ isset($grade->updated_at) ? \Carbon\Carbon::parse($grade->updated_at)->format('d M') : '-' }}</td>
                    <td>{{ $grade->skill_grade ?? $grade->avg_grades ?? '-' }}</td>
                    <td>{{ $grade->professional_grade ?? $grade->avg_grades ?? '-' }}</td>
                    <td>{{ $grade->communication_grade ?? $grade->avg_grades ?? '-' }}</td>
                    <td>{{ $grade->patient_management_grade ?? $grade->avg_grades ?? '-' }}</td>
                    <td>{{ $grade->avg_grades ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="no-data">Belum ada nilai yang tersedia</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
