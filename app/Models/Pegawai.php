<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    use HasFactory;

    protected $table = 'pegawais';

    protected $fillable = [
        'nik',
        'nama_lengkap',
        'kategori_peran',
        'unit_departemen',
        'email_resmi',
        'no_hp',
        'pendidikan_terakhir',
        'no_str',
        'no_sip',
        'tanggal_upload',
    ];

    /**
     * Relasi One-to-Many ke BerkasPegawai
     */
    public function berkasPegawais()
    {
        return $this->hasMany(BerkasPegawai::class, 'pegawai_id');
    }
}