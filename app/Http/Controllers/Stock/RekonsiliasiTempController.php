<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use App\Models\Stock\RekonsiliasiDetilTemp;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class RekonsiliasiTempController extends Controller
{
    public function store(Request $request)
    {
        $data = [
            'idProduk'=>$request->idProduk,
            'idTemp'=>$request->idTemp,
            'jumlah'=>$request->jumlah,
        ];
        $store = RekonsiliasiDetilTemp::updateOrCreate(['id'=>$request->idDetil], $data);
        return response()->json(['status'=>true, 'keterangan'=>$store]);
    }

    public function edit($id)
    {
        $data = RekonsiliasiDetilTemp::getDataAll($id);
        return response()->json($data);
    }

    public function destroy($id)
    {
        $data = RekonsiliasiDetilTemp::destroy($id);
        return response()->json(['status'=>true, 'keterangan'=>$data]);
    }

    public function tableTemp($id)
    {
        $data = RekonsiliasiDetilTemp::where('idTemp',$id)
                ->leftJoin('produk', 'rekonsiliasi_detil_temp.idProduk', '=', 'produk.id_produk')
                ->orderBy('id', 'asc')
                ->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('namaBarang', function($row){
                return $row->nama_produk;
            })
            ->addColumn('Actions', function ($row){
                $btnEdit = '<button class="btn btn-sm btn-clean btn-icon" id="btnEdit" data-value="'.$row->id.'" title="Edit details"><i class="la la-edit"></i></button>';
                $btnSoft = '<button class="btn btn-sm btn-clean btn-icon" id="btnSoft" data-value="'.$row->id.'" title="Delete"><i class="la la-trash"></i></button>';
                return $btnEdit.$btnSoft;
            })
            ->rawColumns(['namaBarang', 'Actions'])
            ->make(true);
    }
}
