<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $guarded = ['id'];

    public function berkas()
    {
        return $this->hasMany(BerkasPeserta::class);
    }

    public function token()
    {
        return $this->hasOne(TokenAbsensi::class);
    }

    public function riwayatUjian()
    {
        return $this->hasMany(RiwayatUjian::class);
    }
}