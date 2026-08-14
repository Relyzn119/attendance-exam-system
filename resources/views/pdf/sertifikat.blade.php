<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Sertifikat Hasil Ujian Diklat - RSU Bunda Thamrin</title>
    <style>
        @page { size: A4 landscape; margin: 0; }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            text-align: center;
            padding: 50px;
            background-color: #fff;
            color: #333;
        }
        .border-container {
            border: 10px double #198754;
            padding: 40px;
            height: 80%;
        }
        .logo-title {
            font-size: 32px;
            color: #198754;
            font-weight: bold;
            letter-spacing: 2px;
            margin-bottom: 5px;
        }
        .sub-title {
            font-size: 18px;
            color: #555;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 15px;
        }
        .cert-no {
            font-size: 14px;
            font-weight: bold;
            color: #444;
            margin-bottom: 30px;
        }
        .peserta-nama {
            font-size: 30px;
            font-weight: bold;
            color: #000;
            text-decoration: underline;
            margin: 15px 0;
            text-transform: uppercase;
        }
        .peserta-detail {
            font-size: 15px;
            color: #555;
            margin-bottom: 25px;
        }
        .score-box {
            display: inline-block;
            font-size: 45px;
            font-weight: bold;
            color: #198754;
            border: 3px solid #198754;
            padding: 10px 30px;
            border-radius: 10px;
            margin: 15px 0;
        }
        .footer-table {
            width: 100%;
            margin-top: 40px;
        }
        .footer-table td {
            text-align: right;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="border-container">
        
        <div class="logo-title">RSU BUNDA THAMRIN</div>
        <div class="sub-title">SERTIFIKAT HASIL UJIAN DIKLAT PEGAWAI</div>
        <div class="cert-no">Nomor: {{ $riwayat->nomor_sertifikat }}</div>

        <p style="font-size: 16px; margin-bottom: 5px;">Diberikan Kepada Peserta:</p>
        <div class="peserta-nama">{{ $riwayat->user->nama }}</div>
        <div class="peserta-detail">NIK: {{ $riwayat->user->nik }} &nbsp;|&nbsp; Email: {{ $riwayat->user->email }}</div>

        <p style="font-size: 15px; line-height: 1.5;">
            Telah dinyatakan <strong>LULUS</strong> dalam mengikuti Ujian Diklat Kehadiran RSU Bunda Thamrin<br>
            dengan perolehan Nilai Akhir:
        </p>

        <div class="score-box">{{ $riwayat->nilai_akhir }}</div>

        <table class="footer-table">
            <tr>
                <td>
                    Medan, {{ \Carbon\Carbon::parse($riwayat->waktu_selesai)->translatedFormat('d F Y') }}<br>
                    <strong>Tim Diklat RSU Bunda Thamrin</strong>
                    <br><br><br><br>
                    ( __________________________ )
                </td>
            </tr>
        </table>

    </div>
</body>
</html>