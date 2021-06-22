<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

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

    public function getAllData()
    {
        $data = DB::table('produk as p')
            ->leftJoin('kategori as k', 'k.id_kategori', '=', 'p,id_kategori')
            ->leftJoin('kategori_harga as kh', 'kh.id_kat_harga', '=', 'p.id_kat_harga')
            ->select([
                'id_produk', 'nama_produk',
                'id_lokal', 'k.nama as kategoriName',
                'kh.nama_kat as kategoriHargaName',
                'kode_lokal', 'hal', 'cover', 'harga'
            ]);
        return $data->get();
    }
}
