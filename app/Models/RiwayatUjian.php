<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatUjian extends Model
{
    // Mengizinkan seluruh kolom (kecuali ID) diisi secara massal
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function token()
    {
        return $this->belongsTo(TokenAbsensi::class, 'token_id');
    }

    public function detailJawaban()
    {
        return $this->hasMany(DetailJawabanUjian::class, 'riwayat_ujian_id');
    }
}