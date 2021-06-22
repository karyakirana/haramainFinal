<?php

namespace App\Models\Stock;

use App\Models\Master\Produk;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class InventoryReal extends Model
{
    use HasFactory;
    protected $table = 'inventory_real';
    protected $fillable = [
        'idProduk', 'branchId', 'stockIn', 'stockOut', 'stockNow'
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'idProduk', 'id_produk');
    }

    public function branch()
    {
        return $this->belongsTo(BranchStock::class, 'branchId');
    }

    public function allForeignTable()
    {
        $data = DB::table('inventory_real as ir')
            ->leftJoin('produk as p')
            ->leftJoin('branch_stock as b')
            ->select(
                'ir.idProduk as idProduk', 'p.nama_produk as produkName',
                'branchId', 'b.branchName as branchName',
                'stockIn', 'stockOut', 'stockNow'
            )
            ->orderBy('idProduk', 'asc');
        return $data->get();
    }
}
