<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use App\Models\Master\Produk;
use App\Models\Stock\StockAkhir;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class StockAkhirController extends Controller
{
    public function index()
    {
        return view('pages.stock.stockAkhir');
    }

    public function stockAkhirList()
    {
        $data = StockAkhir::getAllData();
        return DataTables::of($data)->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'branchId'=>'required',
            'jumlah'=>'required|int'
        ]);

        $data = [
            'activeCash'=>session('ClosedCash'),
            'branchId'=>$request->branchId,
            'id_produk'=>$request->idProduk,
            'jumlah_stock'=>$request->jumlah
        ];

        $store = StockAkhir::updateOrCreate(['id'=>$request->id], $data);
        return response()->json(['status'=>true, '$hasil'=>$store]);
    }

    public function edit($id)
    {
        $data = StockAkhir::find($id);
        return response()->json($data);
    }

    public function destroy($id)
    {
        $data = stockAkhir::destroy($id);
        return response()->json($data);
    }

    public function tableProduk(Request $request)
    {
        $data = Produk::getAllData();
        return DataTables::of($data)
            ->addColumn('Action', function($row){
                $btnEdit = '<a href="#" class="btn btn-sm btn-clean btn-icon" id="btnProduk" data-value="'.$row->id_cust.'" title="Edit"><i class="la la-edit"></i></a>';
                return $btnEdit;
            })
            ->addRawColumns(['Action'])
            ->make(true);
    }
}
