<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Anggota extends Model
{
    protected $table = 'anggota';

    protected $fillable = [
        'user_id',
        'nama_lengkap',
        'nik',
        'alamat',
        'no_telepon',
        'email',
        'tgl_registrasi',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function peminjamans()
    {
        return $this->hasMany(Peminjaman::class);
    }
}
