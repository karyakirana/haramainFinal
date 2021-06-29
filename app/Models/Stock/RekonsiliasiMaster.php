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

    public function scopeGenerateKode()
    {
        $data = $this->latest('id')->first();
        $num = null;
        if(!$data){
            $num = 1;
        } else {
            $urutan = (int) substr($data->kode, 0, 4);
            $num = $urutan + 1;
        }
        $id = sprintf("%04s", $num)."/SR/".date('Y');
        return $id;
    }
}
