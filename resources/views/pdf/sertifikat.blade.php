<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Sertifikat Pelatihan - RSU Bunda Thamrin</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 0;
        }
        * {
            box-sizing: border-box;
        }
        body {
            font-family: 'Times New Roman', Times, 'Georgia', serif;
            text-align: center;
            margin: 0;
            padding: 0;
            background-color: #ffffff;
            color: #111111;
            position: relative;
            width: 100%;
            height: 100%;
        }

        /* BACKGROUND WATERMARK (public/images/bg-Sertifikat.png) */
        .watermark-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1000;
            text-align: center;
        }
        .watermark-img {
            width: 100%;
            height: 100%;
        }

        /* HEADER DEKORATIF (GAMBAR 2) */
        .header-bar-container {
            position: relative;
            width: 100%;
            padding-top: 25px;
            margin-bottom: 25px;
        }
        .header-stripe {
            background-color: #2b2b2b;
            height: 38px;
            width: 100%;
            border-top: 3px solid #d4af37;
            border-bottom: 3px solid #d4af37;
            position: relative;
        }
        .header-logo-wrapper {
            position: absolute;
            top: 10px;
            left: 50%;
            margin-left: -40px;
            z-index: 10;
        }
        .header-logo-img {
            width: 80px;
            height: 80px;
        }

        /* SERTIFIKAT CONTENT */
        .content-container {
            padding: 0 60px;
            margin-top: 45px;
        }

        .cert-title {
            font-family: 'Times New Roman', Times, serif;
            font-size: 42px;
            font-weight: bold;
            letter-spacing: 2px;
            color: #111111;
            margin-bottom: 6px;
            text-transform: uppercase;
        }

        .cert-diberikan {
            font-family: 'Georgia', 'Times New Roman', serif;
            font-size: 22px;
            font-style: italic;
            color: #333333;
            margin-bottom: 12px;
        }

        .peserta-nama {
            font-family: 'Times New Roman', Times, serif;
            font-size: 34px;
            font-weight: bold;
            color: #000000;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 12px 0 16px 0;
        }

        .partisipasi-label {
            font-family: 'Times New Roman', Times, serif;
            font-size: 19px;
            color: #222222;
            margin-bottom: 4px;
        }

        .partisipasi-role {
            font-family: 'Times New Roman', Times, serif;
            font-size: 22px;
            font-weight: bold;
            letter-spacing: 1.5px;
            color: #111111;
            margin-bottom: 14px;
            text-transform: uppercase;
        }

        .pelatihan-nama {
            font-family: 'Times New Roman', Times, serif;
            font-size: 26px;
            font-weight: bold;
            letter-spacing: 1.5px;
            color: #111111;
            margin-bottom: 25px;
            text-transform: uppercase;
        }

        /* BOTTOM STRIPE DEKORATIF */
        .footer-stripe-container {
            width: 100%;
            margin: 10px 0 12px 0;
        }
        .footer-stripe {
            background-color: #2b2b2b;
            height: 28px;
            width: 100%;
            border-top: 3px solid #d4af37;
            border-bottom: 3px solid #d4af37;
        }

        .tanggal-pelaksanaan {
            font-family: 'Times New Roman', Times, serif;
            font-size: 16px;
            color: #222222;
            margin-bottom: 25px;
        }

        /* SIGNATURE TABLE (GAMBAR 2) */
        .signature-table {
            width: 90%;
            margin: 0 auto;
            border-collapse: collapse;
        }
        .signature-table td {
            width: 50%;
            vertical-align: top;
            text-align: center;
        }

        .sig-title {
            font-family: 'Times New Roman', Times, serif;
            font-size: 16px;
            font-weight: bold;
            color: #111111;
            margin-bottom: 5px;
        }

        .sig-image-box {
            height: 65px;
            margin: 2px 0;
        }
        .sig-image-box img {
            max-height: 60px;
            width: auto;
        }

        .sig-name {
            font-family: 'Times New Roman', Times, serif;
            font-size: 15px;
            color: #111111;
        }
        .sig-name-bold {
            font-weight: bold;
            text-transform: uppercase;
        }
    </style>
