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
        'idBranch',
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

    public function scopeDaftarPenjualan()
    {
        $data = Penjualan::leftJoin('user as u', 'penjualan.id_user', '=', 'u.id_user')
            ->leftJoin('users', 'penjualan.id_user', '=', 'users.idUserOld')
            ->leftJoin('customer as c', 'penjualan.id_cust', '=', 'c.id_cust')
            ->select(
                'penjualan.id_jual as penjualanId',
                'c.nama_cust as namaCustomer',
                'tgl_nota',
                'tgl_tempo',
                'status_bayar',
                'sudahBayar',
                'total_jumlah',
                'ppn',
                'biaya_lain',
                'total_bayar',
                'penjualan.keterangan as penket',
                'u.username as namaSales1',
                'users.name as namaSales2',
                'print', // jumlah print
                'penjualan.updated_at as update', // last print
            )
            ->orderBy('penjualan.tgl_nota', 'desc');
        return $data->get();
    }
}
