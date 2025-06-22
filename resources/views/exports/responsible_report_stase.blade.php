<table>
    <tr>
        <td colspan="18" style="font-size:20px; font-weight:bold; text-align:center;">
            LAPORAN REKAPITULASI {{ strtoupper($stase->name ?? '-') }}<br>
            TAHUN AKADEMIK 2024/2025
        </td>
    </tr>
    <tr>
        <td colspan="18" style="text-align:center; font-size:14px; padding-bottom:10px;">
            Rumah Sakit Pendidikan - Sistem Magang Terintegrasi
        </td>
    </tr>
</table>
<table border="1" cellpadding="6" cellspacing="0" style="border-collapse:collapse; font-size:12px; width:100%;">
    <thead style="background:#e5e7eb;">
        <tr>
            <th style="min-width:80px;">NIM</th>
            <th style="min-width:140px;">Nama</th>
            <th style="min-width:110px;">Kelas Magang</th>
            <th style="min-width:100px;">Stase</th>
            <th style="min-width:110px;">Jurusan</th>
            <th style="min-width:110px;">Kampus</th>
            <th style="min-width:80px;">Tahun Angkatan</th>
            <th style="min-width:60px;">Total Pertemuan</th>
            <th style="min-width:50px;">Hadir</th>
            <th style="min-width:50px;">Izin</th>
            <th style="min-width:50px;">Sakit</th>
            <th style="min-width:50px;">Alpa</th>
            <th style="min-width:80px;">% Kehadiran</th>
            @foreach($wantedComponents as $compName)
                <th style="min-width:90px;">{{ $compName }}</th>
            @endforeach
            <th style="min-width:80px;">Nilai Rata-Rata</th>
        </tr>
    </thead>
    <tbody>
        @foreach($students as $student)
            @php
                $absensi = $absensiData->get($student->id);
                $grade = $gradesData->get($student->id);
                $studentComponentGrades = $componentGrades->get($student->id, collect());
                $componentMap = [];
                foreach ($studentComponentGrades as $comp) {
                    $compName = $gradeComponents[$comp->grade_component_id] ?? null;
                    if ($compName) {
                        $componentMap[$compName] = $comp->value;
                    }
                }
            @endphp
            <tr>
                <td>{{ $student->nim }}</td>
                <td>{{ $student->user->name }}</td>
                <td>{{ $student->internshipClass->name }}</td>
                <td>{{ $stase->name }}</td>
                <td>{{ $student->studyProgram->name }}</td>
                <td>{{ $student->studyProgram->campus->name }}</td>
                <td>{{ $student->internshipClass->classYear->class_year }}</td>
                <td style="text-align:center;">{{ $absensi ? $absensi->total_presensi : 0 }}</td>
                <td style="text-align:center;">{{ $absensi ? $absensi->hadir : 0 }}</td>
                <td style="text-align:center;">{{ $absensi ? $absensi->izin ?? 0 : 0 }}</td>
                <td style="text-align:center;">{{ $absensi ? $absensi->sakit ?? 0 : 0 }}</td>
                <td style="text-align:center;">{{ $absensi ? $absensi->alpa ?? 0 : 0 }}</td>
                <td style="text-align:center;">{{ $absensi ? $absensi->persentase_present ?? 0 : 0 }}</td>
                @foreach($wantedComponents as $compName)
                    <td style="text-align:center;">
                        {{ isset($componentMap[$compName]) ? number_format($componentMap[$compName], 2, ',', '') : '0' }}
                    </td>
                @endforeach
                <td style="text-align:center;">{{ $grade ? number_format($grade->average_grade, 2, ',', '') : 0 }}</td>
            </tr>
        @endforeach
    </tbody>
</table>