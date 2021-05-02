<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Kategori;
use App\Models\Master\KategoriHarga;
use App\Models\Master\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class ProdukController extends Controller
{
    /**
     * view Customer
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('pages.master.produk');
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function daftarProduk()
    {
        $dataJoin = Produk::leftJoin('kategori as k', 'produk.id_kategori', '=', 'k.id_kategori')
            ->leftJoin('kategori_harga as kh', 'produk.id_kat_harga', '=', 'kh.id_kat_harga')
            ->select('produk.id_produk as produkId', 'k.id_lokal as idLokal', 'k.nama as kategori', 'kh.nama_kat as kategoriHarga','size',
                'penerbit', 'hal', 'cover', 'nama_produk', 'harga', 'produk.deskripsi', 'produk.created_at', 'produk.kode_lokal as kLokal' )
            ->orderBy('id_produk', 'desc')
            ->get();
        $data = $dataJoin;
        if (Auth::user()->role == 'SuperAdmin'){
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('Actions', function($row){
                    $btnEdit = '<a href="#" class="btn btn-sm btn-clean btn-icon" id="btnEdit" data-value="'.$row->produkId.'" title="Edit"><i class="la la-edit"></i></a>';
                    $btnSoft = '<a href="#" class="btn btn-sm btn-clean btn-icon" id="btnSoft" data-value="'.$row->produkId.'" title="Delete"><i class="la la-trash"></i></a>';
                    $btnRestore = '<a href="#" class="btn btn-sm btn-clean btn-icon" id="btnRestore" data-value="'.$row->produkId.'" title="unDelete"><i class="fab fa-opencart"></i></a>';
                    $btnForce = '<a href="#" class="btn btn-sm btn-clean btn-icon" id="btnForce" data-value="'.$row->produkId.'" title="Delete"><i class="flaticon-delete-2"></i></a>';
                    return $btnEdit.$btnSoft.$btnRestore.$btnForce;
                })
                ->rawColumns(['Actions'])
                ->make(true);
        } else {
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('Actions', function($row){
                    $btnEdit = '<a href="#" class="btn btn-sm btn-clean btn-icon" id="btnEdit" data-value="'.$row->produkId.'" title="Edit details"><i class="la la-edit"></i></a>';
                    $btnSoft = '<a href="#" class="btn btn-sm btn-clean btn-icon" id="btnSoft" data-value="'.$row->produkId.'" title="Delete"><i class="la la-trash"></i></a>';
                    return $btnEdit.$btnSoft;
                })
                ->rawColumns(['Actions'])
                ->make(true);
        }
    }

    public function idProduk()
    {
        $idProduk = Produk::orderBy('id_produk', 'desc')->first();
        $num = null;
        if(!$idProduk)
        {
            $num = 1;
        } else {
            $urutan = (int) substr($idProduk->id_produk, 1, 5);
            $num = $urutan + 1;
        }
        $id = "P".sprintf("%05s", $num);
        return $id;
    }

    /**
     * @param Request $request
     * @return false|string JSON
     */
    public function store(Request $request)
    {
        $request->validate([
            'idKategori'=> 'required|string|max:255',
            'namaProduk'=> 'required|string|max:255',
            'halaman' => 'required|integer',
            'id_kat_harga' => 'required|string|max:255',
            'harga' => 'required|integer',
        ]);

        $data = [
            'id_kategori' => $request->idKategori,
            'id_produk' => $this->idProduk(),
            'kode_lokal' => $request->kodeLokal,
            'penerbit' => $request->penerbit,
            'hal' => $request->halaman,
            'cover' => $request->cover,
            'id_kat_harga' => $request->kategoriHarga,
            'nama_produk' => $request->namaProduk,
            'harga' => $request->harga,
            'size' => $request->size,
            'deskripsi' => $request->keterangan,
        ];
        $insert = Produk::create($data);
        return json_encode(['status'=>TRUE, 'insert'=>$insert]);
    }

    /**
     * @param $id | id_cust
     * @return string
     */
    public function edit($id)
    {
        $data = Produk::where('id_produk', $id)->first();
        $kategori = Kategori::where('id_kategori', $data->id_kategori)->first();
        $kat_harga = KategoriHarga::where('id_kat_harga', $data->id_kat_harga)->first();
        $hasil = [
            'id_produk' => $data->id_produk,
            'id_kategori' => $data->id_kategori,
            'id_lokal' => $kategori->id_lokal,
            'nama_kategori' => $kategori->nama,
            'kode_lokal' => $data->kode_lokal,
            'penerbit' => $data->penerbit,
            'nama_produk' => $data->nama_produk,
            'hal' => $data->hal,
            'cover' => $data->cover,
            'id_kat_harga' => $data->id_kat_harga,
            'nama_kat' => $kat_harga->nama_kat,
            'harga' => $data->harga,
            'size' => $data->size,
            'deskripsi'=> $data->deskripsi,
        ];
        return json_encode($hasil);
    }

    /**
     * @param Request $request
     * @return false|string
     */
    public function update(Request $request)
    {
        $request->validate([
            'nama'=> 'required|string|max:255',
            'alamat'=> 'required|string|max:255',
        ]);

        $data = [
            'jenisSupplier' => $request->jenis,
            'namaSupplier' => $request->nama,
            'alamatSupplier' => $request->alamat,
            'tlpSupplier' => $request->telepon,
            'npwpSupplier' => $request->npwp,
            'emailSupplier' => $request->email,
            'keteranganSupplier' => $request->keterangan,
        ];
        $update = Produk::where('id_produk', $request->id)->update($data);
        return json_encode(['status'=>TRUE, 'update'=>$update]);
    }

    /**
     * @param $id | string id
     * @return false|string
     */
    public function softDeletes($id)
    {
        $softDeletes = Produk::where('id_produk', $id)->delete();
        return json_encode(['status'=>TRUE, 'delete'=>$softDeletes]);
    }

    /**
     * @param $id | string id
     * @return false|string
     */
    public function restore($id)
    {
        $restore = Produk::withTrashed()->where('id_produk', $id)->restore();
        return json_encode(['status'=>TRUE, 'delete'=>$restore]);
    }

    /**
     * @param $id | string id
     * @return false|string
     */
    public function destroy($id)
    {
        $delete = Produk::withTrashed()->where('id_produk', $id)->forceDelete();
        return json_encode(['status'=>TRUE, 'delete'=>$delete]);
    }
}
