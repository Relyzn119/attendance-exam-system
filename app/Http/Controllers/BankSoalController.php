<?php

namespace App\Http\Controllers;

use App\Models\BankSoal;
use Illuminate\Http\Request;

class BankSoalController extends Controller
{
    // 1. Ambil Semua Soal di Bank Soal
    public function index()
    {
        return response()->json(BankSoal::latest()->get());
    }

    // 2. Tambah Soal Baru ke Bank Soal
    public function store(Request $request)
    {
        $request->validate([
            'soal'          => 'required|string',
            'opsi_a'        => 'required|string',
            'opsi_b'        => 'required|string',
            'opsi_c'        => 'required|string',
            'opsi_d'        => 'required|string',
            'kunci_jawaban' => 'required|in:A,B,C,D',
        ]);

        $soal = BankSoal::create($request->all());

        return response()->json([
            'status'  => 'success',
            'message' => 'Soal berhasil ditambahkan ke Bank Soal!',
            'data'    => $soal
        ]);
    }

    // 3. Update 25 Soal Terpilih untuk Ujian (Sistem Centang)
    public function updateSelection(Request $request)
    {
        $request->validate([
            'selected_ids' => 'required|array',
        ]);

        // Reset semua soal menjadi false terlebih dahulu
        BankSoal::query()->update(['is_selected' => false]);

        // Set is_selected = true untuk ID soal yang dicentang Admin
        BankSoal::whereIn('id', $request->selected_ids)->update(['is_selected' => true]);

        return response()->json([
            'status'  => 'success',
            'message' => count($request->selected_ids) . ' Soal berhasil diset sebagai Soal Ujian!'
        ]);
    }

    // 4. Hapus Soal
    public function destroy($id)
    {
        BankSoal::findOrFail($id)->delete();
        return response()->json(['message' => 'Soal berhasil dihapus!']);
    }
}