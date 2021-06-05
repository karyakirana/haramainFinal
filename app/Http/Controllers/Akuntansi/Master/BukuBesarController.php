<?php

namespace App\Http\Controllers\Akuntansi\Master;

use App\Http\Controllers\Controller;
use App\Models\Akuntansi\BukuBesarAkun;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class BukuBesarController extends Controller
{
    public function index()
    {
        return view('pages.akuntansi.bukuBesarNomor');
    }

    public function daftarBukuBesar()
    {
        $data = BukuBesarAkun::all('*')->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('Actions', function($row){
                $btnEdit = '<a href="#" class="btn btn-sm btn-clean btn-icon" id="btnEdit" data-value="'.$row->id.'" title="Edit details"><i class="la la-edit"></i></a>';
                $btnSoft = '<a href="#" class="btn btn-sm btn-clean btn-icon" id="btnSoft" data-value="'.$row->id.'" title="Delete"><i class="la la-trash"></i></a>';
                return $btnEdit.$btnSoft;
            })
            ->rawColumns(['Actions'])
            ->make(true);
    }
}