</head>
<body>

    <!-- 1. WATERMARK BACKGROUND (public/images/bg-Sertifikat.png) -->
    @php
        $useBgWatermark = isset($setting->use_bg_watermark) ? (bool)$setting->use_bg_watermark : true;
    @endphp
    @if($useBgWatermark)
    <div class="watermark-container">
        @php
            $bgCertPath = public_path('images/bg-Sertifikat.png');
            if (!file_exists($bgCertPath)) {
                $bgCertPath = public_path('images/bg-sertifikat.png');
            }
            $bgCertBase64 = file_exists($bgCertPath)
                ? 'data:image/png;base64,' . base64_encode(file_get_contents($bgCertPath))
                : '';
        @endphp
        @if(!empty($bgCertBase64))
            <img class="watermark-img" src="{{ $bgCertBase64 }}" alt="Background Sertifikat RSU Bunda Thamrin">
        @endif
    </div>
    @endif

    <!-- 2. HEADER DEKORATIF DENGAN LOGO EMBLEM (public/images/logo-rsubt.png) -->
    <div class="header-bar-container">
        <div class="header-stripe"></div>
        <div class="header-logo-wrapper">
            @php
                $logoPath = public_path('images/logo-rsubt.png');
                $logoBase64 = file_exists($logoPath)
                    ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath))
                    : '';
            @endphp
            @if(!empty($logoBase64))
                <img class="header-logo-img" src="{{ $logoBase64 }}" alt="Logo RSU Bunda Thamrin">
            @endif
        </div>
    </div>

    <!-- 3. ISI SERTIFIKAT (GAMBAR 2) -->
    <div class="content-container">
        
        <div class="cert-title">SERTIFIKAT</div>
        <div class="cert-diberikan">Diberikan Kepada :</div>

        <div class="peserta-nama">{{ strtoupper($riwayat->user->nama) }}</div>

        <div class="partisipasi-label">Atas Partisipasinya Sebagai :</div>
        <div class="partisipasi-role">PESERTA</div>

        <div class="pelatihan-nama">{{ $judulPelatihan ?? 'PELATIHAN BANTUAN HIDUP DASAR' }}</div>

        <!-- STRIPE BOTTOM DEKORATIF -->
        <div class="footer-stripe-container">
            <div class="footer-stripe"></div>
        </div>

        <div class="tanggal-pelaksanaan">
            Diselenggarakan pada tanggal {{ \Carbon\Carbon::parse($riwayat->waktu_selesai ?? now())->translatedFormat('d F Y') }}
        </div>

        <!-- 4. AREA TANDA TANGAN (GAMBAR 2) -->
        @php
            $namaDirektur = (!empty($setting) && !empty($setting->nama_direktur)) ? $setting->nama_direktur : 'dr. Iskandar Candra, M.Kes, FISQua, KMK, CHQP';
            $namaPembicara = (!empty($setting) && !empty($setting->nama_pembicara)) ? $setting->nama_pembicara : 'JUPENTIUS SITUMORANG';
            $tipeTtd = (!empty($setting) && !empty($setting->tipe_ttd)) ? $setting->tipe_ttd : 'digital';

            // Direktur TTD
            $ttdDirekturBase64 = '';
            if ($tipeTtd === 'digital') {
                if (!empty($setting) && !empty($setting->ttd_direktur) && file_exists(public_path($setting->ttd_direktur))) {
                    $ext = pathinfo($setting->ttd_direktur, PATHINFO_EXTENSION);
                    $mime = ($ext === 'svg') ? 'image/svg+xml' : 'image/' . $ext;
                    $ttdDirekturBase64 = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents(public_path($setting->ttd_direktur)));
                } else {
                    $ttdDirekturSvg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 70" width="160" height="56">
                      <path d="M 20,48 C 22,25 28,15 32,28 C 36,42 35,55 40,46 C 45,35 48,25 54,40 C 58,48 62,32 68,36 C 74,40 78,48 84,42 C 90,36 94,44 100,38 M 25,40 L 90,36 M 68,40 C 85,35 110,30 135,28 C 145,27 155,30 160,32" fill="none" stroke="#111111" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>';
                    $ttdDirekturBase64 = 'data:image/svg+xml;base64,' . base64_encode($ttdDirekturSvg);
                }
            }

            // Pembicara TTD
            $ttdPembicaraBase64 = '';
            if ($tipeTtd === 'digital') {
                if (!empty($setting) && !empty($setting->ttd_pembicara) && file_exists(public_path($setting->ttd_pembicara))) {
                    $ext = pathinfo($setting->ttd_pembicara, PATHINFO_EXTENSION);
                    $mime = ($ext === 'svg') ? 'image/svg+xml' : 'image/' . $ext;
                    $ttdPembicaraBase64 = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents(public_path($setting->ttd_pembicara)));
                } else {
                    $ttdPembicaraSvg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 70" width="160" height="56">
                      <path d="M 18,38 C 22,18 32,12 30,32 C 28,45 38,22 44,35 C 48,42 50,25 56,32 C 62,38 68,26 76,34 C 82,38 88,28 96,36 C 104,42 112,32 122,38 C 130,44 140,34 150,36 M 65,52 C 75,54 90,55 98,52" fill="none" stroke="#111111" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>';
                    $ttdPembicaraBase64 = 'data:image/svg+xml;base64,' . base64_encode($ttdPembicaraSvg);
                }
            }
        @endphp

        <table class="signature-table">
            <tr>
                <td>
                    <div class="sig-title">Direktur RSU Bunda Thamrin</div>
                    <div class="sig-image-box">
                        @if($tipeTtd === 'digital' && !empty($ttdDirekturBase64))
                            <img src="{{ $ttdDirekturBase64 }}" alt="Tanda Tangan Direktur">
                        @endif
                    </div>
                    <div class="sig-name">{{ $namaDirektur }}</div>
                </td>
                <td>
                    <div class="sig-title">Pembicara</div>
                    <div class="sig-image-box">
                        @if($tipeTtd === 'digital' && !empty($ttdPembicaraBase64))
                            <img src="{{ $ttdPembicaraBase64 }}" alt="Tanda Tangan Pembicara">
                        @endif
                    </div>
                    <div class="sig-name">{{ $namaPembicara }}</div>
                </td>
            </tr>
        </table>

    </div>

</body>
</html>