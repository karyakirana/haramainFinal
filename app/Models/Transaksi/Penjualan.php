<?php

namespace App\Models\Transaksi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Penjualan extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "penjualan";
    protected $fillable = [
        'id_jual', // nomor nota
        'id_cust',
        'id_user',
        'tgl_nota',
        'tgl_tempo',
        'status_bayar',
        'sudahBayar',
        'total_jumlah',
        'ppn',
        'biaya_lain',
        'total_bayar',
        'keterangan',
        'activeCash',
    ];
}
