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
        $table->string('nik_nip')->unique();
        $table->string('nama_lengkap');
        $table->enum('jenis_pegawai', ['kontrak', 'tetap', 'dokter']); 
        $table->string('jabatan'); 
        $table->string('unit_kerja'); 
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
