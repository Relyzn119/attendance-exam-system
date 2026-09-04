<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use Illuminate\Http\Request;
use App\Models\BerkasPegawai;
use Illuminate\Support\Facades\Storage;

class PegawaiController extends Controller
{
    /**
     * Get List Dokumentasi Diklat + Statistics Counter + Filtering
     */
    public function index(Request $request)
    {
        try {
            // 1. Hitung Statistik Counter E-Arsip Diklat
            $totalDiklat = Pegawai::count();
            $totalBerkasPdf = BerkasPegawai::count();
            $diklatBulanIni = Pegawai::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count();

            // 2. Query Builder untuk List Diklat + Count Berkas PDF
            $query = Pegawai::withCount('berkasPegawais');

            // Filter 1: Pencarian Teks (Judul Diklat / Nama, Deskripsi / Unit)
            if ($request->has('search') && !empty($request->search)) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('nama_lengkap', 'like', "%{$search}%")
                        ->orWhere('unit_departemen', 'like', "%{$search}%")
                        ->orWhere('nik', 'like', "%{$search}%");
                });
            }

            // Filter 2: Tanggal Pelaksanaan / Upload (Exact Date)
            if ($request->has('tanggal_upload') && !empty($request->tanggal_upload)) {
                $query->whereDate('tanggal_upload', $request->tanggal_upload);
            }

            // Filter 3: Bulan (1 s.d. 12)
            if ($request->has('bulan') && !empty($request->bulan)) {
                $query->whereMonth('tanggal_upload', $request->bulan);
            }

            // Filter 4: Minggu Ke-1 s.d. Ke-4
            if ($request->has('minggu') && !empty($request->minggu)) {
                $minggu = (int) $request->minggu;
                if ($minggu === 1) {
                    $query->whereBetween(\Illuminate\Support\Facades\DB::raw('DAY(tanggal_upload)'), [1, 7]);
                } elseif ($minggu === 2) {
                    $query->whereBetween(\Illuminate\Support\Facades\DB::raw('DAY(tanggal_upload)'), [8, 14]);
                } elseif ($minggu === 3) {
                    $query->whereBetween(\Illuminate\Support\Facades\DB::raw('DAY(tanggal_upload)'), [15, 21]);
                } elseif ($minggu === 4) {
                    $query->whereBetween(\Illuminate\Support\Facades\DB::raw('DAY(tanggal_upload)'), [22, 31]);
                }
            }

            // Filter 5: Tahun (misal 2020, 2021, dst.)
            if ($request->has('tahun') && !empty($request->tahun)) {
                $query->whereYear('tanggal_upload', $request->tahun);
            }

            // Ambil data diurutkan dari yang terbaru
            $diklatList = $query->orderBy('created_at', 'desc')->get();

            return response()->json([
                'success' => true,
                'message' => 'Berhasil mengambil data dokumentasi diklat & arsip',
                'statistics' => [
                    'total_diklat'     => $totalDiklat,
                    'total_berkas_pdf' => $totalBerkasPdf,
                    'diklat_bulan_ini' => $diklatBulanIni,
                    // Backward compatibility keys
                    'total_dokter'     => $totalDiklat,
                    'total_perawat'    => $totalBerkasPdf,
                    'staf_penunjang'   => $diklatBulanIni,
                ],
                'data' => $diklatList
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data dokumentasi diklat: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store Data Dokumentasi Diklat Baru + Upload Berkas 1, Berkas 2, Berkas 3, dst.
     */
    public function store(Request $request)
    {
        // Validasi Input (Judul Required, Deskripsi & Tanggal Optional)
        $request->validate([
            'judul'           => 'nullable|string|max:255',
            'nama_lengkap'    => 'nullable|string|max:255',
            'deskripsi'       => 'nullable|string|max:1000',
            'unit_departemen' => 'nullable|string|max:1000',
            'tanggal_upload'  => 'nullable|date',
        ]);

        try {
            // Ambil Judul & Deskripsi dari request
            $judul = $request->input('judul') ?: $request->input('nama_lengkap') ?: 'Dokumentasi Diklat Baru';
            $deskripsi = $request->input('deskripsi') ?: $request->input('unit_departemen') ?: '-';
            $tanggalUpload = $request->input('tanggal_upload') ?: now()->format('Y-m-d');
            $nik = 'DKL-' . date('Ymd') . '-' . rand(1000, 9999);

            // Simpan Data Diklat
            $pegawai = Pegawai::create([
                'nik'                 => $nik,
                'nama_lengkap'        => $judul,
                'kategori_peran'      => 'Dokumentasi Diklat',
                'unit_departemen'     => $deskripsi,
                'email_resmi'         => null,
                'no_hp'               => null,
                'pendidikan_terakhir' => null,
                'tanggal_upload'      => $tanggalUpload,
            ]);

            // Handling Upload File Berkas PDF (Berkas 1, Berkas 2, Berkas 3, dst.)
            $this->processUploadedFiles($request, $pegawai);

            return response()->json([
                'success' => true,
                'message' => 'Data Dokumentasi Diklat & Berkas berhasil disimpan!',
                'data'    => $pegawai->load('berkasPegawais')
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan data diklat: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get Detail Dokumentasi Diklat + Daftar Berkas PDF
     */
    public function show($id)
    {
        try {
            $pegawai = Pegawai::with('berkasPegawais')->find($id);

            if (!$pegawai) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data dokumentasi diklat tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Berhasil mengambil detail dokumentasi diklat & berkas',
                'data'    => $pegawai
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil detail diklat: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Upload File PDF Berkas Baru (Berkas 1, Berkas 2, dst.)
     */
    public function uploadBerkas(Request $request, $pegawaiId)
    {
        $request->validate([
            'jenis_berkas'  => 'nullable|string',
            'judul_dokumen' => 'nullable|string|max:255',
            'file_pdf'      => 'required|file|mimes:pdf|max:10240', // Max 10MB PDF
            'catatan_hrd'   => 'nullable|string|max:255',
        ]);

        try {
            $pegawai = Pegawai::findOrFail($pegawaiId);

            if ($request->hasFile('file_pdf')) {
                $file = $request->file('file_pdf');
                $originalName = $file->getClientOriginalName();
                $fileName = time() . '_' . preg_replace('/[^a-zA-Z0-9_\.-]/', '_', $originalName);
                $filePath = $file->storeAs('berkas_pegawai', $fileName, 'public');

                $bytes = $file->getSize();
                if ($bytes >= 1048576) {
                    $fileSize = number_format($bytes / 1048576, 1) . ' MB';
                } elseif ($bytes >= 1024) {
                    $fileSize = number_format($bytes / 1024, 1) . ' KB';
                } else {
                    $fileSize = $bytes . ' B';
                }

                $berkasCount = $pegawai->berkasPegawais()->count() + 1;
                $jenisBerkas = $request->input('jenis_berkas') ?: ('Berkas ' . $berkasCount);

                $berkas = BerkasPegawai::create([
                    'pegawai_id'     => $pegawai->id,
                    'jenis_berkas'   => $jenisBerkas,
                    'judul_dokumen'  => $request->input('judul_dokumen') ?: ($jenisBerkas . ' - ' . $pegawai->nama_lengkap),
                    'nama_file'      => $originalName,
                    'file_path'      => 'storage/' . $filePath,
                    'file_size'      => $fileSize,
                    'catatan_hrd'    => $request->catatan_hrd ?? 'Terverifikasi Diklat',
                    'tanggal_upload' => now()->format('Y-m-d'),
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Berkas PDF berhasil diupload!',
                    'data'    => $berkas
                ], 201);
            }

            return response()->json([
                'success' => false,
                'message' => 'File PDF tidak ditemukan dalam request',
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupload berkas: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Hapus Berkas PDF Diklat
     */
    public function destroyBerkas($id)
    {
        try {
            $berkas = BerkasPegawai::find($id);

            if (!$berkas) {
                return response()->json([
                    'success' => false,
                    'message' => 'Berkas tidak ditemukan'
                ], 404);
            }

            $cleanPath = str_replace('storage/', '', $berkas->file_path);
            if (Storage::disk('public')->exists($cleanPath)) {
                Storage::disk('public')->delete($cleanPath);
            }

            $berkas->delete();

            return response()->json([
                'success' => true,
                'message' => 'Berkas PDF berhasil dihapus'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus berkas: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update Data Dokumentasi Diklat & Upload Berkas Baru
     */
    public function update(Request $request, $id)
    {
        $pegawai = Pegawai::find($id);

        if (!$pegawai) {
            return response()->json([
                'success' => false,
                'message' => 'Data dokumentasi diklat tidak ditemukan'
            ], 404);
        }

        try {
            $judul = $request->input('judul') ?? $request->input('nama_lengkap') ?? $pegawai->nama_lengkap;

            $deskripsi = $request->has('deskripsi')
                ? $request->input('deskripsi')
                : ($request->has('unit_departemen') ? $request->input('unit_departemen') : $pegawai->unit_departemen);

            $tanggalUpload = $request->input('tanggal_upload') ?: $pegawai->tanggal_upload;

            $pegawai->update([
                'nama_lengkap'    => $judul,
                'unit_departemen' => $deskripsi ?? '-',
                'tanggal_upload'  => $tanggalUpload,
            ]);

            // Handling Upload File Berkas PDF Baru
            $this->processUploadedFiles($request, $pegawai);

            return response()->json([
                'success' => true,
                'message' => 'Data Dokumentasi Diklat & Berkas berhasil diperbarui!',
                'data'    => $pegawai->load('berkasPegawais')
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui data diklat: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Hapus Data Dokumentasi Diklat & Seluruh Dokumen Fisiknya
     */
    public function destroy($id)
    {
        try {
            $pegawai = Pegawai::with('berkasPegawais')->find($id);

            if (!$pegawai) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data dokumentasi diklat tidak ditemukan'
                ], 404);
            }

            foreach ($pegawai->berkasPegawais as $berkas) {
                $cleanPath = str_replace('storage/', '', $berkas->file_path);
                if (Storage::disk('public')->exists($cleanPath)) {
                    Storage::disk('public')->delete($cleanPath);
                }
            }

            $pegawai->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data Dokumentasi Diklat & seluruh berkas PDF berhasil dihapus'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus diklat: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Download Semua Dokumen & Gambar Diklat (Berkas 1 s.d. Berkas 6+)
     * Disatukan/Merged menjadi 1 File PDF MENTAH tanpa cover/penjelasan/deskripsi tambahan.
     * Urutan berkas dari upload pertama hingga terakhir.
     */
    public function downloadZip($id)
    {
        try {
            $pegawai = Pegawai::find($id);

            if (!$pegawai) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data diklat tidak ditemukan'
                ], 404);
            }

            // Ambil seluruh berkas diurutkan dari UPLOAD PERTAMA HINGGA TERAKHIR (id asc, created_at asc)
            $berkasList = $pegawai->berkasPegawais()
                ->orderBy('created_at', 'asc')
                ->orderBy('id', 'asc')
                ->get();

            if ($berkasList->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Diklat ini belum memiliki berkas terunggah'
                ], 400);
            }

            // 1. Inisialisasi FPDI Merger untuk File MENTAH
            $pdfMerger = new \setasign\Fpdi\Fpdi();
            $tempFilesToDelete = [];
            $validPageCount = 0;

            // 2. Loop & Merge Setiap Berkas MENTAH (Berkas 1, Berkas 2, Berkas 3, dst.) secara Berurutan
            foreach ($berkasList as $berkas) {
                $cleanPath = str_replace('storage/', '', $berkas->file_path);
                $fullPath = storage_path('app/public/' . $cleanPath);

                if (!file_exists($fullPath)) {
                    continue;
                }

                $ext = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));

                if ($ext === 'pdf') {
                    // FITUR MERGE BERKAS PDF MENTAH
                    try {
                        $pageCount = $pdfMerger->setSourceFile($fullPath);
                        for ($p = 1; $p <= $pageCount; $p++) {
                            $tplId = $pdfMerger->importPage($p);
                            $size = $pdfMerger->getTemplateSize($tplId);
                            $pdfMerger->AddPage($size['orientation'], [$size['width'], $size['height']]);
                            $pdfMerger->useTemplate($tplId);
                            $validPageCount++;
                        }
                    } catch (\Exception $pdfEx) {
                        \Log::warning("Gagal merge halaman PDF: " . $pdfEx->getMessage());
                    }
                } elseif (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                    // FITUR MERGE GAMBAR MENTAH (JPG/PNG/WEBP)
                    $imageToUse = $fullPath;

                    if ($ext === 'webp' && function_exists('imagecreatefromwebp')) {
                        $tempJpg = storage_path('app/temp_img_' . uniqid() . '.jpg');
                        $img = @imagecreatefromwebp($fullPath);
                        if ($img) {
                            imagejpeg($img, $tempJpg, 90);
                            imagedestroy($img);
                            $imageToUse = $tempJpg;
                            $tempFilesToDelete[] = $tempJpg;
                        }
                    }

                    if (file_exists($imageToUse)) {
                        list($imgWidth, $imgHeight) = @getimagesize($imageToUse);
                        
                        if ($imgWidth && $imgHeight) {
                            $orientation = ($imgWidth > $imgHeight) ? 'L' : 'P';
                            $pdfMerger->AddPage($orientation, 'A4');
                            
                            $pageWidth = ($orientation === 'L') ? 297 : 210;
                            $pageHeight = ($orientation === 'L') ? 210 : 297;
                            
                            $maxWidth = $pageWidth - 20;
                            $maxHeight = $pageHeight - 20;
                            
                            $ratio = min($maxWidth / $imgWidth, $maxHeight / $imgHeight);
                            $w = $imgWidth * $ratio;
                            $h = $imgHeight * $ratio;
                            $x = ($pageWidth - $w) / 2;
                            $y = ($pageHeight - $h) / 2;

                            $pdfMerger->Image($imageToUse, $x, $y, $w, $h);
                            $validPageCount++;
                        }
                    }
                }
            }

            if ($validPageCount === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada berkas valid yang dapat di-merge'
                ], 400);
            }

            // Output Content PDF Gabungan Mentah
            $mergedPdfOutput = $pdfMerger->Output('S');

            // Hapus file temporary jika ada
            foreach ($tempFilesToDelete as $fileToDelete) {
                if (file_exists($fileToDelete)) {
                    @unlink($fileToDelete);
                }
            }

            $cleanTitle = preg_replace('/[^a-zA-Z0-9_-]/', '_', $pegawai->nama_lengkap);
            $fileName = 'Arsip_Diklat_' . $cleanTitle . '_' . date('Ymd') . '.pdf';

            return response($mergedPdfOutput, 200, [
                'Content-Type'        => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error membuat PDF gabungan mentah: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Helper privat untuk memproses file upload (Berkas 1, Berkas 2, dst.)
     */
    private function processUploadedFiles(Request $request, Pegawai $pegawai)
    {
        $allFiles = $request->allFiles();

        // 1. Handling upload file_berkas_1, file_berkas_2, ..., file_berkas_N (Dinamis tanpa batas)
        foreach ($allFiles as $inputKey => $file) {
            if (str_starts_with($inputKey, 'file_berkas_') && $file->isValid()) {
                $indexStr = str_replace('file_berkas_', '', $inputKey);
                $i = is_numeric($indexStr) ? (int) $indexStr : 1;

                $originalName = $file->getClientOriginalName();
                $fileName = time() . '_' . rand(100, 999) . '_' . preg_replace('/[^a-zA-Z0-9_\.-]/', '_', $originalName);
                $filePath = $file->storeAs('berkas_pegawai', $fileName, 'public');

                $bytes = $file->getSize();
                $fileSize = $bytes >= 1048576
                    ? number_format($bytes / 1048576, 1) . ' MB'
                    : number_format($bytes / 1024, 1) . ' KB';

                $jenisBerkas = "Berkas {$i}";

                BerkasPegawai::create([
                    'pegawai_id'     => $pegawai->id,
                    'jenis_berkas'   => $jenisBerkas,
                    'judul_dokumen'  => "{$jenisBerkas} - {$pegawai->nama_lengkap}",
                    'nama_file'      => $originalName,
                    'file_path'      => 'storage/' . $filePath,
                    'file_size'      => $fileSize,
                    'catatan_hrd'    => 'Diunggah pada Dokumentasi Diklat',
                    'tanggal_upload' => $pegawai->tanggal_upload ?? now()->format('Y-m-d'),
                ]);
            }
        }

        // 2. Backward compatibility untuk key lama (file_ijazah, file_str, dst.)
        $oldDocumentTypes = [
            'file_ijazah'   => 'Berkas 1',
            'file_transkrip' => 'Berkas 2',
            'file_str'      => 'Berkas 3',
            'file_sip'      => 'Berkas 4',
            'file_ktp'      => 'Berkas 5',
            'file_kk'       => 'Berkas 6',
            'file_cv'       => 'Berkas 7',
            'file_lamaran'  => 'Berkas 8',
            'file_lainnya'  => 'Berkas 9',
        ];

        foreach ($oldDocumentTypes as $inputKey => $defaultJenis) {
            if ($request->hasFile($inputKey)) {
                $file = $request->file($inputKey);
                $originalName = $file->getClientOriginalName();
                $fileName = time() . '_' . rand(100, 999) . '_' . preg_replace('/[^a-zA-Z0-9_\.-]/', '_', $originalName);
                $filePath = $file->storeAs('berkas_pegawai', $fileName, 'public');

                $bytes = $file->getSize();
                $fileSize = $bytes >= 1048576
                    ? number_format($bytes / 1048576, 1) . ' MB'
                    : number_format($bytes / 1024, 1) . ' KB';

                BerkasPegawai::create([
                    'pegawai_id'     => $pegawai->id,
                    'jenis_berkas'   => $defaultJenis,
                    'judul_dokumen'  => "{$defaultJenis} - {$pegawai->nama_lengkap}",
                    'nama_file'      => $originalName,
                    'file_path'      => 'storage/' . $filePath,
                    'file_size'      => $fileSize,
                    'catatan_hrd'    => 'Diunggah pada Dokumentasi Diklat',
                    'tanggal_upload' => $pegawai->tanggal_upload ?? now()->format('Y-m-d'),
                ]);
            }
        }
    }
}

