<?php

namespace App\Http\Controllers;

use App\Models\BankSoal;
use Illuminate\Http\Request;

class BankSoalController extends Controller
{
    // 1. Ambil Semua Soal di Bank Soal + Current UjianSetting
    public function index()
    {
        $setting = \App\Models\UjianSetting::first() ?? [
            'model_ujian'       => 'manual',
            'tipe_acak'         => 'semua_sama',
            'jumlah_soal'       => 25,
            'tingkat_kesulitan' => 'normal',
        ];

        return response()->json([
            'soal'    => BankSoal::latest()->get(),
            'setting' => $setting
        ]);
    }

    // 2. Tambah Soal Baru ke Bank Soal
    public function store(Request $request)
    {
        $request->validate([
            'soal'              => 'required|string',
            'opsi_a'            => 'required|string',
            'opsi_b'            => 'required|string',
            'opsi_c'            => 'required|string',
            'opsi_d'            => 'required|string',
            'kunci_jawaban'     => 'required|in:A,B,C,D',
            'tingkat_kesulitan' => 'nullable|in:tidak_ada,mudah,normal,sulit',
        ]);

        $data = $request->all();
        if (empty($data['tingkat_kesulitan'])) {
            $data['tingkat_kesulitan'] = 'tidak_ada';
        }

        $soal = BankSoal::create($data);

        return response()->json([
            'status'  => 'success',
            'message' => 'Soal berhasil ditambahkan ke Bank Soal!',
            'data'    => $soal
        ]);
    }

    // 3. Update Soal Terpilih Manual (Model 1)
    public function updateSelection(Request $request)
    {
        $request->validate([
            'selected_ids' => 'required|array',
        ]);

        // Reset semua soal menjadi false terlebih dahulu
        BankSoal::query()->update(['is_selected' => false]);

        // Set is_selected = true untuk ID soal yang dicentang Admin
        BankSoal::whereIn('id', $request->selected_ids)->update(['is_selected' => true]);

        // Set model_ujian = 'manual'
        $setting = \App\Models\UjianSetting::first() ?? new \App\Models\UjianSetting();
        $setting->model_ujian = 'manual';
        $setting->save();

        return response()->json([
            'status'  => 'success',
            'message' => count($request->selected_ids) . ' Soal berhasil diset sebagai Soal Ujian (Pemilihan Manual)!',
            'setting' => $setting
        ]);
    }

    // 4. Service helper for randomizing questions by difficulty criteria
    public static function pickRandomQuestions($totalGoal, $difficultyLevel)
    {
        if ($difficultyLevel === 'mudah') {
            $countMudah  = (int) round($totalGoal * 0.50);
            $countNormal = (int) round($totalGoal * 0.30);
            $countSulit  = $totalGoal - $countMudah - $countNormal;
        } elseif ($difficultyLevel === 'sulit') {
            $countSulit  = (int) round($totalGoal * 0.50);
            $countNormal = (int) round($totalGoal * 0.30);
            $countMudah  = $totalGoal - $countSulit - $countNormal;
        } else {
            $countNormal = (int) round($totalGoal * 0.50);
            $countSulit  = (int) round($totalGoal * 0.30);
            $countMudah  = $totalGoal - $countNormal - $countSulit;
        }

        $mudahPool  = BankSoal::where('tingkat_kesulitan', 'mudah')->inRandomOrder()->get();
        $normalPool = BankSoal::where('tingkat_kesulitan', 'normal')->inRandomOrder()->get();
        $sulitPool  = BankSoal::where('tingkat_kesulitan', 'sulit')->inRandomOrder()->get();

        $selectedMudah  = $mudahPool->take($countMudah);
        $selectedNormal = $normalPool->take($countNormal);
        $selectedSulit  = $sulitPool->take($countSulit);

        $mergedIds = $selectedMudah->pluck('id')
            ->merge($selectedNormal->pluck('id'))
            ->merge($selectedSulit->pluck('id'))
            ->unique();

        if ($mergedIds->count() < $totalGoal) {
            $remainingNeeded = $totalGoal - $mergedIds->count();
            $fillPool = BankSoal::whereIn('tingkat_kesulitan', ['mudah', 'normal', 'sulit'])
                ->whereNotIn('id', $mergedIds)
                ->inRandomOrder()
                ->take($remainingNeeded)
                ->pluck('id');

            $mergedIds = $mergedIds->merge($fillPool)->unique();
        }

        return $mergedIds;
    }

    // 5. Process Acak Soal (Model 2)
    public function processAcakSoal(Request $request)
    {
        $request->validate([
            'tipe_acak'         => 'required|in:per_peserta,semua_sama',
            'jumlah_soal'       => 'required|integer|min:1',
            'tingkat_kesulitan' => 'required|in:mudah,normal,sulit',
        ]);

        $setting = \App\Models\UjianSetting::first() ?? new \App\Models\UjianSetting();
        $setting->model_ujian       = 'acak';
        $setting->tipe_acak         = $request->tipe_acak;
        $setting->jumlah_soal       = (int) $request->jumlah_soal;
        $setting->tingkat_kesulitan = $request->tingkat_kesulitan;
        $setting->save();

        if ($request->tipe_acak === 'semua_sama') {
            $pickedIds = self::pickRandomQuestions($setting->jumlah_soal, $setting->tingkat_kesulitan);

            if ($pickedIds->isEmpty()) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Tidak ada soal dengan tingkat kesulitan (Mudah, Normal, Sulit) di Bank Soal!'
                ], 400);
            }

            BankSoal::query()->update(['is_selected' => false]);
            BankSoal::whereIn('id', $pickedIds)->update(['is_selected' => true]);

            return response()->json([
                'status'  => 'success',
                'message' => "Berhasil mengacak {$pickedIds->count()} Soal untuk semua peserta (Semua Peserta Mendapatkan Soal Sama)!",
                'setting' => $setting
            ]);
        }

        return response()->json([
            'status'  => 'success',
            'message' => "Pengaturan Acak Soal Per Peserta berhasil disimpan! Setiap peserta akan mendapatkan kombinasi {$setting->jumlah_soal} soal unik saat ujian.",
            'setting' => $setting
        ]);
    }

    // 6. Hapus Soal
    public function destroy($id)
    {
        BankSoal::findOrFail($id)->delete();
        return response()->json(['message' => 'Soal berhasil dihapus!']);
    }
}