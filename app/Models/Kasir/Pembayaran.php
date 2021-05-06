<?php

namespace App\Models\Kasir;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pembayaran extends Model
{
    use HasFactory;
//    use SoftDeletes;

    protected $table = 'pembayaran';
    protected $fillable = [
        'kode',
        'kodeInternal',
        'idPenjualan',
        'jenisBayar',
        'tglPembayaran',
        'idUser',
        'nominal',
        'keterangan'
    ];
}
