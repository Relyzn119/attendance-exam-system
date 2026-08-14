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
    // 1. Validasi Token Absensi & Mulai Ujian (Ambil 25 Soal Terpilih)
    public function startExam(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'token'   => 'required'
        ]);

        // Cek Keberadaan Token
        $token = TokenAbsensi::where('user_id', $request->user_id)
            ->where('kode_token', strtoupper($request->token))
            ->first();

        if (!$token) {
            return response()->json(['message' => 'Kode Token tidak ditemukan atau tidak sesuai!'], 400);
        }

        if ($token->is_used) {
            return response()->json(['message' => 'Kode Token ini sudah pernah digunakan untuk ujian!'], 400);
        }

        // Ambil 25 Soal yang dipilih Admin (is_selected = true)
        $soalList = BankSoal::where('is_selected', true)->get();
        if ($soalList->count() === 0) {
            return response()->json(['message' => 'Admin belum memilih 25 Soal Ujian! Mohon hubungi Admin.'], 400);
        }

        // Tandai Token Sudah Digunakan (Absensi Terverifikasi)
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
            'status'     => 'success',
            'riwayat_id' => $riwayat->id,
            'soal'       => $soalList->makeHidden('kunci_jawaban') // Sembunyikan kunci jawaban dari client
        ]);
    }

    // 2. Submit Jawaban & Hitung Nilai Akhir
    public function submitExam(Request $request, $riwayatId)
    {
        $riwayat = RiwayatUjian::findOrFail($riwayatId);
        $jawabanUser = $request->jawaban; // Array: [{soal_id: 1, jawaban: 'A'}, ...]

        $benar = 0;
        $salah = 0;

        foreach ($jawabanUser as $item) {
            $soal = BankSoal::find($item['soal_id']);
            $isBenar = ($soal && $soal->kunci_jawaban == $item['jawaban']);

            if ($isBenar) $benar++;
            else $salah++;

            DetailJawabanUjian::where('riwayat_ujian_id', $riwayat->id)
                ->where('soal_id', $item['soal_id'])
                ->update([
                    'jawaban_user' => $item['jawaban'],
                    'is_benar'     => $isBenar,
                ]);
        }

        $totalSoal = $riwayat->total_soal > 0 ? $riwayat->total_soal : 25;
        $nilai = ($benar / $totalSoal) * 100;
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
    // Tambahkan di dalam class UjianController

    public function getReviewJawaban($riwayatId)
    {
        $riwayat = RiwayatUjian::with(['detailJawaban.soal', 'user'])->findOrFail($riwayatId);

        return response()->json([
            'status'  => 'success',
            'riwayat' => $riwayat
        ]);
    }


// Tambahkan di dalam class UjianController
public function cetakSertifikat($riwayatId)
{
    $riwayat = RiwayatUjian::with('user')->findOrFail($riwayatId);

    // Keamanan: Tolak jika status ujian belum selesai
    if ($riwayat->status !== 'selesai') {
        return response()->json([
            'message' => 'Sertifikat belum tersedia karena Anda belum menyelesaikan ujian!'
        ], 403);
    }

    // Render View ke PDF
    $pdf = Pdf::loadView('pdf.sertifikat', compact('riwayat'))->setPaper('a4', 'landscape');
    
    return $pdf->download("Sertifikat_Diklat_{$riwayat->user->nik}.pdf");
}
}
