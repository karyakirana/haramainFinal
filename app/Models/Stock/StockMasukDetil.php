<?php

namespace App\Models\Stock;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockMasukDetil extends Model
{
    use HasFactory;

    protected $table='stockmasukdetil';
    protected $fillable = [
        'idStockMasuk', 'idProduk', 'jumlah'
    ];
}
