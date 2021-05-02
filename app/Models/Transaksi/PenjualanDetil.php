<?php

namespace App\Models\Transaksi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenjualanDetil extends Model
{
    use HasFactory;

    protected $table = 'detil_penjualan';
    protected $fillable = [
        'id_jual',
        'id_produk',
        'jumlah',
        'harga',
        'diskon',
        'sub_total',
    ];
}
