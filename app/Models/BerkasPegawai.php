<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BerkasPegawai extends Model
{
    use HasFactory;

    protected $table = 'berkas_pegawais';

    protected $fillable = [
        'pegawai_id',
        'jenis_berkas',
        'judul_dokumen',
        'nama_file',
        'file_path',
        'file_size',
        'catatan_hrd',
        'tanggal_upload',
    ];

    /**
     * Relasi Belongs-To ke Pegawai
     */
    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id');
    }
}