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
    // 5. Generate Token Absensi / Ujian dengan Opsi Tipe Token & Durasi
    public function generateToken(Request $request, $userId)
    {
        $user = User::findOrFail($userId);

        $kodeToken = strtoupper(Str::random(6));
        $tipeToken = $request->input('tipe_token', 'ujian'); // 'absensi' atau 'ujian'
        if (!in_array($tipeToken, ['absensi', 'ujian'])) {
            $tipeToken = 'ujian';
        }
        $durasi = $request->input('durasi_menit', 60);

        $token = TokenAbsensi::updateOrCreate(
            ['user_id' => $user->id],
            [
                'kode_token'   => $kodeToken,
                'tipe_token'   => $tipeToken,
                'durasi_menit' => $durasi,
                'is_used'      => false,
                'used_at'      => null
            ]
        );

        $pesanTipe = ($tipeToken === 'absensi') ? 'Token Absensi Saja' : 'Token Masuk Ujian';

        return response()->json([
            'status'     => 'success',
            'message'    => "{$pesanTipe} berhasil dibuat!",
            'token'      => $token->kode_token,
            'tipe_token' => $token->tipe_token,
            'durasi'     => $token->durasi_menit
        ]);
    }

    // 6. Export PDF Absensi berdasarkan tanggal tertentu (Reset harian & riwayat)
    public function exportAbsensiPdf(Request $request)
    {
        $tanggal = $request->input('tanggal') ?: date('Y-m-d');

        // Ambil riwayat absensi peserta yang sudah input token pada tanggal tersebut
        $absensiList = \App\Models\Absensi::with('user')
            ->whereDate('created_at', $tanggal)
            ->orderBy('created_at', 'asc')
            ->get();

        $imagePath = public_path('images/BackgroundDocument.png');
        $bgBase64 = '';
        if (file_exists($imagePath)) {
            $bgData = file_get_contents($imagePath);
            $bgBase64 = 'data:image/png;base64,' . base64_encode($bgData);
        }

        $pdf = Pdf::loadView('pdf.absensi', compact('absensiList', 'tanggal', 'bgBase64'))->setPaper('a4', 'portrait');
        
        $cleanDate = str_replace('-', '', $tanggal);
        return $pdf->download("Daftar_Absensi_Diklat_" . $cleanDate . ".pdf");
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

    // 8. Ambil Pengaturan Sertifikat (Nama Direktur, Pembicara, TTD)
    public function getSertifikatSetting()
    {
        $setting = \App\Models\SertifikatSetting::first();

        if (!$setting) {
            return response()->json([
                'nama_direktur'    => 'dr. Iskandar Candra, M.Kes, FISQua, KMK, CHQP',
                'nama_pembicara'   => 'JUPENTIUS SITUMORANG',
                'tipe_ttd'          => 'digital',
                'use_bg_watermark' => true,
                'ttd_direktur'     => null,
                'ttd_pembicara'    => null,
            ]);
        }

        return response()->json($setting);
    }

    // 9. Simpan / Perbarui Pengaturan Sertifikat
    public function updateSertifikatSetting(Request $request)
    {
        $request->validate([
            'nama_direktur'    => 'nullable|string|max:255',
            'nama_pembicara'   => 'nullable|string|max:255',
            'tipe_ttd'          => 'required|in:digital,basah',
            'use_bg_watermark' => 'nullable',
            'ttd_direktur'     => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
            'ttd_pembicara'    => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
        ]);

        $setting = \App\Models\SertifikatSetting::first() ?? new \App\Models\SertifikatSetting();

        $setting->nama_direktur = $request->nama_direktur ?: 'dr. Iskandar Candra, M.Kes, FISQua, KMK, CHQP';
        $setting->nama_pembicara = $request->nama_pembicara ?: 'JUPENTIUS SITUMORANG';
        $setting->tipe_ttd = $request->tipe_ttd;
        $setting->use_bg_watermark = filter_var($request->input('use_bg_watermark', true), FILTER_VALIDATE_BOOLEAN);

        if ($request->hasFile('ttd_direktur')) {
            $file = $request->file('ttd_direktur');
            $fileName = 'ttd_direktur_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/ttd'), $fileName);
            $setting->ttd_direktur = 'uploads/ttd/' . $fileName;
        }

        if ($request->hasFile('ttd_pembicara')) {
            $file = $request->file('ttd_pembicara');
            $fileName = 'ttd_pembicara_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/ttd'), $fileName);
            $setting->ttd_pembicara = 'uploads/ttd/' . $fileName;
        }

        $setting->save();

        return response()->json([
            'status'  => 'success',
            'message' => 'Pengaturan data sertifikat berhasil diperbarui!',
            'setting' => $setting
        ]);
    }
}