<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ujian_settings', function (Blueprint $table) {
            $table->id();
            $table->enum('model_ujian', ['manual', 'acak'])->default('manual');
            $table->enum('tipe_acak', ['per_peserta', 'semua_sama'])->default('semua_sama');
            $table->integer('jumlah_soal')->default(25);
            $table->enum('tingkat_kesulitan', ['mudah', 'normal', 'sulit'])->default('normal');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ujian_settings');
    }
};
