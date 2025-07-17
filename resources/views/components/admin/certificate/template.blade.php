<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Sertifikat Magang Rumah Sakit</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 0;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            height: 1122px;
            /* Tinggi A4 pada 96 DPI */
            width: 794px;
            /* Lebar A4 pada 96 DPI */
            font-family: 'Arial', sans-serif;
            background: #fff;
            box-sizing: border-box;
        }

        .certificate-container {
            position: relative;
            width: 100%;
            height: 100%;
            padding: 30px;
            box-sizing: border-box;
            overflow: hidden;
        }

        .border-pattern {
            position: absolute;
            top: 20px;
            left: 20px;
            right: 20px;
            bottom: 20px;
            border: 2px solid #2D5A27;
            box-shadow: 0 0 0 10px #fff, 0 0 0 12px #4F9546;
        }

        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 120px;
            color: #2D5A27;
            opacity: 0.05;
            z-index: 0;
            pointer-events: none;
            white-space: nowrap;
        }

        .content {
            position: relative;
            z-index: 1;
            padding: 20px;
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
            box-sizing: border-box;
        }

        .logo {
            width: 80px;
            margin: 0 auto 20px;
        }

        .certificate-title {
            font-size: 48px;
            color: #2D5A27;
            font-weight: bold;
            text-transform: uppercase;
            margin: 10px 0;
        }

        .certificate-subtitle {
            font-size: 22px;
            color: #4F9546;
            margin-bottom: 20px;
        }

        .certificate-text {
            font-size: 14px;
            color: #444;
            line-height: 1.6;
            margin: 10px 0;
            max-width: 720px;
            margin-left: auto;
            margin-right: auto;
            text-align: justify;
        }

        .recipient-name {
            font-size: 36px;
            color: #333;
            font-weight: bold;
            margin: 20px 0 10px;
            border-bottom: 3px solid #4F9546;
            padding-bottom: 5px;
            display: inline-block;
        }

        .date-location {
            font-size: 16px;
            color: #555;
            margin-top: 30px;
            font-style: italic;
        }

        .signature-section {
            margin-top: 30px;
            display: flex;
            justify-content: space-around;
            gap: 20px;
            flex-wrap: wrap;
        }

        .signature {
            text-align: center;
        }

        .signature-line {
            width: 180px;
            border-bottom: 2px solid #333;
            margin: 10px auto;
        }

        .signature-name {
            font-size: 16px;
            font-weight: bold;
            margin-top: 5px;
        }

        .signature-title {
            font-size: 14px;
            color: #555;
        }

        .certificate-footer {
            font-size: 12px;
            margin-top: 10px;
            color: #666;
        }

        .certificate-number {
            display: inline-block;
            padding: 5px 10px;
            border: 1px solid #ccc;
            background: #f9f9f9;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="certificate-container">
        <div class="content">
            <div>
                <img src="{{ public_path('images/logorevisi.png') }}" alt="Hospital Logo" class="logo">
                <div class="certificate-title">Sertifikat</div>
                <div class="certificate-subtitle">Program Magang Rumah Sakit</div>

                <div class="certificate-text">Dengan ini menyatakan bahwa</div>
                <div class="recipient-name">{{ $nama }}</div>
                <div class="certificate-text">
                    telah berhasil menyelesaikan Program Magang Rumah Sakit dengan penuh dedikasi dan profesionalisme
                    sesuai dengan standar kompetensi yang ditetapkan.<br>
                    Periode Magang: {{ $periode ?? 'Januari - Juni 2024' }}
                </div>

                <div class="date-location">
                    Diberikan pada tanggal {{ \Carbon\Carbon::now()->isoFormat('D MMMM Y') }}<br>
                    di Malang, Indonesia
                </div>
            </div>

            <div>
                <div class="signature-section">
                    <div class="signature">
                        <div class="signature-line"></div>
                        <div class="signature-name">Dr. Jane Doe</div>
                        <div class="signature-title">Direktur Rumah Sakit</div>
                    </div>
                    <div class="signature">
                        <div class="signature-line"></div>
                        <div class="signature-name">Dr. John Smith</div>
                        <div class="signature-title">Kepala Program Magang</div>
                    </div>
                </div>

                <div class="certificate-footer">
                    <div class="certificate-number">Nomor Sertifikat: {{ $kode }}</div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
