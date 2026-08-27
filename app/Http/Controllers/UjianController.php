<?php

namespace App\Http\Controllers;

use App\Models\BankSoal;
use App\Models\TokenAbsensi;
use App\Models\RiwayatUjian;
use App\Models\DetailJawabanUjian;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class UjianController extends Controller
{
    // 1. Validasi Token Absensi & Mulai Ujian (Ambil Soal Terpilih)
    // 1. Validasi Token Absensi & Mulai Ujian (Ambil Soal Terpilih & Durasi)
    public function startExam(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'token'   => 'required'
        ]);

        // Cek Keberadaan Token
        $token = TokenAbsensi::where('user_id', $request->user_id)
            ->where('kode_token', strtoupper(trim($request->token)))
            ->first();

        if (!$token) {
            return response()->json(['message' => 'Kode Token tidak ditemukan atau tidak sesuai!'], 400);
        }

        // Simpan Log Absensi Kehadiran Peserta (Setiap kali token valid diinput)
        \App\Models\Absensi::create([
            'user_id'    => $request->user_id,
            'token_id'   => $token->id,
            'tipe_token' => $token->tipe_token ?? 'ujian',
        ]);

        // KONDISI 1: JIKA TOKEN HARUSNYA HANYA ABSENSI SAJA
        if ($token->tipe_token === 'absensi') {
            return response()->json([
                'status'     => 'success',
                'tipe_token' => 'absensi',
                'message'    => 'Absensi Berhasil Dicatat!'
            ]);
        }

        // KONDISI 2: JIKA TOKEN UNTUK MASUK UJIAN
        if ($token->is_used) {
            return response()->json(['message' => 'Kode Token ini sudah pernah digunakan untuk ujian!'], 400);
        }

        // Ambil Soal yang dipilih Admin (is_selected = true)
        $soalList = BankSoal::where('is_selected', true)->get();
        if ($soalList->count() === 0) {
            return response()->json(['message' => 'Admin belum memilih Soal Ujian! Mohon hubungi Admin.'], 400);
        }

        // Tandai Token Sudah Digunakan untuk Ujian
        $token->update([
            'is_used' => true,
            'used_at' => now()
        ]);

        // Buat Sesi Riwayat Ujian
        $riwayat = RiwayatUjian::create([
            'user_id'     => $request->user_id,
            'token_id'    => $token->id,
            'total_soal'  => $soalList->count(),
            'waktu_mulai' => now(),
            'status'      => 'berlangsung'
        ]);

        // Buat Placeholder Detail Jawaban
        foreach ($soalList as $soal) {
            DetailJawabanUjian::create([
                'riwayat_ujian_id' => $riwayat->id,
                'soal_id'          => $soal->id,
            ]);
        }

        return response()->json([
            'status'       => 'success',
            'tipe_token'   => 'ujian',
            'riwayat_id'   => $riwayat->id,
            'durasi_menit' => $token->durasi_menit ?? 60,
            'soal'         => $soalList->makeHidden('kunci_jawaban')
        ]);
    }

    // 2. Submit Jawaban & Hitung Nilai Akhir
    public function submitExam(Request $request, $riwayatId)
    {
        $riwayat = RiwayatUjian::findOrFail($riwayatId);
        $jawabanUser = $request->jawaban ?? [];

        $benar = 0;
        $salah = 0;

        foreach ($jawabanUser as $item) {
            $soal = BankSoal::find($item['soal_id']);
            $userAns = in_array($item['jawaban'] ?? '', ['A', 'B', 'C', 'D']) ? $item['jawaban'] : null;
            $isBenar = ($soal && $userAns && $soal->kunci_jawaban == $userAns);

            if ($isBenar) $benar++;
            else $salah++;

            DetailJawabanUjian::where('riwayat_ujian_id', $riwayat->id)
                ->where('soal_id', $item['soal_id'])
                ->update([
                    'jawaban_user' => $userAns,
                    'is_benar'     => $isBenar,
                ]);
        }

        $totalSoal = $riwayat->total_soal > 0 ? $riwayat->total_soal : count($jawabanUser);
        $nilai = $totalSoal > 0 ? round(($benar / $totalSoal) * 100, 2) : 0;
        $noSertifikat = "CERT/DIKLAT/" . date('Ym') . "/" . sprintf("%04d", $riwayat->id);

        $riwayat->update([
            'jawaban_benar'    => $benar,
            'jawaban_salah'    => $salah,
            'nilai_akhir'      => $nilai,
            'nomor_sertifikat' => $noSertifikat,
            'waktu_selesai'    => now(),
            'status'           => 'selesai'
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Ujian berhasil diselesaikan!',
            'nilai'   => $nilai
        ]);
    }

    // 3. Review Jawaban Ujian
    public function getReviewJawaban($riwayatId)
    {
        $riwayat = RiwayatUjian::with(['detailJawaban.soal', 'user'])->findOrFail($riwayatId);

        return response()->json([
            'status'  => 'success',
            'riwayat' => $riwayat
        ]);
    }

    // 4. Cetak Sertifikat Hasil Ujian
    public function cetakSertifikat($riwayatId)
    {
        $riwayat = RiwayatUjian::with('user')->findOrFail($riwayatId);

        if ($riwayat->status !== 'selesai') {
            return response()->json([
                'message' => 'Sertifikat belum tersedia karena Anda belum menyelesaikan ujian!'
            ], 403);
        }

        $pdf = Pdf::loadView('pdf.sertifikat', compact('riwayat'))->setPaper('a4', 'landscape');

        return $pdf->download("Sertifikat_Diklat_{$riwayat->user->nik}.pdf");
    }
}
