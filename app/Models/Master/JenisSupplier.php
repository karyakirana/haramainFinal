<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JenisSupplier extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'jenissupplier';
    protected $fillable = [
        'jenis', 'keterangan'
    ];
}
