<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Daftar Hadir Absensi Diklat RSU Bunda Thamrin</title>
    <style>
        /* MARGIN HALAMAN A4 DISESUAIKAN DENGAN AREA KOP & FOOTER RSU BUNDA THAMRIN */
        @page {
            margin-top: 170px;    /* Memberi jarak aman dari Kop Header atas */
            margin-bottom: 95px;   /* Memberi jarak aman dari Footer Alamat bawah */
            margin-left: 50px;
            margin-right: 50px;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 11px;
            color: #0f172a;
            margin: 0;
            padding: 0;
            background: transparent;
        }
        /* BACKGROUND TEMPLATE PERSISTEN DI SETIAP HALAMAN */
        .bg-container {
            position: fixed;
            top: -170px;    /* Mengimbangi margin-top @page agar background full-screen */
            left: -50px;    /* Mengimbangi margin-left @page */
            width: 210mm;
            height: 297mm;
            z-index: -1000;
        }
        .bg-container img {
            width: 210mm;
            height: 297mm;
        }
        .text-center { text-align: center; }
        .doc-title {
            font-size: 14px;
            font-weight: bold;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 3px;
        }
        .doc-subtitle {
            font-size: 11px;
            font-weight: bold;
            color: #b40d0d;
            margin-bottom: 15px;
            text-transform: uppercase;
        }

        /* TABEL TRANSPARAN AGAR WATERMARK TERLIHAT CLEAR */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
            background: transparent;
        }
        tr {
            page-break-inside: avoid;
            background: transparent;
        }
        th {
            background: transparent;
            color: #0f172a;
            font-weight: bold;
            font-size: 10px;
            text-transform: uppercase;
            padding: 8px 6px;
            border: 1px solid #334155;
            text-align: center;
        }
        td {
            background: transparent;
            border: 1px solid #475569;
            padding: 7px 6px;
            font-size: 10px;
            vertical-align: middle;
            color: #0f172a;
        }
    </style>
</head>
<body>
    <!-- BACKGROUND LANDSCAPE/PORTRAIT FULL PAGE BACKGROUND TEMPLATE -->
    @if(!empty($bgBase64))
    <div class="bg-container">
        <img src="{{ $bgBase64 }}" alt="Background Document">
    </div>
    @endif

    <div class="text-center">
        <div class="doc-title">DAFTAR ABSENSI KEHADIRAN DIKLAT</div>
        <div class="doc-subtitle">RSU BUNDA THAMRIN MEDAN • TANGGAL: {{ \Carbon\Carbon::parse($tanggal)->format('d/m/Y') }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th width="6%">No</th>
                <th width="18%">NIK</th>
                <th width="28%">Nama Peserta</th>
                <th width="16%">No. HP</th>
                <th width="16%">Jabatan</th>
                <th width="16%">Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($absensiList as $idx => $abs)
            <tr>
                <td class="text-center">{{ $idx + 1 }}</td>
                <td class="text-center">{{ $abs->user->nik ?? '-' }}</td>
                <td><strong>{{ $abs->user->nama ?? '-' }}</strong></td>
                <td class="text-center">{{ $abs->user->no_hp ?? '-' }}</td>
                <td class="text-center">{{ $abs->user->jabatan ?? '-' }}</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($abs->created_at)->format('d/m/Y H:i') }}</td>
            </tr>
            @endforeach

            @if(count($absensiList) === 0)
            <tr>
                <td colspan="6" class="text-center" style="padding: 15px; color: #64748b;">
                    Tidak ada peserta yang melakukan absensi pada tanggal {{ \Carbon\Carbon::parse($tanggal)->format('d/m/Y') }}.
                </td>
            </tr>
            @endif
        </tbody>
    </table>
</body>
</html>