<?php

namespace App\Models\Stock;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekonsiliasiDetilTemp extends Model
{
    use HasFactory;
    protected $table = 'rekonsiliasi_detil_temp';
    protected $fillable = [
        'idTemp', 'idProduk', 'jumlah'
    ];

    public function scopeGetDataAll($id)
    {
        $data = $this->leftJoin('produk', 'rekonsiliasi_detil_temp.idProduk', '=', 'produk.id_produk')
            ->leftJoin('kategori as k', 'produk.id_kategori', '=', 'k.id_kategori')
            ->leftJoin('kategori_harga as kh', 'produk.id_kat_harga', '=', 'kh.id_kat_harga')
            ->select('id', 'produk.id_produk as produkId', 'k.id_lokal as idLokal', 'k.nama as kategori', 'kh.nama_kat as kategoriHarga','size',
                'penerbit', 'hal', 'cover', 'nama_produk', 'jumlah', 'produk.deskripsi', 'produk.created_at', 'produk.kode_lokal as kLokal', 'stock' )
            ->orderBy('idProduk', 'desc')
            ->find($id);
        return $data;
    }
}
