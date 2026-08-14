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
    Schema::create('bank_soals', function (Blueprint $table) {
        $table->id();
        $table->text('soal');
        $table->string('opsi_a');
        $table->string('opsi_b');
        $table->string('opsi_c');
        $table->string('opsi_d');
        $table->enum('kunci_jawaban', ['A', 'B', 'C', 'D']);
        // is_selected: Penanda 25 soal yang dipilih Admin untuk dijadikan Soal Ujian
        $table->boolean('is_selected')->default(false); 
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_soals');
    }
};
