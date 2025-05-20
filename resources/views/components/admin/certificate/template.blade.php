<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sertifikat</title>
    <style>
        body {
            text-align: center;
            font-family: 'DejaVu Sans', sans-serif;
        }
        .sertifikat {
            border: 10px solid #ccc;
            padding: 50px;
            margin: 50px;
        }
        .judul {
            font-size: 30px;
            font-weight: bold;
        }
        .nama {
            font-size: 24px;
            margin-top: 20px;
        }
        .kode {
            font-size: 16px;
            margin-top: 10px;
            color: gray;
        }
    </style>
</head>
<body>
    <div class="sertifikat">
        <div class="judul">SERTIFIKAT</div>
        <div class="nama">{{ $nama }}</div>
        <div class="kode">Kode Sertifikat: {{ $kode }}</div>
    </div>
</body>
</html>
