<?php

namespace App\Models\Stock;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class StockAkhir extends Model
{
    use HasFactory;

    protected $table = 'stockakhir';
    protected $fillable = [
        'activeCash', 'branchId', 'id_produk', 'jumlah_stock'
    ];

    public function scopeGetAllData()
    {
        $data = DB::table('stockakhir as sa')
            ->leftJoin('branch_stock as b', 'b.id', '=', 'sa.branchId')
            ->leftJoin('produk as p', 'p.id_produk', '=', 'sa.id_produk')
            ->select(
                'activeCash',
                'b.branchName as branch',
                'p.id_produk as idProduk', 'p.nama_produk as produkName',
                'jumlah_stock'
            )
            ->orderBy('idProduk', 'asc');
        return $data->get();
    }
}
