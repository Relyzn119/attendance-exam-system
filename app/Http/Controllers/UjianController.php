<?php

namespace App\Http\Controllers;

use App\Models\BankSoal;
use App\Models\TokenUjian;
use App\Models\RiwayatUjian;
use App\Models\DetailJawabanUjian;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use PDF; // Gunakan package barryvdh/laravel-dompdf untuk PDF Sertifikat

class UjianController extends Controller
{
    // 1. Admin Klik Tombol "Generate Kode Token"
    public function generateToken()
    {
        $token = TokenUjian::create([
            'kode_token' => strtoupper(Str::random(6)), // Menghasilkan 6 karakter unik (ex: X7K9A2)
            'is_active' => true
        ]);

        return response()->json([
            'message' => 'Token berhasil dibuat!',
            'token' => $token->kode_token
        ]);
    }

    // 2. Pegawai Masuk Ujian via Kode Token
    public function mulaiUjian(Request $request)
    {
        $request->validate([
            'nik_nip' => 'required',
            'kode_token' => 'required'
        ]);

        // Cek Token Valid
        $token = TokenUjian::where('kode_token', $request->kode_token)
                           ->where('is_active', true)->first();
        if (!$token) {
            return response()->json(['message' => 'Kode Token Tidak Valid atau Kadaluarsa!'], 400);
        }

        // Cek Data Pegawai
        $pegawai = Pegawai::where('nik_nip', $request->nik_nip)->first();
        if (!$pegawai) {
            return response()->json(['message' => 'Data Pegawai tidak ditemukan!'], 404);
        }

        // Ambil 25 Soal Secara Acak dari total 200 Soal
        $soalAcak = BankSoal::inRandomOrder()->take(25)->get();

        // Buat Sesi Ujian Baru
        $riwayat = RiwayatUjian::create([
            'pegawai_id' => $pegawai->id,
            'token_id' => $token->id,
            'waktu_mulai' => Carbon::now(),
            'status' => 'berlangsung'
        ]);

        // Siapkan Record Detail Jawaban
        foreach ($soalAcak as $soal) {
            DetailJawabanUjian::create([
                'riwayat_ujian_id' => $riwayat->id,
                'soal_id' => $soal->id,
            ]);
        }

        return response()->json([
            'riwayat_id' => $riwayat->id,
            'pegawai' => $pegawai,
            'soal' => $soalAcak->makeHidden('kunci_jawaban') // Sembunyikan kunci jawaban
        ]);
    }

    // 3. Simpan Jawaban & Durasi per Soal + Selesai Ujian
    public function submitUjian(Request $request, $riwayatId)
    {
        $riwayat = RiwayatUjian::findOrFail($riwayatId);
        $jawabanInput = $request->jawaban; // Array dari Vue: [{soal_id: 1, jawaban: 'A', durasi_detik: 45}, ...]

        $benar = 0;
        $salah = 0;

        foreach ($jawabanInput as $item) {
            $soal = BankSoal::find($item['soal_id']);
            $isBenar = ($soal->kunci_jawaban == $item['jawaban']);

            if ($isBenar) $benar++; else $salah++;

            DetailJawabanUjian::where('riwayat_ujian_id', $riwayat->id)
                ->where('soal_id', $item['soal_id'])
                ->update([
                    'jawaban_user' => $item['jawaban'],
                    'is_benar' => $isBenar,
                    'durasi_detik' => $item['durasi_detik'] // Waktu pengerjaan per soal disimpan di sini
                ]);
        }

        // Hitung Nilai Akhir
        $nilai = ($benar / 25) * 100;
        $nomorSertifikat = "CERT/DIKLAT/" . date('Ym') . "/" . sprintf("%04d", $riwayat->id);

        $riwayat->update([
            'jawaban_benar' => $benar,
            'jawaban_salah' => $salah,
            'nilai_akhir' => $nilai,
            'nomor_sertifikat' => $nomorSertifikat,
            'waktu_selesai' => Carbon::now(),
            'status' => 'selesai'
        ]);

        return response()->json([
            'message' => 'Ujian Selesai',
            'nilai' => $nilai,
            'nomor_sertifikat' => $nomorSertifikat
        ]);
    }

    // 4. Download Sertifikat Otomatis
    public function cetakSertifikat($riwayatId)
    {
        $riwayat = RiwayatUjian::with('pegawai')->findOrFail($riwayatId);
        
        // Render PDF menggunakan view sertifikat
        $pdf = PDF::loadView('pdf.sertifikat', compact('riwayat'));
        return $pdf->download("Sertifikat_{$riwayat->pegawai->nama_lengkap}.pdf");
    }

    // 5. Data Monitoring untuk Bagian Bawah Halaman 1 (Admin)
    public function getMonitoringResult()
    {
        $data = RiwayatUjian::with(['pegawai', 'detailJawaban.soal'])
            ->where('status', 'selesai')
            ->latest()
            ->get();

        return response()->json($data);
    }
}