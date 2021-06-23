<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use App\Models\Stock\RekonsiliasiDetil;
use App\Models\Stock\RekonsiliasiDetilTemp;
use App\Models\Stock\RekonsiliasiMaster;
use App\Models\Stock\RekonsiliasiTemp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class RekonsiliasiController extends Controller
{
    public function index()
    {
        return view('pages.stock.rekonsiliasi');
    }

    public function rekonsiliasiList()
    {
        $data = RekonsiliasiMaster::all();
        return DataTables::of($data)
            ->nake(true);
    }

    protected function idRekonsiliasi()
    {
        $data = RekonsiliasiMaster::latest()->first();
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

    public function create()
    {
        // check session
        if (!session('rekonsiliasiTemp')){
            $Temp = RekonsiliasiTemp::create([
                'pembuat'=>Auth::user()->id,
            ]);
            $tempId = $Temp->id;
            session()->put('rekonsiliasiTemp', $tempId);
        }
        $data = [
            'tempId'=>session('rekonsiliasiTemp'),
            'kode'=>$this->idRekonsiliasi(),
        ];

        return view('pages.stock.rekonsiliasiNew', $data);
    }

    public function edit($id)
    {
        return view('pages.stock.rekonsiliasiNew');
    }

    public function store(Request $request)
    {
//        dd($request);
//        $nomorTransaksi = $this->idRekonsiliasi();
        DB::beginTransaction();
        try {
            // create transaction
            $master = RekonsiliasiMaster::updateOrCreate(
                ['id'=>$request->idRekonsiliasi],
                [
                    'kode'=>$request->nomorTransaksi,
                    'tglBuat'=>date('Y-m-d', strtotime($request->tanggalNota)),
                    'branchIdAsal'=>$request->gudangAsal,
                    'branchIdAkhir'=>$request->gudangTujuan,
                    'pembuat'=>$request->idSales,
                    'nomorPo'=>$request->nomorPo,
                    'keterangan'=>$request->keterangan
                ]
            );
            // insert Detil
            $detil = RekonsiliasiDetilTemp::where('idTemp', $request->temp);
            foreach ($detil->get() as $row){
                RekonsiliasiDetil::updateOrCreate(
                    [
                        'idRekonsiliasi'=>$request->idRekonsiliasi,
                        'idProduk'=>$row->idProduk
                    ],
                    [
                        'idRekonsiliasi'=>$master->id,
                        'idProduk'=>$row->idProduk,
                        'jumlah'=>$row->jumlah
                    ]
                );
            }
            // delete detil
            $detil->delete();
            // delete master
            RekonsiliasiTemp::destroy($request->temp);
            DB::commit();
            session()->forget('rekonsiliasiTemp');
            redirect()->to('/stock/rekonsiliasi/');
        } catch (\Exception $e){
            DB::rollBack();
            dd($e);
        }
    }
}
