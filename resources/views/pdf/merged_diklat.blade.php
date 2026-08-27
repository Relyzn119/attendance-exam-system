<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Arsip Dokumentasi Diklat - {{ $pegawai->nama_lengkap }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 20mm 15mm 20mm 15mm;
        }
        * {
            box-sizing: border-box;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 11pt;
            color: #1e293b;
            margin: 0;
            padding: 0;
            background: #ffffff;
        }

        /* HEADER KOP */
        .kop-header {
            text-align: center;
            border-bottom: 3px double #0f172a;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }
        .kop-title {
            font-size: 18pt;
            font-weight: bold;
            color: #0f172a;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        .kop-subtitle {
            font-size: 11pt;
            font-weight: bold;
            color: #2563eb;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 3px;
        }
        .kop-address {
            font-size: 9pt;
            color: #64748b;
            margin-top: 2px;
        }

        /* SUMMARY INFO BOX */
        .doc-title-box {
            background-color: #f8fafc;
            border: 1px solid #cbd5e1;
            border-left: 5px solid #2563eb;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }
        .doc-label {
            font-size: 9pt;
            font-weight: bold;
            color: #64748b;
            text-transform: uppercase;
            margin-bottom: 3px;
        }
        .doc-main-title {
            font-size: 16pt;
            font-weight: bold;
            color: #0f172a;
            margin-bottom: 8px;
        }
        .doc-desc {
            font-size: 10.5pt;
            color: #334155;
            line-height: 1.5;
        }

        /* INFO TABLE */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        .info-table td {
            padding: 8px 10px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 10pt;
        }
        .info-table td.label-col {
            width: 30%;
            font-weight: bold;
            color: #475569;
            background-color: #f1f5f9;
        }

        /* BERKAS SUMMARY TABLE */
        .berkas-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .berkas-table th {
            background-color: #1e293b;
            color: #ffffff;
            font-size: 9.5pt;
            font-weight: bold;
            text-transform: uppercase;
            padding: 9px;
            border: 1px solid #0f172a;
            text-align: left;
        }
        .berkas-table td {
            padding: 8px 9px;
            border: 1px solid #cbd5e1;
            font-size: 9.5pt;
        }

        /* PAGE BREAK FOR ATTACHMENTS */
        .attachment-page {
            page-break-before: always;
            text-align: center;
        }
        .attachment-header {
            background-color: #f1f5f9;
            border: 1px solid #cbd5e1;
            padding: 10px 15px;
            border-radius: 6px;
            margin-bottom: 15px;
            text-align: left;
        }
        .attachment-title {
            font-size: 12pt;
            font-weight: bold;
            color: #0f172a;
        }
        .attachment-filename {
            font-size: 9pt;
            color: #64748b;
            margin-top: 2px;
        }

        .img-preview {
            max-width: 98%;
            max-height: 720px;
            height: auto;
            border: 1px solid #cbd5e1;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .doc-placeholder-box {
            padding: 40px 20px;
            background-color: #f8fafc;
            border: 2px dashed #cbd5e1;
            border-radius: 8px;
            margin-top: 30px;
        }
    </style>
</head>
<body>

    <!-- HALAMAN 1: COVER & LEMBAR INFORMASI DIKLAT -->
    <div class="kop-header">
        <div class="kop-title">RSU BUNDA THAMRIN</div>
        <div class="kop-subtitle">DOKUMENTASI & ARSIP DIKLAT PEGAWAI</div>
        <div class="kop-address">Jl. Sei Batang Hari No. 28-30, Medan, Sumatera Utara • Telp: (061) 4557000</div>
    </div>

    <div class="doc-title-box">
        <div class="doc-label">Kegiatan Diklat / Pelatihan:</div>
        <div class="doc-main-title">{{ $pegawai->nama_lengkap }}</div>
        <div class="doc-desc">
            <strong>Deskripsi:</strong> {{ $pegawai->unit_departemen ?: '-' }}
        </div>
    </div>

    <table class="info-table">
        <tr>
            <td class="label-col">ID Registrasi Arsip</td>
            <td><strong>{{ $pegawai->nik }}</strong></td>
        </tr>
        <tr>
            <td class="label-col">Tanggal Pelaksanaan</td>
            <td>{{ \Carbon\Carbon::parse($pegawai->tanggal_upload)->translatedFormat('d F Y') }}</td>
        </tr>
        <tr>
            <td class="label-col">Total Berkas Terlampir</td>
            <td><strong>{{ count($berkasList) }} Berkas File</strong></td>
        </tr>
        <tr>
            <td class="label-col">Status Dokumen</td>
            <td><span style="color: #16a34a; font-weight: bold;">Terverifikasi & Terenkripsi</span></td>
        </tr>
    </table>

    <div style="font-weight: bold; font-size: 11pt; color: #0f172a; margin-bottom: 8px;">
        DAFTAR URUTAN BERKAS TERLAMPIR (UPLOAD PERTAMA -> TERAKHIR):
    </div>

    <table class="berkas-table">
        <thead>
            <tr>
                <th width="8%">No</th>
                <th width="25%">Jenis Berkas</th>
                <th width="42%">Nama File</th>
                <th width="25%">Ukuran File</th>
            </tr>
        </thead>
        <tbody>
            @foreach($berkasList as $idx => $b)
            <tr>
                <td style="text-align: center; font-weight: bold;">{{ $idx + 1 }}</td>
                <td><strong>{{ $b->jenis_berkas }}</strong></td>
                <td>{{ $b->nama_file }}</td>
                <td>{{ $b->file_size ?: '-' }}</td>
            </tr>
            @endforeach
            @if(count($berkasList) === 0)
            <tr>
                <td colspan="4" style="text-align: center; color: #94a3b8;">Belum ada berkas terlampir.</td>
            </tr>
            @endif
        </tbody>
    </table>


    <!-- HALAMAN BERIKUTNYA: LAMPIRAN BERKAS DIURUTKAN DARI UPLOAD PERTAMA SAMPAI TERAKHIR -->
    @foreach($berkasList as $idx => $b)
        <div class="attachment-page">
            <div class="attachment-header">
                <div class="attachment-title">Lampiran {{ $idx + 1 }}: {{ $b->jenis_berkas }}</div>
                <div class="attachment-filename">File: {{ $b->nama_file }} | Ukuran: {{ $b->file_size ?: '-' }}</div>
            </div>

            @php
                $cleanPath = str_replace('storage/', '', $b->file_path);
                $fullPath = storage_path('app/public/' . $cleanPath);
                $isImage = false;
                $imageBase64 = '';

                if (file_exists($fullPath)) {
                    $ext = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));
                    if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                        $isImage = true;
                        $imgData = file_get_contents($fullPath);
                        $mime = $ext === 'png' ? 'image/png' : ($ext === 'webp' ? 'image/webp' : 'image/jpeg');
                        $imageBase64 = 'data:' . $mime . ';base64,' . base64_encode($imgData);
                    }
                }
            @endphp

            @if($isImage && !empty($imageBase64))
                <img src="{{ $imageBase64 }}" class="img-preview" alt="Lampiran {{ $b->jenis_berkas }}">
            @else
                <div class="doc-placeholder-box">
                    <div style="font-size: 14pt; font-weight: bold; color: #2563eb; margin-bottom: 8px;">
                        📄 Dokumen PDF / Berkas Digital
                    </div>
                    <div style="font-size: 11pt; font-weight: bold; color: #0f172a;">
                        {{ $b->nama_file }}
                    </div>
                    <div style="font-size: 9.5pt; color: #64748b; margin-top: 6px;">
                        Jenis Berkas: {{ $b->jenis_berkas }} • Terverifikasi Resmi Diklat RSU Bunda Thamrin
                    </div>
                </div>
            @endif
        </div>
    @endforeach

</body>
</html>
