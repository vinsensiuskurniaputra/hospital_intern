@extends('layouts.auth')

@section('title', 'Nilai Mahasiswa')

@section('content')
<div class="page-container">
    <div class="main-content">
        <div class="header-section">
            <h1>Nilai Mahasiswa</h1>
            <p>Menampilkan rekap nilai akhir mahasiswa selama periode rotasi klinik.</p>
        </div>
        
        <div class="student-info-section">
            <div class="section-title">Informasi Mahasiswa</div>
            
            <div class="info-row">
                <div class="info-column">
                    <div class="info-label">Nama Lengkap</div>
                    <div class="info-input-wrapper">
                        <input type="text" class="info-input" value="{{ auth()->user()->name }}" readonly>
                    </div>
                </div>
                <div class="info-column">
                    <div class="info-label">Nomor Induk Mahasiswa</div>
                    <div class="info-input-wrapper">
                        <input type="text" class="info-input" value="{{ auth()->user()->student->nim ?? '-' }}" readonly>
                    </div>
                </div>
            </div>
            
            <div class="info-row">
                <div class="info-column">
                    <div class="info-label">Asal Kampus</div>
                    <div class="info-input-wrapper">
                        <input type="text" class="info-input" value="{{ auth()->user()->student->studyProgram->campus->name ?? '-' }}" readonly>
                    </div>
                </div>
                <div class="info-column"></div>
            </div>
        </div>
        
        <div class="grades-details-section">
            <div class="section-header">
                <h2>Detail Nilai Mahasiswa</h2>
            </div>
            
            <div class="grades-table-wrapper">
                <table class="grades-table">
                    <thead>
                        <tr>
                            <th class="column-stase">Stase</th>
                            <th class="column-pj">Nama PJ</th>
                            <th class="column-date">Tanggal</th>
                            <th>Keahlian</th>
                            <th>Komunikasi</th>
                            <th>Profesionalisme</th>
                            <th>Kemampuan Menangani Pasien</th>
                            <th class="column-average">Rata-Rata</th>
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
                                <td>{{ $grade->communication_grade ?? $grade->avg_grades ?? '-' }}</td>
                                <td>{{ $grade->professional_grade ?? $grade->avg_grades ?? '-' }}</td>
                                <td>{{ $grade->patient_management_grade ?? $grade->avg_grades ?? '-' }}</td>
                                <td class="score-average">{{ $grade->avg_grades ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="no-data">Belum ada nilai yang tersedia</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="export-container">
                <a href="{{ route('student.grades.export') }}" class="export-a">Ekspor ke PDF</button>
            </div>

            <!-- Hidden print template -->
            <div class="print-only-template">
                <div class="print-header">
                    <div class="print-date">5/22/25, 5:00 PM</div>
                    <div class="print-title">Nilai Mahasiswa</div>
                </div>
                
                <div class="hospital-info">
                    <div class="hospital-logo">
                        <span class="logo-icon">⚕️</span> Hospital Intern
                    </div>
                </div>
                
                <div class="report-title">Nilai Mahasiswa</div>
                <div class="report-description">Menampilkan rekap nilai akhir mahasiswa selama periode rotasi klinik.</div>
                
                <div class="student-print-info">
                    <div class="student-info-item">
                        <span class="info-label">Nama Lengkap:</span> 
                        <span class="info-value">{{ auth()->user()->name }}</span>
                    </div>
                    <div class="student-info-item">
                        <span class="info-label">Nomor Induk Mahasiswa:</span> 
                        <span class="info-value">{{ auth()->user()->student->nim ?? '-' }}</span>
                    </div>
                    <div class="student-info-item">
                        <span class="info-label">Asal Kampus:</span> 
                        <span class="info-value">{{ auth()->user()->student->studyProgram->campus->name ?? '-' }}</span>
                    </div>
                </div>
                
                <table class="print-grades-table">
                    <thead>
                        <tr>
                            <th>Stase</th>
                            <th>Nama PJ</th>
                            <th>Tanggal</th>
                            <th>Keahlian</th>
                            <th>Komunikasi</th>
                            <th>Profesionalisme</th>
                            <th>Kemampuan Menangani Pasien</th>
                            <th>Rata-Rata</th>
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
                                <td>{{ $grade->communication_grade ?? $grade->avg_grades ?? '-' }}</td>
                                <td>{{ $grade->professional_grade ?? $grade->avg_grades ?? '-' }}</td>
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
            </div>
        </div>
    </div>
</div>

<style>
    /* Modern Color Palette */
    :root {
        --primary-color: #658E36;
        --primary-light: #f4f7f0;
        --light-gray: #f5f5f5;
        --medium-gray: #e0e0e0;
        --dark-gray: #6c757d;
        --header-color: #333;
        --text-color: #444;
        --border-color: #eaeaea;
        --white: #ffffff;
    }
    
    /* Main layout */
    .page-container {
        max-width: 100%;
        padding: 30px;
        background-color: #f8f9fa;
        min-height: calc(100vh - 60px);
    }
    
    .main-content {
        max-width: 1200px;
        margin: 0 auto;
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        padding: 30px;
    }
    
    /* Header section */
    .header-section {
        margin-bottom: 35px;
        border-bottom: 1px solid var(--border-color);
        padding-bottom: 20px;
    }
    
    .header-section h1 {
        font-size: 24px;
        color: var(--header-color);
        font-weight: 600;
        margin-bottom: 8px;
    }
    
    .header-section p {
        color: var(--dark-gray);
        font-size: 14px;
        margin-bottom: 0;
    }
    
    /* Section title styling - UPDATED: removed bottom border */
    .section-title {
        font-size: 18px;
        font-weight: 600;
        color: var(--header-color);
        margin-bottom: 20px;
        padding-bottom: 10px;
    }
    
    /* Student info section - UPDATED WITH CARD-LIKE SHADOWS */
    .student-info-section {
        margin-bottom: 40px;
    }
    
    .info-row {
        display: flex;
        margin-bottom: 25px;
        gap: 24px;
        width: 100%;
    }
    
    .info-column {
        flex: 1;
    }
    
    .info-label {
        color: var(--dark-gray);
        font-size: 16px;
        margin-bottom: 8px;
        font-weight: 400;
    }
    
    .info-input-wrapper {
        position: relative;
    }
    
    .info-input {
        width: 100%;
        background-color: var(--white);
        padding: 14px 16px;
        border-radius: 8px;
        border: none;
        font-size: 16px;
        color: var(--text-color);
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        box-sizing: border-box;
        height: 50px;
        transition: all 0.3s ease;
        cursor: default;
    }
    
    /* UPDATED: Hover effect with enhanced shadow */
    .info-input:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    .info-input:focus {
        box-shadow: 0 4px 12px rgba(0,0,0,0.12);
        outline: none;
    }
    
    /* Remove the text selection cursor */
    .info-input::selection {
        background: transparent;
    }
    
    /* Grades details section */
    .grades-details-section {
        margin-top: 20px;
    }
    
    .section-header {
        margin-bottom: 20px;
        position: relative;
        padding-left: 15px;
        border-left: 5px solid var(--primary-color);
    }
    
    .section-header h2 {
        font-size: 18px;
        color: var(--primary-color);
        font-weight: 600;
        margin: 0;
    }
    
    /* Table styling */
    .grades-table-wrapper {
        overflow-x: auto;
        margin-bottom: 30px;
        border: 1px solid var(--border-color);
        border-radius: 6px;
    }
    
    .grades-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .grades-table thead {
        background-color: var(--primary-light);
    }
    
    .grades-table th {
        padding: 14px 16px;
        font-size: 13px;
        color: var(--primary-color);
        font-weight: 600;
        text-align: center;
        border-bottom: 1px solid var(--border-color);
    }
    
    .grades-table .column-stase,
    .grades-table .column-pj {
        text-align: center;
    }
    
    .grades-table tbody tr:nth-child(odd) {
        background-color: white;
    }
    
    .grades-table tbody tr:nth-child(even) {
        background-color: var(--light-gray);
    }
    
    .grades-table td {
        padding: 14px 16px;
        font-size: 14px;
        color: var(--text-color);
        text-align: center;
        border-bottom: 1px solid var(--border-color);
    }
    
    .grades-table td:first-child,
    .grades-table td:nth-child(2) {
        text-align: center;
    }
    
    .score-average {
        font-weight: 600;
    }
    
    .no-data {
        text-align: center !important;
        padding: 20px !important;
        color: var(--dark-gray);
    }
    
    /* Table row hover effect */
    .grades-table tbody tr {
        transition: background-color 0.2s ease;
    }
    
    .grades-table tbody tr:hover {
        background-color: #f0f5e8;
    }
    
    /* Export button */
    .export-container {
        display: flex;
        justify-content: flex-end;
        margin-top: 20px;
    }
    
    .export-button {
        background-color: var(--primary-color);
        color: white;
        padding: 10px 20px;
        border-radius: 4px;
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.2s ease;
    }
    
    .export-button:hover {
        background-color: #557a2f;
        text-decoration: none;
        color: white;
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    }
    
    /* Responsive tweaks */
    @media (max-width: 768px) {
        .page-container {
            padding: 15px;
        }
        
        .main-content {
            padding: 20px;
        }
        
        .info-row {
            flex-direction: column;
            gap: 20px;
        }
    }

    @media print {
        /* Hide everything except the grades details section */
        nav, header, footer, .export-container, .sidebar, .header-section, .student-info-section {
            display: none !important;
        }
        
        /* Reset page layout for printing */
        .page-container {
            padding: 0 !important;
            background-color: white !important;
            min-height: auto !important;
        }
        
        .main-content {
            box-shadow: none !important;
            padding: 20px !important;
            max-width: 100% !important;
        }
        
        /* Make sure tables fit on page */
        .grades-table {
            width: 100% !important;
            page-break-inside: avoid;
        }
        
        /* Add a title for the printed page */
        .grades-details-section::before {
            content: "Nilai Mahasiswa";
            display: block;
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
        }
        
        body {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        
        /* Ensure the grades section appears at the top of the page */
        .grades-details-section {
            margin-top: 0 !important;
        }
    }

    /* Print-only template styling */
    .print-only-template {
        display: none;
    }

    @media print {
        /* Hide regular page content */
        body > *:not(script),
        .page-container, 
        .main-content > *:not(.print-only-template),
        nav, header, footer, .sidebar {
            display: none !important;
        }
        
        /* Show only the print template */
        .print-only-template {
            display: block !important;
            padding: 20px;
            max-width: 100%;
            margin: 0 auto;
        }
        
        /* Ensure print template takes the whole page */
        body {
            margin: 0;
            padding: 0;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        
        /* Remove any page margins */
        @page {
            size: auto;
            margin: 10mm;
        }
        
        /* Rest of your print styles remain the same */
        .print-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        
        /* Other print styles stay the same... */
        
    }
</style>
<script>
    function printGrades() {
    // Pastikan template cetak terlihat
    const printTemplate = document.querySelector('.print-only-template');
        if (printTemplate) {
            printTemplate.style.display = 'block';
        }
        
        // Update tanggal di template cetak
        const now = new Date();
        const formattedDate = `${now.getDate()}/${now.getMonth()+1}/${now.getFullYear()}, ${now.getHours().toString().padStart(2, '0')}:${now.getMinutes().toString().padStart(2, '0')}`;
        
        if (document.querySelector('.print-date')) {
            document.querySelector('.print-date').textContent = formattedDate;
        }
        
        // Ubah judul yang ditampilkan di template cetak
        if (document.querySelector('.print-title')) {
            document.querySelector('.print-title').textContent = "Nilai Mahasiswa";
        }
        
        if (document.querySelector('.report-title')) {
            document.querySelector('.report-title').textContent = "Detail Nilai Mahasiswa";
        }
        
        // Pastikan tabel nilai terlihat dengan menambahkan styling khusus
        const printTable = document.querySelector('.print-grades-table');
        if (printTable) {
            printTable.style.display = 'table';
            printTable.style.width = '100%';
            printTable.style.borderCollapse = 'collapse';
            printTable.style.marginTop = '20px';
            printTable.style.marginBottom = '30px';
            printTable.style.border = '1px solid #ddd';
        }
        
        // Tambahkan styling tambahan untuk header dan sel tabel
        const tableHeaders = document.querySelectorAll('.print-grades-table th');
        tableHeaders.forEach(th => {
            th.style.backgroundColor = '#f4f7f0';
            th.style.color = '#658E36';
            th.style.fontWeight = '600';
            th.style.padding = '10px';
            th.style.textAlign = 'center';
            th.style.border = '1px solid #ddd';
        });
        
        const tableCells = document.querySelectorAll('.print-grades-table td');
        tableCells.forEach(td => {
            td.style.padding = '10px';
            td.style.border = '1px solid #ddd';
            td.style.textAlign = 'center';
        });
        
        // Ubah judul dokumen untuk mencetak
        let originalTitle = document.title;
        document.title = "Detail Nilai Mahasiswa";
        
        // Beri waktu untuk browser memproses tampilan
        setTimeout(function() {
            // Cetak halaman
            window.print();
            
            // Kembalikan judul asli dan sembunyikan template setelah mencetak
            setTimeout(function() {
                document.title = originalTitle;
                if (printTemplate) {
                    printTemplate.style.display = 'none';
                }
            }, 500);
        }, 300);
    }
</script>
@endsection