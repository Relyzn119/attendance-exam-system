<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\BerkasPeserta;
use Illuminate\Http\Request;
use App\Models\TokenAbsensi;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class AdminController extends Controller
{
    // 1. Mengambil data peserta dengan Pagination 10 Data per Halaman
    public function getPesertaList(Request $request)
    {
        $peserta = User::where('role', 'peserta')
            ->with(['berkas', 'token', 'riwayatUjian'])
            ->latest()
            ->paginate(10); // Maksimal 10 data per halaman

        return response()->json($peserta);
    }

    // 2. Update Data Peserta (SESI 1)
    public function updatePeserta(Request $request, $id)
    {
        $user = User::where('role', 'peserta')->findOrFail($id);

        $validated = $request->validate([
            'nama'          => 'required|string|max:255',
            'nik'           => 'nullable|string|max:50|unique:users,nik,' . $id,
            'jenis_kelamin' => 'nullable|in:L,P',
            'no_hp'         => 'nullable|string|max:50',
            'email'         => 'required|email|max:255|unique:users,email,' . $id,
            'alamat'        => 'nullable|string',
        ]);

        $user->update($validated);

        return response()->json([
            'status'  => 'success',
            'message' => 'Data peserta berhasil diperbarui!',
            'data'    => $user->load(['berkas', 'token', 'riwayatUjian'])
        ]);
    }

    // 3. Delete Data Peserta beserta Berkas Fisik (SESI 1)
    public function deletePeserta($id)
    {
        $user = User::where('role', 'peserta')->with('berkas')->findOrFail($id);

        // Hapus file fisik berkas peserta dari storage jika ada
        foreach ($user->berkas as $b) {
            $cleanRelativePath = str_replace(['storage/', 'public/'], '', $b->file_path);
            if (Storage::disk('public')->exists($cleanRelativePath)) {
                Storage::disk('public')->delete($cleanRelativePath);
            }
        }

        // Hapus data user dari DB
        $user->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Data peserta & seluruh berkasnya berhasil dihapus!'
        ]);
    }

   // DOWNLOAD ALL BERKAS (.ZIP) PESERTA
