<?php

namespace App\Models\Stock;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockMasuk extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'stockmasuk';
    protected $fillable = [
        'kode', 'idSupplier', 'idUser',
        'tglMasuk', 'nomorPo', 'keterangan'
    ];
}
