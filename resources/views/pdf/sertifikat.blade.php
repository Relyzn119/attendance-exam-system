<!DOCTYPE html>
<html>
<head>
    <title>Sertifikat Hasil Ujian Diklat</title>
    <style>
        body { font-family: sans-serif; text-align: center; padding: 30px; border: 10px solid #0056b3; }
        h1 { color: #0056b3; font-size: 32px; margin-bottom: 5px; }
        h3 { font-size: 18px; color: #555; }
        .nama { font-size: 26px; font-weight: bold; text-decoration: underline; margin: 20px 0; }
        .nilai { font-size: 40px; color: green; font-weight: bold; }
    </style>
</head>
<body>
    <h1>RSU BUNDA THAMRIN</h1>
    <h3>SERTIFIKAT HASIL UJIAN DIKLAT</h3>
    <p>Nomor: {{ $riwayat->nomor_sertifikat }}</p>
    <hr>
    <p>Diberikan Kepada:</p>
    <div class="nama">{{ $riwayat->pegawai->nama_lengkap }}</div>
    <p>NIK/NIP: {{ $riwayat->pegawai->nik_nip }} | Jabatan: {{ $riwayat->pegawai->jabatan }}</p>
    <p>Telah menyelesaikan Ujian Diklat dengan Nilai Akhir:</p>
    <div class="nilai">{{ $riwayat->nilai_akhir }}</div>
    <br><br>
    <p>Medan, {{ date('d F Y') }}</p>
    <p><strong>Tim Diklat RSU Bunda Thamrin</strong></p>
</body>
</html>