<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\BerkasPeserta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // 1. Validasi Input Data & File
        $request->validate([
            'nama'          => 'required|string|max:255',
            'email'         => 'required|string|email|unique:users',
            'password'      => 'required|string|min:6',
            'jenis_kelamin' => 'required|in:L,P',
            'alamat'        => 'required|string',
            'no_hp'         => 'required|string|max:20',
            'nik'           => 'required|string|unique:users|max:20',
            'npwp'          => 'nullable|string|max:30',

            // Validasi File Wajib (Max 5MB per file: PDF/JPG/PNG)
            'file_kk'            => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'file_ktp'           => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'file_ijazah'        => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'file_transkrip'     => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'file_cv'            => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'file_surat_lamaran' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',

            // File Opsional
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
                'file_kk'            => 'Kartu Keluarga (KK)',
                'file_ktp'           => 'KTP',
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
        $user = User::with(['berkas', 'token', 'riwayatUjian.detailJawaban.soal'])->findOrFail($id);
        return response()->json($user);
    }
}
