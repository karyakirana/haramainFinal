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
}