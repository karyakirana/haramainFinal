<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KategoriHarga extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'kategori_harga';

    protected $fillable = [
        'id_kat_harga',
        'nama_kat',
        'keterangan'
    ];
}
