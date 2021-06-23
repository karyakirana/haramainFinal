<?php

namespace App\Models\Stock;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekonsiliasiMaster extends Model
{
    use HasFactory;

    protected $table = 'rekonsiliasi_branch';
    protected $fillable =[
        'kode', 'tglBuat', 'branchIdAsal', 'branchIdAkhir',
        'pembuat', 'nomorPo', 'keterangan'
    ];
}
