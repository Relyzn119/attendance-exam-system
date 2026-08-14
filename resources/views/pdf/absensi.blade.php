<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Daftar Hadir Absensi Diklat RSU Bunda Thamrin</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 12px; color: #333; }
        .text-center { text-align: center; }
        .title { font-size: 18px; font-weight: bold; color: #198754; margin-bottom: 2px; }
        .sub-title { font-size: 13px; font-weight: bold; margin-bottom: 15px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #666; padding: 7px 10px; text-align: left; }
        th { background-color: #198754; color: #fff; text-align: center; }
    </style>
</head>
<body>
    <div class="text-center">
        <div class="title">DAFTAR ABSENSI KEHADIRAN UJIAN DIKLAT</div>
        <div class="sub-title">RSU BUNDA THAMRIN MEDAN</div>
    </div>
    <hr>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="18%">NIK</th>
                <th width="27%">Nama Peserta</th>
                <th width="8%">JK</th>
                <th width="17%">No. HP</th>
                <th width="25%">Waktu Absensi (Input Token)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tokens as $idx => $t)
            <tr>
                <td class="text-center">{{ $idx + 1 }}</td>
                <td>{{ $t->user->nik }}</td>
                <td><strong>{{ $t->user->nama }}</strong></td>
                <td class="text-center">{{ $t->user->jenis_kelamin }}</td>
                <td>{{ $t->user->no_hp }}</td>
                <td>{{ \Carbon\Carbon::parse($t->used_at)->translatedFormat('d F Y, H:i') }} WIB</td>
            </tr>
            @endforeach
            @if(count($tokens) === 0)
            <tr>
                <td colspan="6" class="text-center">Belum ada peserta yang menginput token / hadir.</td>
            </tr>
            @endif
        </tbody>
    </table>
</body>
</html>