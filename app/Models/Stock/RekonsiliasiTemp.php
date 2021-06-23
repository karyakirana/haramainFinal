<?php

namespace App\Models\Stock;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekonsiliasiTemp extends Model
{
    use HasFactory;

    protected $table = 'rekonsiliasi_temp';
    protected $fillable =[
        'kode', 'tglBuat', 'branchIdAsal', 'branchIdAkhir',
        'pembuat', 'nomorPo'
    ];
}
