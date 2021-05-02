<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'Supplier';
    protected $fillable = [
        'kodeSupplier', 'jenisSupplier',
        'namaSupplier', 'alamatSupplier',
        'tlpSupplier', 'npwpSupplier',
        'npwpSupplier', 'emailSupplier',
        'keteranganSupplier'
    ];
}
