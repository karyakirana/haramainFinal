<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kategori extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'kategori';

    protected $fillable = [
        'id_kategori',
        'id_lokal',
        'nama',
        'keterangan',
    ];
}
