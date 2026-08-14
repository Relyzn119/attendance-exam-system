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
    Schema::create('riwayat_ujians', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        $table->foreignId('token_id')->constrained('token_absensis')->onDelete('cascade');
        $table->integer('total_soal')->default(25);
        $table->integer('jawaban_benar')->default(0);
        $table->integer('jawaban_salah')->default(0);
        $table->decimal('nilai_akhir', 5, 2)->default(0);
        $table->string('nomor_sertifikat')->nullable();
        $table->dateTime('waktu_mulai');
        $table->dateTime('waktu_selesai')->nullable();
        $table->enum('status', ['berlangsung', 'selesai'])->default('berlangsung');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_ujians');
    }
};
