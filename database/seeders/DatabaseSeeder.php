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

        // 2. Jalankan Seeder Khusus Bank Soal Ujian Diklat Rumah Sakit
        $this->call(BankSoalSeeder::class);
    }
}