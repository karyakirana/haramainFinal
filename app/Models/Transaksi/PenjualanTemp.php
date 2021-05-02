<?php

namespace App\Models\Transaksi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenjualanTemp extends Model
{
    use HasFactory;

    protected $table = 'penjualan_temp';
    protected $fillable = [
        'jenisTemp',
        'id_jual',
        'idSales',
    ];
}
