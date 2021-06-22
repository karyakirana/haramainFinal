<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use App\Models\Stock\InventoryReal;
use App\Models\Stock\StockAkhir;
use App\Models\Stock\StockMasuk;
use App\Models\Stock\StockMasukDetil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class InventoryRealController extends Controller
{
    public function index()
    {
        return view('pages.stock.inventoryAll');
    }

    public function inventoryList()
    {
        $data = InventoryReal::allForeignTable()->get();
        return DataTables::of($data)
            ->make(true);
    }

    public function indexByBranch($branch)
    {
        $data = ['branch'=>$branch];
        return view('pages.stock.inventoryByBranch', $data);
    }

    public function inventoryByBranch($branch)
    {
        $data = InventoryReal::allForeignTable()->where('branchId', $branch)->get();
        return DataTables::of($data)
            ->make(true);
    }

    public function refreshStockFromAkhir()
    {
        // data dari stock akhir
        $stockAkhir = StockAkhir::latest()->get();
        $tambah = 0;
        $ngupdate = 0;
        DB::beginTransaction();
        try {
            foreach ($stockAkhir as $row)
            {
                // check data sudah ada atau tidak
                $check = InventoryReal::where('idProduk', $row->id_produk)->where('branchId', $row->branchId)->first();
                if (!$check)
                {
                    // insert
                    $insert = InventoryReal::create([
                        'idProduk'=>$row->id_produk,
                        'branchId'=>$row->branchId,
                        'stockIn'=>$row->jumlah_stock,
                        'stockNow'=>$row->jumlah_stock,
                    ]);
                    $tambah++;
                } else {
                    // update increment
                    $update = InventoryReal::where('idProduk', $row->id_produk)
                        ->where('branchId', $row->branchId)
                        ->update([
                            'stockIn'=> DB::raw('stockNow +'.$row->jumlah_stock),
                            'stockNow'=> DB::raw('stockNow +'.$row->jumlah_stock),
                        ]);
                    $ngupdate++;
                }
            }
            DB::commit();
            return response()->json(['status'=>true, 'insert'=>$tambah, 'update'=>$ngupdate]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status'=>false, 'keterangan'=>$e]);
        }
    }

    public function refreshStockFromGudangIn()
    {
        $stockMasuk = StockMasukDetil::leftJoin('stockmasuk as sm', 'sm.id', '=', 'stockmasukdetil.idStockMasuk')
                ->select('sm.idBranch as branchId', 'stockmasukdetil.idProduk as idProduk', 'stockmasukdetil.jumlah as jumlah')
                ->get();
        $tambah = 0;
        $ngupdate = 0;
        DB::beginTransaction();
        try {
            foreach ($stockMasuk as $row)
            {
                // check data sudah ada atau tidak
                $check = InventoryReal::where('idProduk', $row->idProduk)->where('branchId', $row->branchId)->first();
                if (!$check)
                {
                    // insert
                    $insert = InventoryReal::create([
                        'idProduk'=>$row->idProduk,
                        'branchId'=>$row->branchId,
                        'stockIn'=>$row->jumlah,
                        'stockNow'=>$row->jumlah,
                    ]);
                    $tambah++;
                } else {
                    // update increment
                    $update = InventoryReal::where('idProduk', $row->idProduk)
                        ->where('branchId', $row->branchId)
                        ->update([
                            'stockIn'=> DB::raw('stockNow +'.$row->jumlah),
                            'stockNow'=> DB::raw('stockNow +'.$row->jumlah),
                        ]);
                    $ngupdate++;
                }
            }
            DB::commit();
            return response()->json(['status'=>true, 'insert'=>$tambah, 'update'=>$ngupdate]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status'=>false, 'keterangan'=>$e]);
        }
    }

    public function refreshStockFromSales()
    {
        //
    }

    public function refreshStockAll()
    {
        //
    }
}
