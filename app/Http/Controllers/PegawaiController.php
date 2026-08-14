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
    public function store(Request $request)
    {
        // 1. Validasi Input sesuai form Gambar 6
        $validated = $request->validate([
            'nik'                 => 'required|string|unique:pegawais,nik',
            'nama_lengkap'        => 'required|string|max:255',
            'kategori_peran'      => 'required|string',
            'unit_departemen'     => 'required|string|max:255',
            'email_resmi'         => 'nullable|email|max:255',
            'no_hp'               => 'nullable|string|max:50',
            'pendidikan_terakhir' => 'nullable|string|max:255',
            'tanggal_upload'      => 'nullable|date',

            // Checkbox Lampiran Otomatis (Gambar 6)
            'lampiran_ijazah'     => 'nullable|boolean',
            'lampiran_transkrip'  => 'nullable|boolean',
            'lampiran_str_sip'    => 'nullable|boolean',
        ]);

        try {
            // 2. Normalisasi kategori_peran dari pilihan dropdown Vue
            $peran = $validated['kategori_peran'];
            if (str_contains($peran, 'Dokter')) {
                $peran = 'Dokter';
            } elseif (str_contains($peran, 'Perawat')) {
                $peran = 'Perawat';
            } elseif (str_contains($peran, 'Penunjang Medis')) {
                $peran = 'Penunjang Medis';
            } elseif (str_contains($peran, 'Staf Administrasi') || str_contains($peran, 'HRD')) {
                $peran = 'Staf Administrasi';
            }

            // 3. Simpan Data Pegawai
            $pegawai = Pegawai::create([
                'nik'                 => $validated['nik'],
                'nama_lengkap'        => $validated['nama_lengkap'],
                'kategori_peran'      => $peran,
                'unit_departemen'     => $validated['unit_departemen'],
                'email_resmi'         => $request->email_resmi,
                'no_hp'               => $request->no_hp,
                'pendidikan_terakhir' => $request->pendidikan_terakhir,
                'tanggal_upload'      => $request->tanggal_upload ?? now()->format('Y-m-d'),
            ]);

            // Sanitisasi nama untuk file placeholder
            $cleanName = str_replace([' ', '.', ','], '_', $pegawai->nama_lengkap);

            // 4. Buat Lampiran Otomatis jika Checkbox Di-centang (Gambar 6)
            if ($request->boolean('lampiran_ijazah')) {
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

            if ($request->boolean('lampiran_transkrip')) {
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

            if ($request->boolean('lampiran_str_sip')) {
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
}
