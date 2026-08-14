<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\TokenAbsensi;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminController extends Controller
{
    // Mengambil data peserta dengan Pagination 10 Data per Halaman
    public function getPesertaList(Request $request)
    {
        $peserta = User::where('role', 'peserta')
            ->with(['berkas', 'token', 'riwayatUjian'])
            ->latest()
            ->paginate(10); // Maksimal 10 data per halaman

        return response()->json($peserta);
    }
    // Tambahkan di dalam class AdminController



    public function generateToken(Request $request, $userId)
    {
        $user = User::findOrFail($userId);

        // Menghasilkan 6 karakter unik (Contoh: B8K2M9)
        $kodeToken = strtoupper(Str::random(6));

        // Simpan/Update token khusus untuk user ini
        $token = TokenAbsensi::updateOrCreate(
            ['user_id' => $user->id],
            [
                'kode_token' => $kodeToken,
                'is_used'    => false,
                'used_at'    => null
            ]
        );

        return response()->json([
            'status'  => 'success',
            'message' => 'Token Absensi & Ujian berhasil dibuat!',
            'token'   => $token->kode_token
        ]);
    }

    // Tambahkan di dalam class AdminController
    public function exportAbsensiPdf()
    {
        $tokens = TokenAbsensi::with('user')
            ->where('is_used', true)
            ->latest('used_at')
            ->get();

        $pdf = Pdf::loadView('pdf.absensi', compact('tokens'))->setPaper('a4', 'portrait');
        return $pdf->download("Daftar_Absensi_Diklat_" . date('Ymd_His') . ".pdf");
    }
}
