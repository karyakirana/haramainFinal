<?php

namespace App\Models\Stock;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockAkhirMaster extends Model
{
    use HasFactory;

    protected $table = 'stockakhir_master';
    protected $fillable = [
        'kode', 'branchId', 'tglinput',
        'pencatat', 'idPembuat', 'keterangan'
    ];
}
