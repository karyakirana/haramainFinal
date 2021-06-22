<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use App\Models\Stock\InventoryReal;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class InventoryRealController extends Controller
{
    public function index()
    {
        return view('pages.stock.inventoryAll');
    }

    public function inventoryList()
    {
        $data = InventoryReal::allForeignTable();
        return DataTables::of($data)
            ->make(true);
    }

    public function refreshStockFromAkhir()
    {
        //
    }

    public function refreshStockFromGudangIn()
    {
        //
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
