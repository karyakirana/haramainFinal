<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Models\Master\Customer;
use App\Models\Master\Produk;
use App\Models\Transaksi\PenjualanDetilTemp;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class PenjualanTempController extends Controller
{
    public function detilPenjualanTemp($id)
    {
        $data = PenjualanDetilTemp::leftJoin('produk', 'detil_penjualan_temp.idBarang', '=', 'produk.id_produk')
            ->where('idPenjualanTemp', $id)
            ->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('namaBarang', function($row){
                return $row->nama_produk.'<br>'.$row->id_produk;
            })
            ->addColumn('Actions', function($row){
                $btnEdit = '<a href="#" class="btn btn-sm btn-clean btn-icon" id="btnEdit" data-value="'.$row->produkId.'" title="Edit details"><i class="la la-edit"></i></a>';
                $btnSoft = '<a href="#" class="btn btn-sm btn-clean btn-icon" id="btnSoft" data-value="'.$row->produkId.'" title="Delete"><i class="la la-trash"></i></a>';
                return $btnEdit.$btnSoft;
            })
            ->rawColumns(['namaBarang', 'Actions'])
            ->make(true);
    }

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

    public function daftarCustomer()
    {
        $data = Customer::orderBy('id_cust', 'desc')->get();
        return DataTables::of($data)
            ->addColumn('Actions', function($row){
                $btnEdit = '<a href="#" class="btn btn-sm btn-clean btn-icon" id="btnAddCustomer" data-value="'.$row->id_cust.'" title="Add details"><i class="la la-edit"></i></a>';
                return $btnEdit;
            })
            ->rawColumns(['Actions'])
            ->make(true);
    }

    public function daftarDetil($temp)
    {
        $data = PenjualanDetilTemp::leftJoin('produk', 'detil_penjualan_temp.idBarang', '=', 'produk.id_produk')
            ->where('idPenjualanTemp', $temp)
            ->orderBy('id', 'asc')
            ->get();
        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('namaBarang', function($row){
                return $row->nama_produk;
            })
            ->addColumn('Diskon', function ($row){
                return $row->diskon."%";
            })
            ->addColumn('Actions', function ($row){
                $btnEdit = '<button class="btn btn-sm btn-clean btn-icon" id="btnEdit" data-value="'.$row->id.'" title="Edit details"><i class="la la-edit"></i></button>';
                $btnSoft = '<button class="btn btn-sm btn-clean btn-icon" id="btnSoft" data-value="'.$row->id.'" title="Delete"><i class="la la-trash"></i></button>';
                return $btnEdit.$btnSoft;
            })
            ->rawColumns(['namaBarang', 'Diskon','Actions'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'jumlah' => 'required|int',
            'diskon' => 'required|numeric',
        ]);
        $diskon = ((float) $request->diskon) / 100;
        $harga = $request->harga;
        $hargaDiskon = $harga - ($harga * $diskon);
        $subTotal = $hargaDiskon * $request->jumlah;
        $data = [
            'idPenjualanTemp' => $request->idTemp,
            'idBarang' => $request->idProduk,
            'jumlah' => $request->jumlah,
            'harga' => $harga,
            'diskon' => $request->diskon,
            'sub_total' => $subTotal,
        ];
        $insert = PenjualanDetilTemp::updateOrCreate(['id'=>$request->idDetil], $data);
        return json_encode(['status'=> true, 'insert', $insert]);
    }

    public function edit($id)
    {
        $detil = PenjualanDetilTemp::leftJoin('produk', 'detil_penjualan_temp.idbarang', '=', 'produk.id_produk')
            ->leftJoin('kategori as k', 'produk.id_kategori', '=', 'k.id_kategori')
            ->leftJoin('kategori_harga as kh', 'produk.id_kat_harga', '=', 'kh.id_kat_harga')
            ->select('id', 'produk.id_produk as produkId', 'k.id_lokal as idLokal', 'k.nama as kategori', 'kh.nama_kat as kategoriHarga','size',
                'penerbit', 'hal', 'cover', 'nama_produk', 'detil_penjualan_temp.harga as hargaBarang', 'diskon', 'jumlah', 'sub_total', 'produk.deskripsi', 'produk.created_at', 'produk.kode_lokal as kLokal', 'stock' )
            ->orderBy('id_produk', 'desc')
            ->find($id);
        return json_encode($detil);
    }

    public function destroy($id)
    {
        $delete = PenjualanDetilTemp::where('id', $id)->delete();
        return json_encode(['status'=>TRUE, 'delete'=>$delete]);
    }
}
