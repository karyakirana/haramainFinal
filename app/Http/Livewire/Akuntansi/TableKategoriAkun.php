<?php

namespace App\Http\Livewire\Akuntansi;

use App\Models\Akuntansi\KategoriAkun;
use Livewire\Component;

class TableKategoriAkun extends Component
{
    public function render()
    {
        $dataKategoriAkun = KategoriAkun::all();
        return view('livewire.akuntansi.table-kategori-akun', ['kategoriAkun'=>$dataKategoriAkun]);
    }
}
