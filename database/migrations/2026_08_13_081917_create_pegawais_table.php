<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pegawais', function (Blueprint $table) {
            $table->id();
            $table->string('nik')->unique();
            $table->string('nama_lengkap');
            // Kategori peran: 'Dokter', 'Perawat', 'Penunjang Medis', 'Staf Administrasi'
            $table->string('kategori_peran');
            $table->string('unit_departemen');
            $table->string('email_resmi')->nullable();
            $table->string('no_hp')->nullable();
            $table->string('pendidikan_terakhir')->nullable();
            $table->string('no_str')->nullable();
            $table->string('no_sip')->nullable();
            $table->date('tanggal_upload');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pegawais');
    }
};
