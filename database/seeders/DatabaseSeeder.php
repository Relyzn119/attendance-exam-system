<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\BankSoal;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat / Update Akun Admin (Aman dari Duplicate Entry Error)
        User::updateOrCreate(
            ['email' => 'admin@bundathamrin.com'], // Kunci Pencarian Unik
            [
                'nama'          => 'Admin Diklat RSU Bunda Thamrin',
                'password'      => Hash::make('password'),
                'role'          => 'admin',
                'jenis_kelamin' => 'L',
                'alamat'        => 'Jl. Sei Batang Hari No.28, Medan',
                'no_hp'         => '081234567890',
                'nik'           => '1234567890000001',
            ]
        );

        // 2. Buat 25 Contoh Soal Ujian Diklat (Hanya diisi jika Bank Soal masih kosong)
        if (BankSoal::count() === 0) {
            for ($i = 1; $i <= 25; $i++) {
                BankSoal::create([
                    'soal'          => "Pertanyaan Soal Diklat No. {$i}: Apakah prosedur K3 di RSU Bunda Thamrin wajib dilaksanakan oleh seluruh staf?",
                    'opsi_a'        => "Wajib dilaksanakan dengan penuh tanggung jawab",
                    'opsi_b'        => "Hanya opsional jika ada waktu luang",
                    'opsi_c'        => "Tidak wajib dilaksanakan",
                    'opsi_d'        => "Khusus dokter dan perawat saja",
                    'kunci_jawaban' => "A",
                    'is_selected'   => true
                ]);
            }
        }
    }
}