// DOWNLOAD ALL BERKAS (.ZIP) PESERTA
    public function downloadZipPeserta($id)
    {
        try {
            $peserta = User::where('role', 'peserta')->with('berkas')->find($id);

            if (!$peserta) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data peserta tidak ditemukan'
                ], 404);
            }

            if ($peserta->berkas->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Peserta ini belum memiliki berkas dokumen terunggah'
                ], 400);
            }

            // Folder penyimpanan ZIP Sementara
            $tempDir = storage_path('app/temp');
            if (!file_exists($tempDir)) {
                mkdir($tempDir, 0755, true);
            }

            $cleanName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $peserta->nama);
            $zipFileName = 'Berkas-Peserta_' . $cleanName . '_' . time() . '.zip';
            $zipPath = $tempDir . '/' . $zipFileName;

            $zip = new \ZipArchive();

            if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === TRUE) {
                $fileCount = 0;

                foreach ($peserta->berkas as $berkas) {
                    // Bersihkan relative path dari storage/ atau public/
                    $cleanRelativePath = ltrim(str_replace(['storage/', 'public/'], '', $berkas->file_path), '/');
                    
                    // Ambil path absolut fisik file dari disk public
                    $fullPath = Storage::disk('public')->path($cleanRelativePath);

                    // Fallback jika tidak ditemukan dengan Storage disk
                    if (!file_exists($fullPath)) {
                        $fullPath = storage_path('app/public/' . $cleanRelativePath);
                    }

                    // Pastikan file fisik benar-benar ada sebelum dimasukkan ke ZIP
                    if (file_exists($fullPath) && is_file($fullPath)) {
                        $jenisClean = preg_replace('/[^a-zA-Z0-9_-]/', '_', $berkas->jenis_berkas ?? 'Berkas');
                        $ext = pathinfo($fullPath, PATHINFO_EXTENSION);
                        $originalName = pathinfo($berkas->nama_file ?? basename($fullPath), PATHINFO_FILENAME);
                        
                        // Nama file di dalam archive ZIP
                        $entryName = $jenisClean . '_' . $originalName . '.' . ($ext ?: 'pdf');

                        $zip->addFile($fullPath, $entryName);
                        $fileCount++;
                    }
                }

                $zip->close();

                if ($fileCount === 0) {
                    if (file_exists($zipPath)) {
                        @unlink($zipPath);
                    }
                    return response()->json([
                        'success' => false,
                        'message' => 'File dokumen fisik tidak ditemukan di server'
                    ], 404);
                }

                // Mencegah output buffer PHP merusak binary ZIP
                if (ob_get_level()) {
                    ob_end_clean();
                }

                return response()->download($zipPath, $zipFileName, [
                    'Content-Type' => 'application/zip',
                    'Content-Disposition' => 'attachment; filename="' . $zipFileName . '"',
                    'Pragma' => 'no-cache',
                    'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
                    'Expires' => '0'
                ])->deleteFileAfterSend(true);
            }

            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat file ZIP'
            ], 500);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error download ZIP: ' . $e->getMessage(),
            ], 500);
        }
    }
    // 5. Generate Token Absensi Ujian
   // 5. Generate Token Absensi Ujian dengan Pengaturan Durasi
    public function generateToken(Request $request, $userId)
    {
        $user = User::findOrFail($userId);

        $kodeToken = strtoupper(Str::random(6));
        $durasi = $request->input('durasi_menit', 60); // Default 60 Menit jika tidak diisi

        $token = TokenAbsensi::updateOrCreate(
            ['user_id' => $user->id],
            [
                'kode_token'   => $kodeToken,
                'durasi_menit' => $durasi,
                'is_used'      => false,
                'used_at'      => null
            ]
        );

        return response()->json([
            'status'  => 'success',
            'message' => 'Token Absensi & Ujian berhasil dibuat!',
            'token'   => $token->kode_token,
            'durasi'  => $token->durasi_menit
        ]);
    }

    // 6. Export PDF Absensi
    public function exportAbsensiPdf()
    {
        $pesertas = User::where('role', 'peserta')
            ->with('token')
            ->orderBy('nama', 'asc')
            ->get();

        $imagePath = public_path('images/BackgroundDocument.png');
        $bgBase64 = '';
        if (file_exists($imagePath)) {
            $bgData = file_get_contents($imagePath);
            $bgBase64 = 'data:image/png;base64,' . base64_encode($bgData);
        }

        $pdf = Pdf::loadView('pdf.absensi', compact('pesertas', 'bgBase64'))->setPaper('a4', 'portrait');
        
        return $pdf->download("Daftar_Absensi_Diklat_" . date('Ymd_His') . ".pdf");
    }
     public function previewBerkas($id)
    {
        $berkas = BerkasPeserta::find($id);

        if (!$berkas) {
            return response()->json([
                'success' => false,
                'message' => 'Data berkas tidak ditemukan.'
            ], 404);
        }

        // Bersihkan path file dari prefix 'storage/' atau 'public/' jika ada
        $cleanPath = ltrim(str_replace(['storage/', 'public/'], '', $berkas->file_path), '/');
        
        // Dapatkan path fisik file di server
        $fullPath = Storage::disk('public')->path($cleanPath);

        // Fallback jika tidak ditemukan di disk public default
        if (!file_exists($fullPath)) {
            $fullPath = storage_path('app/public/' . $cleanPath);
        }

        // Cek keberadaan file fisik
        if (!file_exists($fullPath) || !is_file($fullPath)) {
            return response()->json([
                'success' => false,
                'message' => 'File fisik PDF tidak ditemukan di server.'
            ], 404);
        }

        // Mengirimkan response file dengan Content-Type application/pdf dan Content-Disposition inline
        return response()->file($fullPath, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . ($berkas->nama_file ?? 'dokumen.pdf') . '"'
        ]);
    }
    // BUKA KEMBALI UJIAN (RESET SESI UJIAN PESERTA)
    public function resetUjianPeserta($id)
    {
        $user = User::where('role', 'peserta')->findOrFail($id);

        // 1. Reset Token Absensi agar token sebelumnya dapat digunakan kembali
        if ($user->token) {
            $user->token->update([
                'is_used' => false,
                'used_at' => null
            ]);
        }

        // 2. Hapus detail jawaban & riwayat ujian sebelumnya agar bersih untuk ujian ulang
        $riwayatList = \App\Models\RiwayatUjian::where('user_id', $user->id)->get();
        foreach ($riwayatList as $riwayat) {
            \App\Models\DetailJawabanUjian::where('riwayat_ujian_id', $riwayat->id)->delete();
            $riwayat->delete();
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Sesi ujian untuk peserta ' . $user->nama . ' berhasil dibuka kembali! Token dapat digunakan ulang.'
        ]);
    }
    
}