<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use Illuminate\Http\Request;
use App\Models\BerkasPegawai;
use Illuminate\Support\Facades\Storage;

class PegawaiController extends Controller
{
    /**
     * Get List Pegawai + Statistics Counter + Filtering
     */
    public function index(Request $request)
    {
        try {
            // 1. Hitung Statistik Counter (Independen dari filter tabel)
            $totalDokter = Pegawai::where('kategori_peran', 'Dokter')->count();
            $totalPerawat = Pegawai::where('kategori_peran', 'Perawat')->count();
            $stafPenunjang = Pegawai::whereIn('kategori_peran', ['Penunjang Medis', 'Staf Administrasi'])->count();

            // 2. Query Builder untuk List Pegawai + Count Berkas PDF
            $query = Pegawai::withCount('berkasPegawais');

            // Filter 1: Pencarian Teks (Nama, NIK, Unit/Departemen, Email)
            if ($request->has('search') && !empty($request->search)) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('nama_lengkap', 'like', "%{$search}%")
                        ->orWhere('nik', 'like', "%{$search}%")
                        ->orWhere('unit_departemen', 'like', "%{$search}%")
                        ->orWhere('email_resmi', 'like', "%{$search}%");
                });
            }

            // Filter 2: Kategori Peran / Profesi
            if ($request->has('kategori_peran') && !empty($request->kategori_peran) && $request->kategori_peran !== 'Semua Peran / Profession') {
                $peran = $request->kategori_peran;

                // Handling mapping teks dropdown dari frontend
                if (str_contains($peran, 'Dokter')) {
                    $query->where('kategori_peran', 'Dokter');
                } elseif (str_contains($peran, 'Perawat')) {
                    $query->where('kategori_peran', 'Perawat');
                } elseif (str_contains($peran, 'Penunjang Medis')) {
                    $query->where('kategori_peran', 'Penunjang Medis');
                } elseif (str_contains($peran, 'Staf Administrasi')) {
                    $query->where('kategori_peran', 'Staf Administrasi');
                } else {
                    $query->where('kategori_peran', $peran);
                }
            }

            // Filter 3: Tanggal Upload
            if ($request->has('tanggal_upload') && !empty($request->tanggal_upload)) {
                $query->whereDate('tanggal_upload', $request->tanggal_upload);
            }

            // Ambil data diurutkan dari yang terbaru
            $pegawaiList = $query->orderBy('created_at', 'desc')->get();

            return response()->json([
                'success' => true,
                'message' => 'Berhasil mengambil data pegawai & arsip',
                'statistics' => [
                    'total_dokter' => $totalDokter,
                    'total_perawat' => $totalPerawat,
                    'staf_penunjang' => $stafPenunjang,
                ],
                'data' => $pegawaiList
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data pegawai: ' . $e->getMessage(),
            ], 500);
        }
    }
    /**
     * Store Data Pegawai Baru & Automatic Attachment Placeholders
     */
    /**
     * Store Data Pegawai Baru + Direct File Uploads + Custom Role
     */
    public function store(Request $request)
    {
        // 1. Validasi Input (NIK & Unit sekarang OPSIONAL / nullable)
        $validated = $request->validate([
            'nik'                  => 'nullable|string|max:50|unique:pegawais,nik',
            'nama_lengkap'         => 'required|string|max:255',
            'kategori_peran'       => 'required|string',
            'kategori_peran_custom' => 'nullable|string|max:255',
            'unit_departemen'      => 'nullable|string|max:255',
            'email_resmi'          => 'nullable|email|max:255',
            'no_hp'                => 'nullable|string|max:50',
            'pendidikan_terakhir'  => 'nullable|string|max:255',
            'tanggal_upload'       => 'nullable|date',

            // Validasi file PDF opsional jika diupload langsung saat pendaftaran
            'file_ijazah'          => 'nullable|file|mimes:pdf|max:10240',
            'file_transkrip'       => 'nullable|file|mimes:pdf|max:10240',
            'file_str'             => 'nullable|file|mimes:pdf|max:10240',
            'file_sip'             => 'nullable|file|mimes:pdf|max:10240',
            'file_ktp'             => 'nullable|file|mimes:pdf|max:10240',
            'file_kk'              => 'nullable|file|mimes:pdf|max:10240',
            'file_cv'              => 'nullable|file|mimes:pdf|max:10240',
            'file_lamaran'         => 'nullable|file|mimes:pdf|max:10240',
            'file_lainnya'         => 'nullable|file|mimes:pdf|max:10240',
        ]);

        try {
            // 2. Normalisasi kategori_peran / Peran Kustom
            $peran = $validated['kategori_peran'];
            if (str_contains($peran, 'Lainnya') || $peran === 'Pegawai Lainnya') {
                $peran = $request->input('kategori_peran_custom') ?: 'Pegawai Lainnya';
            } else {
                if (str_contains($peran, 'Dokter')) {
                    $peran = 'Dokter';
                } elseif (str_contains($peran, 'Perawat')) {
                    $peran = 'Perawat';
                } elseif (str_contains($peran, 'Penunjang Medis')) {
                    $peran = 'Penunjang Medis';
                } elseif (str_contains($peran, 'Staf Administrasi') || str_contains($peran, 'HRD')) {
                    $peran = 'Staf Administrasi';
                }
            }

            // Jika NIK kosong, otomatis buat nomor NIK sementara unik
            $nik = $validated['nik'] ?? ('NP-' . time() . rand(10, 99));

            // 3. Simpan Data Pegawai
            $pegawai = Pegawai::create([
                'nik'                 => $nik,
                'nama_lengkap'        => $validated['nama_lengkap'],
                'kategori_peran'      => $peran,
                'unit_departemen'     => $validated['unit_departemen'] ?? 'Umum',
                'email_resmi'         => $request->email_resmi,
                'no_hp'               => $request->no_hp,
                'pendidikan_terakhir' => $request->pendidikan_terakhir,
                'tanggal_upload'      => $request->tanggal_upload ?? now()->format('Y-m-d'),
            ]);

            // 4. Handling Upload File PDF Langsung Saat Pendaftaran
            $documentTypes = [
                'file_ijazah'   => 'Ijazah Profesi / Gelar',
                'file_transkrip' => 'Transkrip Nilai Akademik',
                'file_str'      => 'STR (Surat Tanda Registrasi)',
                'file_sip'      => 'SIP (Surat Izin Praktik)',
                'file_ktp'      => 'KTP (Kartu Tanda Penduduk)',
                'file_kk'       => 'Kartu Keluarga (KK)',
                'file_cv'       => 'CV (Curriculum Vitae)',
                'file_lamaran'  => 'Surat Lamaran',
                'file_lainnya'  => 'Dokumen Lainnya',
            ];

            foreach ($documentTypes as $inputKey => $jenisBerkas) {
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
                        'jenis_berkas'   => $jenisBerkas,
                        'judul_dokumen'  => $jenisBerkas . ' - ' . $pegawai->nama_lengkap,
                        'nama_file'      => $originalName,
                        'file_path'      => 'storage/' . $filePath,
                        'file_size'      => $fileSize,
                        'catatan_hrd'    => 'Diunggah saat pendaftaran',
                        'tanggal_upload' => $pegawai->tanggal_upload,
                    ]);
                }
            }

            // Fallback: Checkbox Draf Placeholder jika dicentang dan tidak ada upload file fisik
            $cleanName = str_replace([' ', '.', ','], '_', $pegawai->nama_lengkap);

            if ($request->boolean('lampiran_ijazah') && !$request->hasFile('file_ijazah')) {
                BerkasPegawai::create([
                    'pegawai_id'     => $pegawai->id,
                    'jenis_berkas'   => 'Ijazah Profesi / Gelar',
                    'judul_dokumen'  => 'Ijazah Profesi & Gelar',
                    'nama_file'      => 'Ijazah_' . $cleanName . '.pdf',
                    'file_path'      => 'berkas_pegawai/sample.pdf',
                    'file_size'      => '1.0 MB',
                    'catatan_hrd'    => 'Sistem Auto-Upload Pendaftaran',
                    'tanggal_upload' => $pegawai->tanggal_upload,
                ]);
            }

            if ($request->boolean('lampiran_transkrip') && !$request->hasFile('file_transkrip')) {
                BerkasPegawai::create([
                    'pegawai_id'     => $pegawai->id,
                    'jenis_berkas'   => 'Transkrip Nilai Akademik',
                    'judul_dokumen'  => 'Transkrip Nilai Akademik',
                    'nama_file'      => 'Transkrip_' . $cleanName . '.pdf',
                    'file_path'      => 'berkas_pegawai/sample.pdf',
                    'file_size'      => '1.0 MB',
                    'catatan_hrd'    => 'Sistem Auto-Upload Pendaftaran',
                    'tanggal_upload' => $pegawai->tanggal_upload,
                ]);
            }

            if ($request->boolean('lampiran_str_sip') && !$request->hasFile('file_str') && !$request->hasFile('file_sip')) {
                BerkasPegawai::create([
                    'pegawai_id'     => $pegawai->id,
                    'jenis_berkas'   => 'STR (Surat Tanda Registrasi)',
                    'judul_dokumen'  => 'STR / SIP Medis Resmi',
                    'nama_file'      => 'STR_SIP_' . $cleanName . '.pdf',
                    'file_path'      => 'berkas_pegawai/sample.pdf',
                    'file_size'      => '1.0 MB',
                    'catatan_hrd'    => 'Sistem Auto-Upload Pendaftaran',
                    'tanggal_upload' => $pegawai->tanggal_upload,
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Data Pegawai Baru & Berkas Lampiran berhasil disimpan!',
                'data'    => $pegawai->load('berkasPegawais')
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan data pegawai: ' . $e->getMessage(),
            ], 500);
        }
    }
    /**
     * Get Detail Pegawai + Daftar Berkas PDF Terupload (Gambar 2)
     */
    public function show($id)
    {
        try {
            $pegawai = Pegawai::with('berkasPegawais')->find($id);

            if (!$pegawai) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data pegawai tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Berhasil mengambil detail pegawai & berkas',
                'data'    => $pegawai
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil detail pegawai: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Upload File PDF Berkas Pegawai Baru (Gambar 3 & 4)
     * Mendukung: KK, KTP, CV, Surat Lamaran, STR, SIP, Transkrip, Ijazah, dll.
     */
    public function uploadBerkas(Request $request, $pegawaiId)
    {
        $request->validate([
            'jenis_berkas'  => 'required|string',
            'judul_dokumen' => 'required|string|max:255',
            'file_pdf'      => 'required|file|mimes:pdf|max:10240', // Max 10MB PDF
            'catatan_hrd'   => 'nullable|string|max:255',
        ]);

        try {
            $pegawai = Pegawai::findOrFail($pegawaiId);

            if ($request->hasFile('file_pdf')) {
                $file = $request->file('file_pdf');
                $originalName = $file->getClientOriginalName();

                // Format nama file unik untuk disimpan di disk storage
                $fileName = time() . '_' . preg_replace('/[^a-zA-Z0-9_\.-]/', '_', $originalName);

                // Simpan file fisik ke storage/app/public/berkas_pegawai
                $filePath = $file->storeAs('berkas_pegawai', $fileName, 'public');

                // Hitung Format File Size (MB / KB) secara dinamis
                $bytes = $file->getSize();
                if ($bytes >= 1048576) {
                    $fileSize = number_format($bytes / 1048576, 1) . ' MB';
                } elseif ($bytes >= 1024) {
                    $fileSize = number_format($bytes / 1024, 1) . ' KB';
                } else {
                    $fileSize = $bytes . ' B';
                }

                // Simpan Record Berkas ke Database
                $berkas = BerkasPegawai::create([
                    'pegawai_id'     => $pegawai->id,
                    'jenis_berkas'   => $request->jenis_berkas,
                    'judul_dokumen'  => $request->judul_dokumen,
                    'nama_file'      => $originalName,
                    'file_path'      => 'storage/' . $filePath,
                    'file_size'      => $fileSize,
                    'catatan_hrd'    => $request->catatan_hrd ?? 'Terverifikasi Asli HRD',
                    'tanggal_upload' => now()->format('Y-m-d'),
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Berkas PDF berhasil diupload & diverifikasi!',
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
     * Hapus Berkas PDF Pegawai
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

            // Hapus file fisik jika ada di storage
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
     * Update Data Pegawai & Upload Lampiran Berkas Baru saat Edit
     */
    public function update(Request $request, $id)
    {
        $pegawai = Pegawai::find($id);

        if (!$pegawai) {
            return response()->json([
                'success' => false,
                'message' => 'Data pegawai tidak ditemukan'
            ], 404);
        }

        // 1. Validasi Input (NIK & Unit Opsional)
        $validated = $request->validate([
            'nik'                  => 'nullable|string|max:50|unique:pegawais,nik,' . $id,
            'nama_lengkap'         => 'required|string|max:255',
            'kategori_peran'       => 'required|string',
            'kategori_peran_custom' => 'nullable|string|max:255',
            'unit_departemen'      => 'nullable|string|max:255',
            'email_resmi'          => 'nullable|email|max:255',
            'no_hp'                => 'nullable|string|max:50',
            'pendidikan_terakhir'  => 'nullable|string|max:255',

            // Validasi upload file PDF opsional saat edit
            'file_ijazah'          => 'nullable|file|mimes:pdf|max:10240',
            'file_transkrip'       => 'nullable|file|mimes:pdf|max:10240',
            'file_str'             => 'nullable|file|mimes:pdf|max:10240',
            'file_sip'             => 'nullable|file|mimes:pdf|max:10240',
            'file_ktp'             => 'nullable|file|mimes:pdf|max:10240',
            'file_kk'              => 'nullable|file|mimes:pdf|max:10240',
            'file_cv'              => 'nullable|file|mimes:pdf|max:10240',
            'file_lamaran'         => 'nullable|file|mimes:pdf|max:10240',
            'file_lainnya'         => 'nullable|file|mimes:pdf|max:10240',
        ]);

        try {
            // 2. Normalisasi kategori_peran / Peran Kustom
            $peran = $validated['kategori_peran'];
            if (str_contains($peran, 'Lainnya') || $peran === 'Pegawai Lainnya') {
                $peran = $request->input('kategori_peran_custom') ?: 'Pegawai Lainnya';
            } else {
                if (str_contains($peran, 'Dokter')) {
                    $peran = 'Dokter';
                } elseif (str_contains($peran, 'Perawat')) {
                    $peran = 'Perawat';
                } elseif (str_contains($peran, 'Penunjang Medis')) {
                    $peran = 'Penunjang Medis';
                } elseif (str_contains($peran, 'Staf Administrasi') || str_contains($peran, 'HRD')) {
                    $peran = 'Staf Administrasi';
                }
            }

            // 3. Update Data Pegawai
            $pegawai->update([
                'nik'                 => $validated['nik'] ?? $pegawai->nik,
                'nama_lengkap'        => $validated['nama_lengkap'],
                'kategori_peran'      => $peran,
                'unit_departemen'     => $validated['unit_departemen'] ?? $pegawai->unit_departemen,
                'email_resmi'         => $request->email_resmi,
                'no_hp'               => $request->no_hp,
                'pendidikan_terakhir' => $request->pendidikan_terakhir,
            ]);

            // 4. Handling Upload File PDF Baru saat Edit (Opsional)
            $documentTypes = [
                'file_ijazah'   => 'Ijazah Profesi / Gelar',
                'file_transkrip' => 'Transkrip Nilai Akademik',
                'file_str'      => 'STR (Surat Tanda Registrasi)',
                'file_sip'      => 'SIP (Surat Izin Praktik)',
                'file_ktp'      => 'KTP (Kartu Tanda Penduduk)',
                'file_kk'       => 'Kartu Keluarga (KK)',
                'file_cv'       => 'CV (Curriculum Vitae)',
                'file_lamaran'  => 'Surat Lamaran',
                'file_lainnya'  => 'Dokumen Lainnya',
            ];

            foreach ($documentTypes as $inputKey => $jenisBerkas) {
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
                        'jenis_berkas'   => $jenisBerkas,
                        'judul_dokumen'  => $jenisBerkas . ' - ' . $pegawai->nama_lengkap,
                        'nama_file'      => $originalName,
                        'file_path'      => 'storage/' . $filePath,
                        'file_size'      => $fileSize,
                        'catatan_hrd'    => 'Diunggah saat update data',
                        'tanggal_upload' => now()->format('Y-m-d'),
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Data Pegawai & Dokumen berhasil diperbarui!',
                'data'    => $pegawai->load('berkasPegawais')
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui data pegawai: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Hapus Data Pegawai & Seluruh Dokumen Fisiknya di Storage
     */
    public function destroy($id)
    {
        try {
            $pegawai = Pegawai::with('berkasPegawais')->find($id);

            if (!$pegawai) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data pegawai tidak ditemukan'
                ], 404);
            }

            // 1. Hapus semua file PDF fisik milik pegawai dari folder storage
            foreach ($pegawai->berkasPegawais as $berkas) {
                $cleanPath = str_replace('storage/', '', $berkas->file_path);
                if (Storage::disk('public')->exists($cleanPath)) {
                    Storage::disk('public')->delete($cleanPath);
                }
            }

            // 2. Hapus data pegawai dari database
            $pegawai->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data Pegawai & seluruh berkas PDF berhasil dihapus'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus pegawai: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Download Semua Dokumen PDF Pegawai dalam Format ZIP (Tugas #6)
     */
    public function downloadZip($id)
    {
        try {
            $pegawai = Pegawai::with('berkasPegawais')->find($id);

            if (!$pegawai) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data pegawai tidak ditemukan'
                ], 404);
            }

            if ($pegawai->berkasPegawais->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pegawai ini belum memiliki berkas dokumen terunggah'
                ], 400);
            }

            // Buat File ZIP Sementara
            $zip = new \ZipArchive();
            $cleanName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $pegawai->nama_lengkap);
            $zipFileName = 'Arsip_Dokumen_' . $cleanName . '_' . time() . '.zip';
            $zipPath = storage_path('app/public/' . $zipFileName);

            if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === TRUE) {
                $fileCount = 0;

                foreach ($pegawai->berkasPegawais as $berkas) {
                    $cleanRelativePath = str_replace('storage/', '', $berkas->file_path);
                    $fullPath = storage_path('app/public/' . $cleanRelativePath);

                    if (file_exists($fullPath)) {
                        // Nama file di dalam zip diberi prefix jenis berkas agar rapi
                        $jenisClean = preg_replace('/[^a-zA-Z0-9_-]/', '_', $berkas->jenis_berkas);
                        $entryName = $jenisClean . '_' . $berkas->nama_file;

                        $zip->addFile($fullPath, $entryName);
                        $fileCount++;
                    }
                }

                $zip->close();

                if ($fileCount === 0) {
                    if (file_exists($zipPath)) {
                        unlink($zipPath);
                    }
                    return response()->json([
                        'success' => false,
                        'message' => 'File dokumen fisik tidak ditemukan di server'
                    ], 404);
                }

                // Stream Download File ZIP lalu hapus file temp setelah terkirim
                return response()->download($zipPath)->deleteFileAfterSend(true);
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
}
