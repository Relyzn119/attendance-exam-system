<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailJawabanUjian extends Model
{
    // Mengizinkan seluruh kolom (termasuk riwayat_ujian_id, soal_id, jawaban_user, is_benar) diisi secara massal
    protected $guarded = ['id'];

    public function riwayatUjian()
    {
        return $this->belongsTo(RiwayatUjian::class, 'riwayat_ujian_id');
    }

    public function soal()
    {
        return $this->belongsTo(BankSoal::class, 'soal_id');
    }
}