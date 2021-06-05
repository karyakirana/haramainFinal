<?php

namespace App\Models\Akuntansi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriAkun extends Model
{
    use HasFactory;

    protected $table = 'kategori_akuntansi';
    protected $fillable = [
        'namaAkun', 'deskripsi'
    ];
}
