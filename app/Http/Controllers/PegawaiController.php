<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\BerkasPegawai;
use Illuminate\Http\Request;

class PegawaiController extends Controller
{
    // Mengambil Data Pegawai + Filter Tanggal Upload
    public function index(Request $request)
    {
        $query = Pegawai::with('berkas');

        // Filter berdasarkan tanggal upload jika dipilihi oleh admin
        if ($request->has('tanggal_upload') && $request->tanggal_upload != '') {
            $query->whereDate('tanggal_upload', $request->tanggal_upload);
        }

        return response()->json($query->latest()->get());
    }

    // Detail Pegawai beserta Dokumen Transkrip, STR, Ijazah
    public function show($id)
    {
        $pegawai = Pegawai::with('berkas')->findOrFail($id);
        return response()->json($pegawai);
    }

    // Upload Berkas PDF oleh HRD
    public function uploadBerkas(Request $request, $pegawaiId)
    {
        $request->validate([
            'jenis_berkas' => 'required', // Contoh: STR / Transkrip Nilai / Ijazah
            'file' => 'required|mimes:pdf|max:5000' // Maksimal 5MB PDF
        ]);

        $file = $request->file('file');
        $path = $file->store('berkas_pegawai', 'public');

        $berkas = BerkasPegawai::create([
            'pegawai_id' => $pegawaiId,
            'jenis_berkas' => $request->jenis_berkas,
            'nama_file' => $file->getClientOriginalName(),
            'file_path' => $path,
            'tanggal_upload' => now()
        ]);

        return response()->json(['message' => 'Berkas PDF Berhasil Diupload!', 'berkas' => $berkas]);
    }
}