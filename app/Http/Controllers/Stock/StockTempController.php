<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use App\Models\Master\Produk;
use App\Models\Master\Supplier;
use App\Models\Stock\StockDetilTemp;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class StockTempController extends Controller
{
    public function daftarProduk()
    {
        $dataJoin = Produk::leftJoin('kategori as k', 'produk.id_kategori', '=', 'k.id_kategori')
            ->leftJoin('kategori_harga as kh', 'produk.id_kat_harga', '=', 'kh.id_kat_harga')
            ->select('produk.id_produk as produkId', 'k.id_lokal as idLokal', 'k.nama as kategori', 'kh.nama_kat as kategoriHarga','size',
                'penerbit', 'hal', 'cover', 'nama_produk', 'harga', 'produk.deskripsi', 'produk.created_at', 'produk.kode_lokal as kLokal', 'stock' )
            ->orderBy('id_produk', 'desc')
            ->get();
        $data = $dataJoin;
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('Actions', function($row){
                $btnEdit = '<a href="#" class="btn btn-sm btn-clean btn-icon" id="btnAddProduk" data-value="'.$row->produkId.'" title="Add details"><i class="la la-edit"></i></a>';
                return $btnEdit;
            })
            ->rawColumns(['Actions'])
            ->make(true);
    }

    public function setProduk($id)
    {
        $data = Produk::leftJoin('kategori as k', 'produk.id_kategori', '=', 'k.id_kategori')
            ->leftJoin('kategori_harga as kh', 'produk.id_kat_harga', '=', 'kh.id_kat_harga')
            ->select('produk.id_produk as produkId', 'k.id_lokal as idLokal', 'k.nama as kategori', 'kh.nama_kat as kategoriHarga','size',
                'penerbit', 'hal', 'cover', 'nama_produk', 'harga', 'produk.deskripsi', 'produk.created_at', 'produk.kode_lokal as kLokal', 'stock' )
            ->where('id_produk', $id)
            ->orderBy('id_produk', 'desc')
            ->first();
        return json_encode($data);
    }

    public function daftarSupplier()
    {
        $data = Supplier::latest()->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('Actions', function($row){
                $btnEdit = '<a href="#" class="btn btn-sm btn-clean btn-icon" id="btnAddSupplier" data-value="'.$row->id.'" title="Add details"><i class="la la-edit"></i></a>';
                return $btnEdit;
            })
            ->rawColumns(['Actions'])
            ->make(true);
    }

    public function daftarDetil($temp)
    {
        $data = StockDetilTemp::leftJoin('produk', 'stock_detil_temp.idProduk', '=', 'produk.id_produk')
            ->where('stockTemp', $temp)
            ->orderBy('id', 'asc')
            ->get();
        return Datatables::of($data)
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

    public function store(Request $request)
    {
        $request->validate([
            'jumlah' => 'required|int'
        ]);

        $data = [
            'stockTemp' => $request->idTemp,
            'idProduk' => $request->idProduk,
            'jumlah' => $request->jumlah,
        ];
        $insert = StockDetilTemp::updateOrCreate(['id'=>$request->idDetil], $data);
        return json_encode(['status'=>true, 'insert'=>$insert]);
    }

    public function edit($id)
    {
        $detil = StockDetilTemp::leftJoin('produk', 'stock_detil_temp.idProduk', '=', 'produk.id_produk')
            ->leftJoin('kategori as k', 'produk.id_kategori', '=', 'k.id_kategori')
            ->leftJoin('kategori_harga as kh', 'produk.id_kat_harga', '=', 'kh.id_kat_harga')
            ->select('id', 'produk.id_produk as produkId', 'k.id_lokal as idLokal', 'k.nama as kategori', 'kh.nama_kat as kategoriHarga','size',
                'penerbit', 'hal', 'cover', 'nama_produk', 'jumlah', 'produk.deskripsi', 'produk.created_at', 'produk.kode_lokal as kLokal', 'stock' )
            ->orderBy('idProduk', 'desc')
            ->find($id);
        return json_encode($detil);
    }

    public function destroy($id)
    {
        $delete = StockDetilTemp::where('id', $id)->delete();
        return json_encode(['status'=>TRUE, 'delete'=>$delete]);
    }
}
