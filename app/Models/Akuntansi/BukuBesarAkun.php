<?php

namespace App\Models\Akuntansi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BukuBesarAkun extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'bukubesar_akuntansi';
    protected $fillable = [
        'kategoriAkuntansi', 'kodeAkun', 'keterangan'
    ];
}
