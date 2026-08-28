<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sertifikat_settings', function (Blueprint $table) {
            $table->id();
            $table->string('nama_direktur')->nullable();
            $table->string('nama_pembicara')->nullable();
            $table->enum('tipe_ttd', ['digital', 'basah'])->default('digital');
            $table->string('ttd_direktur')->nullable();
            $table->string('ttd_pembicara')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sertifikat_settings');
    }
};
