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

    public function getAllData()
    {
        $data = DB::table('stock_akhir as sa')
            ->leftJoin('branchId as b')
            ->leftJoin('produk as p')
            ->select(
                'activeCash',
                'b.branchName as branch',
                'id_produk', 'p.nama_produk as produkName',
                'jumlah_stock'
            )
            ->orderBy('id_produk', 'asc');
        return $data->get();
    }
}
