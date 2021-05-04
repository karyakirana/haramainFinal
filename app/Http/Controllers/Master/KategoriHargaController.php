<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\KategoriHarga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class KategoriHargaController extends Controller
{
    /**
     * view Customer
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('pages.master.kategoriHarga');
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function daftarKategori()
    {
        $data = KategoriHarga::orderBy('id_kat_harga', 'desc')->get();
        if (Auth::user()->role == 'SuperAdmin'){
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('Actions', function($row){
                    $btnEdit = '<a href="#" class="btn btn-sm btn-clean btn-icon" id="btnEdit" data-value="'.$row->id_kat_harga.'" title="Edit"><i class="la la-edit"></i></a>';
                    $btnSoft = '<a href="#" class="btn btn-sm btn-clean btn-icon" id="btnSoft" data-value="'.$row->id_kat_harga.'" title="Delete"><i class="la la-trash"></i></a>';
                    $btnRestore = '<a href="#" class="btn btn-sm btn-clean btn-icon" id="btnRestore" data-value="'.$row->id_kat_harga.'" title="unDelete"><i class="fab fa-opencart"></i></a>';
                    $btnForce = '<a href="#" class="btn btn-sm btn-clean btn-icon" id="btnForce" data-value="'.$row->id_kat_harga.'" title="Delete"><i class="flaticon-delete-2"></i></a>';
                    return $btnEdit.$btnSoft.$btnRestore.$btnForce;
                })
                ->rawColumns(['Actions'])
                ->make(true);
        } else {
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('Actions', function($row){
                    $btnEdit = '<a href="#" class="btn btn-sm btn-clean btn-icon" id="btnEdit" data-value="'.$row->id_kat_harga.'" title="Edit details"><i class="la la-edit"></i></a>';
                    $btnSoft = '<a href="#" class="btn btn-sm btn-clean btn-icon" id="btnSoft" data-value="'.$row->id_kat_harga.'" title="Delete"><i class="la la-trash"></i></a>';
                    return $btnEdit.$btnSoft;
                })
                ->rawColumns(['Actions'])
                ->make(true);
        }
    }

    public function idKategoriharga()
    {
        $idKategori = KategoriHarga::orderBy('id_kat_harga', 'desc')->first();
        $num;
        if(!$idKategori)
        {
            $num = 1;
        } else {
            $urutan = (int) substr($idKategori->id_kat_harga, 3, 3);
            $num = $urutan + 1;
        }
        $id = "H".sprintf("%05s", $num);
        return $id;
    }

    /**
     * @param Request $request
     * @return false|string JSON
     */
    public function store(Request $request)
    {
        $request->validate([
            'namaKategori'=> 'required|string|max:255',
        ]);

        $data = [
            'id_kat_harga' => $this->idKategoriharga(),
            'nama_kat' => $request->namaKategori,
            'keterangan' => $request->keterangan,
        ];
        $insert = KategoriHarga::create($data);
        return json_encode(['status'=>TRUE, 'insert'=>$insert]);
    }

    /**
     * @param $id | id_cust
     * @return string
     */
    public function edit($id)
    {
        $data = KategoriHarga::where('id_kat_harga', $id)->first();
        return json_encode($data);
    }

    /**
     * @param Request $request
     * @return false|string
     */
    public function update(Request $request)
    {
        $request->validate([
            'namaKategori'=> 'required|string|max:255',
        ]);

        $data = [
            'nama_kat' => $request->namaKategori,
            'keterangan' => $request->keterangan,
        ];
        $update = KategoriHarga::where('id_kat_harga', $request->id)->update($data);
        return json_encode(['status'=>TRUE, 'update'=>$update]);
    }

    /**
     * @param $id | string id
     * @return false|string
     */
    public function softDeletes($id)
    {
        $softDeletes = KategoriHarga::where('id_kat_harga', $id)->delete();
        return json_encode(['status'=>TRUE, 'delete'=>$softDeletes]);
    }

    /**
     * @param $id | string id
     * @return false|string
     */
    public function restore($id)
    {
        $restore = KategoriHarga::withTrashed()->where('id_kat_harga', $id)->restore();
        return json_encode(['status'=>TRUE, 'delete'=>$restore]);
    }

    /**
     * @param $id | string id
     * @return false|string
     */
    public function destroy($id)
    {
        $delete = KategoriHarga::withTrashed()->where('id_kat_harga', $id)->forceDelete();
        return json_encode(['status'=>TRUE, 'delete'=>$delete]);
    }

    public function select2(Request $request)
    {
        if ($request->has('q')) {
            $cari = $request->q;
            $data = KategoriHarga::where('nama_kat', 'LIKE', '%'.$cari.'%')->get();
            return json_encode($data);
        } else {
            $data = KategoriHarga::get();
            return json_encode($data);
        }
    }
}
