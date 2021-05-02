<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Produk extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "produk";
    protected $fillable = [
        'id_produk', 'id_kategori',
        'kode_lokal', 'penerbit', 'nama_produk',
        'stock', 'hal', 'cover',
        'id_kat_harga', 'harga', 'size',
        'deskripsi',
    ];
}
