<?php

namespace App\Models\Stock;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekonsiliasiDetil extends Model
{
    use HasFactory;
    protected $table = 'rekonsiliasi_branch_detil';
    protected $fillable =[
        'idRekonsiliasi', 'idProduk', 'jumlah'
    ];
}
