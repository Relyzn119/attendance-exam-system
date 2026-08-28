<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UjianSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'model_ujian',
        'tipe_acak',
        'jumlah_soal',
        'tingkat_kesulitan',
    ];
}
