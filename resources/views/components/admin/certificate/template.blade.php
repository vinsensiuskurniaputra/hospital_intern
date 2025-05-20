<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Sertifikat Magang Rumah Sakit</title>
    <style>
        @page {
            margin: 0;
            size: A4 landscape;
        }

        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background: #fff;
            position: relative;
        }

        .certificate-container {
            width: 100%;
            height: 100%;
            min-height: 100%;
            padding: 40px;
            box-sizing: border-box;
            position: relative;
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
            opacity: 0.05;
            font-size: 150px;
            color: #2D5A27;
            z-index: 0;
            pointer-events: none;
        }

        .content {
            position: relative;
            z-index: 1;
            padding: 60px;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .logo {
            width: 150px;
            margin-bottom: 30px;
        }

        .certificate-title {
            font-size: 54px;
            color: #2D5A27;
            margin-bottom: 20px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 6px;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
        }

        .certificate-subtitle {
            font-size: 28px;
            color: #4F9546;
            margin-bottom: 50px;
            letter-spacing: 2px;
        }

        .recipient-name {
            font-size: 42px;
            color: #333;
            margin: 30px 0;
            border-bottom: 3px solid #4F9546;
            padding-bottom: 10px;
            font-weight: bold;
        }

        .certificate-text {
            font-size: 20px;
            color: #444;
            line-height: 1.8;
            margin: 25px 0;
            max-width: 800px;
            text-align: justify;
            text-justify: inter-word;
        }

        .date-location {
            margin-top: 50px;
            font-size: 18px;
            color: #555;
            font-style: italic;
        }

        .signature-section {
            margin-top: 80px;
            display: flex;
            justify-content: space-around;
            width: 100%;
            max-width: 800px;
        }

        .signature {
            text-align: center;
            margin: 0 40px;
        }

        .signature-line {
            width: 250px;
            border-bottom: 2px solid #333;
            margin: 10px auto;
        }

        .signature-name {
            font-size: 18px;
            font-weight: bold;
            color: #333;
            margin-top: 10px;
        }

        .signature-title {
            font-size: 16px;
            color: #555;
            margin-top: 5px;
        }

        .certificate-footer {
            position: absolute;
            bottom: 20px;
            left: 0;
            right: 0;
            text-align: center;
        }

        .certificate-number {
            font-size: 16px;
            color: #666;
            padding: 8px 15px;
            border: 1px solid #ddd;
            display: inline-block;
            background: rgba(255, 255, 255, 0.9);
        }
    </style>
</head>

<body>
    <div class="certificate-container">
        <div class="border-pattern"></div>
        <div class="watermark">CERTIFIED</div>
        <div class="content">
            <img src="{{ public_path('images/logo.png') }}" alt="Hospital Logo" class="logo">

            <div class="certificate-title">Sertifikat</div>
            <div class="certificate-subtitle">Program Magang Rumah Sakit</div>

            <div class="certificate-text">
                Dengan ini menyatakan bahwa
            </div>

            <div class="recipient-name">
                {{ $nama }}
            </div>

            <div class="certificate-text">
                telah berhasil menyelesaikan Program Magang Rumah Sakit
                dengan penuh dedikasi dan profesionalisme sesuai dengan
                standar kompetensi yang ditetapkan.<br>
                Periode Magang: {{ $periode ?? 'Januari - Juni 2024' }}
            </div>

            <div class="date-location">
                Diberikan pada tanggal {{ Carbon\Carbon::now()->isoFormat('D MMMM Y') }}
                <br>
                di Malang, Indonesia
            </div>

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
                <div class="certificate-number">
                    Nomor Sertifikat: {{ $kode }}
                </div>
            </div>
        </div>
    </div>
</body>

</html>
