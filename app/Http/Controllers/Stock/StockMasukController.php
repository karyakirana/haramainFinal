<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use App\Models\Master\Supplier;
use App\Models\Stock\Branch;
use App\Models\Stock\StockDetilTemp;
use App\Models\Stock\StockMasuk;
use App\Models\Stock\StockMasukDetil;
use App\Models\Stock\StockTemp;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class StockMasukController extends Controller
{
    public function index()
    {
        return view('pages.stock.stockMasuk');
    }

    public function daftarStockMasuk()
    {
        $data = StockMasuk::leftJoin('supplier as s', 'stockmasuk.idSupplier', '=', 's.id')
            ->leftJoin('users as u', 'stockmasuk.idUser', '=', 'u.id')
            ->leftJoin('branch_stock as bs', 'stockMasuk.idBranch', "=", 'bs.id')
            ->select(
                'stockmasuk.id as stockmasukId', 'kode', 'tglMasuk', 'nomorPo', 'stockmasuk.keterangan as keterangan',
                'stockmasuk.created_at as created_at',
                's.namaSupplier as namaSupplier',
                'u.name as name',
                'bs.branchName as gudang'
            )
            ->latest()->get();
        return DataTables::of($data)
            ->addColumn('Actions', function ($row){
                $btnPrint = '<a href="'.url('/stock/masuk/print/')."/".$row->stockmasukId.'" class="btn btn-sm btn-clean btn-icon" title="Print">
                                    <i class="text-dark-50 flaticon2-print"></i>
                                </a>';
                $btnEdit = '<a href="'.url('/stock/masuk/edit')."/".$row->stockmasukId.'" class="btn btn-sm btn-clean btn-icon" title="Edit details">
                                    <i class="la text-success la-edit"></i>
                                </a>';
                return $btnPrint.$btnEdit;
            })
            ->addColumn('tglMasuk', function($row){
                return date('d-M-Y', strtotime($row->tglMasuk));
            })
            ->addColumn('nomorPo', function($row){
                return ($row->nomorPo) ? $row->nomorPo : '';
            })
            ->rawColumns(['Actions'])
            ->make(true);
    }

    protected function idStockMasuk()
    {
//        $closedCash = session('ClosedCash');
        $data = StockMasuk::latest()->first();
        $num = null;
        if(!$data){
            $num = 1;
        } else {
            $urutan = (int) substr($data->kode, 0, 4);
            $num = $urutan + 1;
        }
        $id = sprintf("%04s", $num)."/SM/".date('Y');
        return $id;
    }

    protected function createdTemp()
    {
        $create = StockTemp::create([
            'jenisTemp' => 'StockMasuk',
            'idUser' => Auth::user()->id,
        ]);
        session(['stockMasuk'=>$create->id]);
        return $create;
    }

    public function create()
    {
        // check session
        $sessionTemp = session('stockMasuk');
        if ($sessionTemp) {
            $stockTemp = StockTemp::find($sessionTemp);
            $user = User::find($stockTemp)->first();
            $data = [
                'idTemp' => $sessionTemp,
                'idUser' => $stockTemp->idUser,
                'namaUser' => $user->name,
                'kode' => $this->idStockMasuk(),
            ];
        } else {
            $temporary = $this->createdTemp();
            $user = User::find($temporary->idUser);
            $data = [
                'idTemp' => $temporary->id,
                'idUser' => $temporary->idUser,
                'namaUser' => $user->name,
                'kode' => $this->idStockMasuk(),
            ];
        }
        $branch = Branch::all();
        return view('pages.stock.stockMasukBaru', $data)->with(['branch'=>$branch]);
    }

    public function store(Request $request)
    {
        $idStock = $this->idStockMasuk();
        $idStockTemp = $request->temp;
        $tglMasuk = date('Y-m-d', strtotime($request->tanggalMasuk));

        // ambil data stock_detil_temp
        $detilTemp = StockDetilTemp::where('stockTemp', $idStockTemp)->get();



        $data = [
            'activeCash' => session('ClosedCash'),
            'kode' => $idStock,
            'tglMasuk' => $tglMasuk,
            'idSupplier' => $request->id_supplier,
            'idUser' => Auth::user()->id,
            'nomorPo' => $request->nomorPo,
            'keterangan' => $request->keterangan,
        ];

        $jsonData = null;
        DB::beginTransaction();
        try {
            $insertMaster = StockMasuk::create($data);
            $dataDetil = null;
            foreach ($detilTemp as $row){
                $data = [
                    'idStockMasuk'=> $insertMaster->id,
                    'idProduk'=> $row->idProduk,
                    'jumlah'=> $row->jumlah,
                ];
                $dataDetil []= $data;
            }
            $insertDetil = StockMasukDetil::insert($dataDetil);
            $deleteTempDetil = StockDetilTemp::where('stockTemp', $idStockTemp)->delete();
            $deleteTempMaster = StockTemp::where('id', $idStockTemp)->delete();
            DB::commit();
            session()->forget('stockMasuk');
            $jsonData = [
                'status' => true,
                'detail' => $insertDetil,
                'master' => $insertMaster,
                'deleteDetil' => $deleteTempDetil,
                'deleteMaster' => $deleteTempMaster,
                'nomorStockMasuk' => str_replace('/', '-', $idStock),
            ];
        } catch (ModelNotFoundException $e){
            DB::rollBack();
            $jsonData = [
                'status' => false,
                'keterangan' => $e->getMessage(),
            ];
        }
        return json_encode($jsonData);
    }

    public function edit($id)
    {
        // ambil data detail yg diedit
        $detail = StockMasukDetil::where('idStockMasuk', $id)->get();

        // ambil data Master
        $master = StockMasuk::where('id', $id)->first();

        // delete temp sebelumnya
        $deleteMaster = StockTemp::where('stockMasuk', $id)->first();
        if ($deleteMaster) {
            StockDetilTemp::where('stockTemp', $deleteMaster->id)->delete();
            StockTemp::where('stockMasuk', $id)->delete();
        }

        $tempMaster = StockTemp::create([
            'jenisTemp' => 'StockMasuk',
            'stockMasuk' => $id,
            'idSupplier' => $master->idSupplier,
            'tglMasuk' => $master->tglMasuk,
            'nomorPo' => $master->nomorPo,
            'idUser' => Auth::user()->id,
        ]);

        // jika $tempMaster gagal return error
        if(!isset($tempMaster->id))
        {
            return "error";
        }

        foreach ($detail as $row)
        {
            $detailTemp = StockDetilTemp::create([
                'stockTemp' => $tempMaster->id,
                'idProduk' => $row->idProduk,
                'jumlah' => $row->jumlah,
            ]);
        }

        $data = [
            'id' => $master->id,
            'idTemp' => $tempMaster->id,
            'kode' => $master->kode,
            'idUser' => Auth::user()->id,
            'namaUser' => Auth::user()->name,
            'idStockMasuk' => $id,
            'idSupplier' => $master->idSupplier,
            'namaSupplier' => Supplier::find($master->idSupplier)->namaSupplier,
            'tglMasuk' => date('d-m-Y', strtotime($master->tglMasuk)),
            'nomorPo' => ($master->nomorPo) ? $master->nomorPo : '',
            'keterangan' => $master->keterangan,
            'idBranch' => $master->idBranch,
            'branch'=>Branch::all()
        ];

        return view('pages.stock.stockMasukEdit', $data);
    }

    public function update(Request $request)
    {
        $idStockMasuk = $request->id;
        $idStockTemp = $request->temp;
        $tglMasuk = date('Y-m-d', strtotime($request->tanggalMasuk));

        // Ambil Data detail_penjualan_temp
        $detilTemp = StockDetilTemp::where('stockTemp', $idStockTemp)->get();

        $data = [
//            'activeCash' => session('ClosedCash'),
//            'kode' => $idStock,
            'tglMasuk' => $tglMasuk,
            'idSupplier' => $request->id_supplier,
            'idUser' => Auth::user()->id,
            'nomorPo' => $request->nomorPo,
            'keterangan' => $request->keterangan,
        ];

        $jsonData = null;
        DB::beginTransaction();
        try {
            $deleteDetail = StockMasukDetil::where('idStockMasuk', $idStockMasuk)->delete();
            $dataDetil = null;
            $insertMaster = StockMasuk::where('id', $idStockMasuk)->update($data);
            foreach ($detilTemp as $row){
                $data = [
                    'idStockMasuk'=> $idStockMasuk,
                    'idProduk'=> $row->idProduk,
                    'jumlah'=> $row->jumlah,
                ];
                $dataDetil []= $data;
            }
            $insertDetil = StockMasukDetil::insert($dataDetil);
            $deleteTempDetil = StockDetilTemp::where('stockTemp', $idStockTemp)->delete();
            $deleteTempMaster = StockTemp::where('id', $idStockTemp)->delete();
            DB::commit();
            session()->forget('stockMasuk');
            $jsonData = [
                'status' => true,
                'detail' => $insertDetil,
                'master' => $insertMaster,
                'deleteDetil' => $deleteTempDetil,
                'deleteMaster' => $deleteTempMaster,
                'nomorStockMasuk' => str_replace('/', '-', $idStockMasuk),
            ];
        } catch (ModelNotFoundException $e){
            DB::rollBack();
            $jsonData = [
                'status' => false,
                'keterangan' => $e->getMessage(),
            ];
        }
        return json_encode($jsonData);
    }
}
