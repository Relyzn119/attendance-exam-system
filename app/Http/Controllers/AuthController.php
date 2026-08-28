<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // Validasi Input Data Peserta
        $request->validate([
            'nama'          => 'required|string|max:255',
            'email'         => 'required|string|email|unique:users',
            'password'      => 'required|string|min:6',
            'jenis_kelamin' => 'required|in:L,P',
            'jabatan'       => 'required|string|max:255',
            'alamat'        => 'required|string',
            'no_hp'         => 'required|string|max:20',
            'nik'           => 'required|string|unique:users|max:20',
            'npwp'          => 'nullable|string|max:30',
        ]);

        try {
            $user = User::create([
                'nama'          => $request->nama,
                'email'         => $request->email,
                'password'      => Hash::make($request->password),
                'role'          => 'peserta',
                'jenis_kelamin' => $request->jenis_kelamin,
                'jabatan'       => $request->jabatan,
                'alamat'        => $request->alamat,
                'no_hp'         => $request->no_hp,
                'nik'           => $request->nik,
                'npwp'          => $request->npwp ?? null,
            ]);

            return response()->json([
                'status'  => 'success',
                'message' => 'Pendaftaran berhasil! Silakan login untuk masuk ke Dashboard Peserta.'
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Terjadi kesalahan saat pendaftaran: ' . $e->getMessage()
            ], 500);
        }
    }

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
            'token',
            'riwayatUjian' => function($query) {
                $query->orderBy('id', 'desc')->with('detailJawaban.soal');
            }
        ])->findOrFail($id);

        return response()->json($user);
    }

    // EDIT DATA DIRI PESERTA
    public function updateProfile(Request $request, $id)
    {
        $user = User::where('role', 'peserta')->findOrFail($id);

        $request->validate([
            'nama'          => 'required|string|max:255',
            'email'         => 'required|string|email|max:255|unique:users,email,' . $id,
            'nik'           => 'required|string|max:20|unique:users,nik,' . $id,
            'jenis_kelamin' => 'required|in:L,P',
            'jabatan'       => 'required|string|max:255',
            'no_hp'         => 'required|string|max:20',
            'alamat'        => 'required|string',
            'npwp'          => 'nullable|string|max:30',
        ]);

        try {
            $user->update([
                'nama'          => $request->nama,
                'email'         => $request->email,
                'nik'           => $request->nik,
                'jenis_kelamin' => $request->jenis_kelamin,
                'jabatan'       => $request->jabatan,
                'no_hp'         => $request->no_hp,
                'alamat'        => $request->alamat,
                'npwp'          => $request->npwp ?? null,
            ]);

            $updatedUser = User::with(['token', 'riwayatUjian.detailJawaban.soal'])->find($user->id);

            return response()->json([
                'status'  => 'success',
                'message' => 'Data profil Anda berhasil diperbarui!',
                'user'    => $updatedUser
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal memperbarui profil: ' . $e->getMessage()
            ], 500);
        }
    }

    // GANTI PASSWORD USER (ADMIN & PESERTA)
    public function changePassword(Request $request, $id)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password'     => 'required|string|min:6|confirmed',
        ]);

        $user = User::findOrFail($id);

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Password lama yang Anda masukkan tidak sesuai!'
            ], 400);
        }

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Password Anda berhasil diperbarui!'
        ]);
    }
}
