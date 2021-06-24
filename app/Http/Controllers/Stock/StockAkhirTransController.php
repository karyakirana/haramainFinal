<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use App\Models\Stock\StockAkhirDetil;
use App\Models\Stock\StockAkhirMaster;
use App\Models\Stock\StockDetilTemp;
use App\Models\Stock\StockTemp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StockAkhirTransController extends Controller
{
    protected function kode()
    {
        $data = StockAkhirMaster::latest()->first();
        $num = null;
        if(!$data){
            $num = 1;
        } else {
            $urutan = (int) substr($data->kode, 0, 4);
            $num = $urutan + 1;
        }
        $id = sprintf("%04s", $num)."/SA/".date('Y');
        return $id;
    }

    public function index()
    {
        $temp = null;
        $session = session('stockAkhir');
        // check session
        if (!$session){
            // create temp
            $temp = StockTemp::create([
                'jenisTemp'=>'StockAkhir',
                'idUser'=>Auth::user()->id,
            ]);
            // create session
            session(['stockAkhir'=>$temp->id]);
        } else {
            // get data temp
            $temp = StockTemp::find($session);
        }
        $data = [
            'idTemp'=>$temp->id,
            'kode'=>$this->kode(),
        ];
        return view('pages.stock.stockAkhirTrans', $data);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $master = StockAkhirMaster::updateOrCreate(
                [
                    'id'=>$request->idMaster
                ],
                [
                    'activeCash'=>session('ClosedCash'),
                    'kode'=>$request->kode,
                    'branchId'=>$request->gudang,
                    'tglInput'=>date('Y-m-d', strtotime($request->tanggalMasuk)),
                    'pencatat'=>$request->pencatat,
                    'idPembuat'=>Auth::user()->id,
                    'keterangan'=>$request->keterangan,
            ]);
            $detilTemp = StockDetilTemp::where('stockTemp', $request->temp);

            // insert stock detil
            foreach ($detilTemp->get() as $row){
                StockAkhirDetil::updateOrCreate(
                    [
                        'idStockAkhir'=>$request->idMaster,
                        'idProduk'=>$row->idproduk
                    ],
                    [
                        'idStockAkhir'=>$request->idMaster,
                        'idProduk'=>$row->idproduk,
                        'jumlah'=>$row->jumlah
                    ]
                );
            }

            // delete detil temp
            $detilTemp->delete();
            // delete master temp
            StockTemp::destroy($request->temp);
            DB::commit();
        } catch (\Exception $e){
            DB::rollBack();
        }
    }
}
