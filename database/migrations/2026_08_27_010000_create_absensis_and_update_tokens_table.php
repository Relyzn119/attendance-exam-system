<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tambah tipe_token ke token_absensis jika belum ada
        if (!Schema::hasColumn('token_absensis', 'tipe_token')) {
            Schema::table('token_absensis', function (Blueprint $table) {
                $table->enum('tipe_token', ['absensi', 'ujian'])->default('ujian')->after('kode_token');
            });
        }

        // 2. Buat tabel absensis untuk riwayat log kehadiran harian
        if (!Schema::hasTable('absensis')) {
            Schema::create('absensis', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->foreignId('token_id')->nullable()->constrained('token_absensis')->onDelete('set null');
                $table->enum('tipe_token', ['absensi', 'ujian'])->default('absensi');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('absensis');

        if (Schema::hasColumn('token_absensis', 'tipe_token')) {
            Schema::table('token_absensis', function (Blueprint $table) {
                $table->dropColumn('tipe_token');
            });
        }
    }
};
