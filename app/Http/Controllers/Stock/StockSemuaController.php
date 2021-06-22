<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StockSemuaController extends Controller
{
    //stock semua
    public function index()
    {
        return view('pages.stock.stockSemua');
    }

    public function stockALlList()
    {
        //
    }
}
