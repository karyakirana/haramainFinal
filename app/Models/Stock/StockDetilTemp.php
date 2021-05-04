<?php

namespace App\Models\Stock;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockDetilTemp extends Model
{
    use HasFactory;

    protected $table = 'stock_detil_temp';
    protected $fillable = [
        'stockTemp', 'idProduk', 'jumlah'
    ];
}
