<?php

namespace App\Models\Stock;

use App\Models\Master\Supplier;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockMasuk extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'stockmasuk';
    protected $fillable = [
        'kode', 'idBranch','idSupplier', 'idUser',
        'tglMasuk', 'nomorPo', 'keterangan'
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'idBranch');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'idSupplier');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'idUser');
    }
}
