<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SertifikatSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_direktur',
        'nama_pembicara',
        'tipe_ttd',
        'use_bg_watermark',
        'ttd_direktur',
        'ttd_pembicara',
    ];
}
