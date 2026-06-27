<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    protected $table = 'buku';

    protected $fillable = [
        'isbn',
        'judul',
        'pengarang',
        'penerbit',
        'tahun_terbit',
        'kategori',
        'stok',
        'cover_image',
        'lokasi_rak',
        'sinopsis',
    ];

    public function peminjamans()
    {
        return $this->hasMany(Peminjaman::class);
    }
}
