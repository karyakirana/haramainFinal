<?php

namespace App\Models\Transaksi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenjualanDetilTemp extends Model
{
    use HasFactory;

    protected $table = 'detil_penjualan_temp';
    protected $fillable = [
        'idPenjualanTemp',
        'idBarang',
        'jumlah',
        'harga',
        'diskon',
        'sub_total',
    ];
}
