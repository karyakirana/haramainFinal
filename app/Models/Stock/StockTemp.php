<?php

namespace App\Models\Stock;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockTemp extends Model
{
    use HasFactory;

    protected $table = 'stock_temp';
    protected $fillable = [
        'jenisTemp', // jenis transaksi
        'stockMasuk', // idStock jika digunakan untuk edit data
        'idSupplier',
        'idUser', // pengguna data
        'tglMasuk',
        'nomorPo',
        'keterangan',
    ];
}
