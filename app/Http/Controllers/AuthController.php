<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\BerkasPeserta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // 1. Validasi Input Data & File (Pas Foto & KTP Wajib, sisanya Opsional/Nullable)
        $request->validate([
            'nama'          => 'required|string|max:255',
            'email'         => 'required|string|email|unique:users',
            'password'      => 'required|string|min:6',
            'jenis_kelamin' => 'required|in:L,P',
            'alamat'        => 'required|string',
            'no_hp'         => 'required|string|max:20',
            'nik'           => 'required|string|unique:users|max:20',
            'npwp'          => 'nullable|string|max:30',

            // Validasi File Wajib
            'file_pas_foto'      => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'file_ktp'           => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',

            // Validasi File Opsional (Nullable)
            'file_kk'            => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'file_ijazah'        => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'file_transkrip'     => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'file_cv'            => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'file_surat_lamaran' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'file_berkas_lain'   => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        DB::beginTransaction();
        try {
            // 2. Simpan Data User Peserta
            $user = User::create([
                'nama'          => $request->nama,
                'email'         => $request->email,
                'password'      => Hash::make($request->password),
                'role'          => 'peserta',
                'jenis_kelamin' => $request->jenis_kelamin,
                'alamat'        => $request->alamat,
                'no_hp'         => $request->no_hp,
                'nik'           => $request->nik,
                'npwp'          => $request->npwp ?? null,
            ]);

            // 3. Array Pemetaan Berkas
            $berkasList = [
                'file_pas_foto'      => 'Pas Foto',
                'file_ktp'           => 'KTP',
                'file_kk'            => 'Kartu Keluarga (KK)',
                'file_ijazah'        => 'Ijazah',
                'file_transkrip'     => 'Transkrip Nilai',
                'file_cv'            => 'Curriculum Vitae (CV)',
                'file_surat_lamaran' => 'Surat Lamaran',
                'file_berkas_lain'   => 'Berkas Lainnya',
            ];

            // 4. Proses Simpan File ke Storage
            foreach ($berkasList as $inputKey => $jenisBerkas) {
                if ($request->hasFile($inputKey)) {
                    $file = $request->file($inputKey);
                    $filename = time() . '_' . $inputKey . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('berkas_peserta/' . $user->id, $filename, 'public');

                    BerkasPeserta::create([
                        'user_id'      => $user->id,
                        'jenis_berkas' => $jenisBerkas,
                        'nama_file'    => $file->getClientOriginalName(),
                        'file_path'    => $path,
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'status'  => 'success',
                'message' => 'Pendaftaran berhasil! Silakan login untuk masuk ke Dashboard Peserta.'
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status'  => 'error',
                'message' => 'Terjadi kesalahan saat pendaftaran: ' . $e->getMessage()
            ], 500);
        }
    }
    // Tambahkan di dalam class AuthController

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Email atau password salah!'
            ], 401);
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Login berhasil!',
            'user'    => $user
        ]);
    }

    public function getProfile($id)
    {
        $user = User::with([
            'berkas', 
            'token', 
            'riwayatUjian' => function($query) {
                $query->orderBy('id', 'desc')->with('detailJawaban.soal');
            }
        ])->findOrFail($id);

        return response()->json($user);
    }
    // PREVIEW FILE BERKAS PESERTA (STREAM DIRECT WITH HEADERS)
    public function previewBerkas($id)
    {
        $berkas = BerkasPeserta::find($id);

        if (!$berkas) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Data berkas tidak ditemukan.'
            ], 404);
        }

        // Bersihkan relative path dari storage/ atau public/
        $cleanPath = ltrim(str_replace(['storage/', 'public/'], '', $berkas->file_path), '/');
        
        // Ambil path absolut fisik file di server
        $fullPath = Storage::disk('public')->path($cleanPath);

        if (!file_exists($fullPath)) {
            $fullPath = storage_path('app/public/' . $cleanPath);
        }

        if (!file_exists($fullPath) || !is_file($fullPath)) {
            return response()->json([
                'status'  => 'error',
                'message' => 'File fisik tidak ditemukan di server.'
            ], 404);
        }

        // Dapatkan mime-type resmi file (image/jpeg, image/png, application/pdf, dll)
        $mimeType = mime_content_type($fullPath) ?: 'application/octet-stream';

        return response()->file($fullPath, [
            'Content-Type'        => $mimeType,
            'Content-Disposition' => 'inline; filename="' . ($berkas->nama_file ?? 'dokumen') . '"'
        ]);
    }
    // EDIT DATA DIRI & RE-UPLOAD BERKAS PESERTA
    public function updateProfile(Request $request, $id)
    {
        $user = User::where('role', 'peserta')->findOrFail($id);

        // 1. Validasi Input Data & File Opsional
        $request->validate([
            'nama'          => 'required|string|max:255',
            'email'         => 'required|string|email|max:255|unique:users,email,' . $id,
            'nik'           => 'required|string|max:20|unique:users,nik,' . $id,
            'jenis_kelamin' => 'required|in:L,P',
            'no_hp'         => 'required|string|max:20',
            'alamat'        => 'required|string',
            'npwp'          => 'nullable|string|max:30',

            // Seluruh file opsional saat update (hanya diisi jika ingin mengganti file lama)
            'file_pas_foto'      => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'file_ktp'           => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'file_kk'            => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'file_ijazah'        => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'file_transkrip'     => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'file_cv'            => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'file_surat_lamaran' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'file_berkas_lain'   => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        DB::beginTransaction();
        try {
            // 2. Update Data Diri User
            $user->update([
                'nama'          => $request->nama,
                'email'         => $request->email,
                'nik'           => $request->nik,
                'jenis_kelamin' => $request->jenis_kelamin,
                'no_hp'         => $request->no_hp,
                'alamat'        => $request->alamat,
                'npwp'          => $request->npwp ?? null,
            ]);

            // 3. Mapping Berkas yang Mungkin Di-upload Ulang
            $berkasList = [
                'file_pas_foto'      => 'Pas Foto',
                'file_ktp'           => 'KTP',
                'file_kk'            => 'Kartu Keluarga (KK)',
                'file_ijazah'        => 'Ijazah',
                'file_transkrip'     => 'Transkrip Nilai',
                'file_cv'            => 'Curriculum Vitae (CV)',
                'file_surat_lamaran' => 'Surat Lamaran',
                'file_berkas_lain'   => 'Berkas Lainnya',
            ];

            // 4. Proses Ganti File Fisik jika Ada Upload Baru
            foreach ($berkasList as $inputKey => $jenisBerkas) {
                if ($request->hasFile($inputKey)) {
                    $file = $request->file($inputKey);

                    // Cek jika sudah ada berkas lama dengan jenis ini
                    $berkasLama = BerkasPeserta::where('user_id', $user->id)
                        ->where('jenis_berkas', $jenisBerkas)
                        ->first();

                    // Hapus file fisik lama dari disk jika ada
                    if ($berkasLama) {
                        $cleanRelativePath = ltrim(str_replace(['storage/', 'public/'], '', $berkasLama->file_path), '/');
                        if (Storage::disk('public')->exists($cleanRelativePath)) {
                            Storage::disk('public')->delete($cleanRelativePath);
                        }
                    }

                    // Simpan file baru
                    $filename = time() . '_' . $inputKey . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('berkas_peserta/' . $user->id, $filename, 'public');

                    // Update atau Buat Record Baru
                    BerkasPeserta::updateOrCreate(
                        ['user_id' => $user->id, 'jenis_berkas' => $jenisBerkas],
                        ['nama_file' => $file->getClientOriginalName(), 'file_path' => $path]
                    );
                }
            }

            DB::commit();

            // Return data user ter-update beserta relasinya
            $updatedUser = User::with(['berkas', 'token', 'riwayatUjian.detailJawaban.soal'])->find($user->id);

            return response()->json([
                'status'  => 'success',
                'message' => 'Data profil & berkas Anda berhasil diperbarui!',
                'user'    => $updatedUser
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal memperbarui profil: ' . $e->getMessage()
            ], 500);
        }
    }
}
