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
    Schema::create('detail_jawaban_ujians', function (Blueprint $table) {
        $table->id();
        $table->foreignId('riwayat_ujian_id')->constrained('riwayat_ujians')->onDelete('cascade');
        $table->foreignId('soal_id')->constrained('bank_soals')->onDelete('cascade');
        $table->enum('jawaban_user', ['A', 'B', 'C', 'D'])->nullable();
        $table->boolean('is_benar')->default(false);
        $table->integer('durasi_detik')->default(0); // Menyimpan durasi pengerjaan soal ini
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_jawaban_ujians');
    }
};
