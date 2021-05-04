<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class KategoriController extends Controller
{
    /**
     * view Customer
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('pages.master.kategori');
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function daftarKategori()
    {
        $data = Kategori::orderBy('id_kategori', 'desc')->get();
        if (Auth::user()->role == 'SuperAdmin'){
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('Actions', function($row){
                    $btnEdit = '<a href="#" class="btn btn-sm btn-clean btn-icon" id="btnEdit" data-value="'.$row->id_kategori.'" title="Edit"><i class="la la-edit"></i></a>';
                    $btnSoft = '<a href="#" class="btn btn-sm btn-clean btn-icon" id="btnSoft" data-value="'.$row->id_kategori.'" title="Delete"><i class="la la-trash"></i></a>';
                    $btnRestore = '<a href="#" class="btn btn-sm btn-clean btn-icon" id="btnRestore" data-value="'.$row->id_kategori.'" title="unDelete"><i class="fab fa-opencart"></i></a>';
                    $btnForce = '<a href="#" class="btn btn-sm btn-clean btn-icon" id="btnForce" data-value="'.$row->id_kategori.'" title="Delete"><i class="flaticon-delete-2"></i></a>';
                    return $btnEdit.$btnSoft.$btnRestore.$btnForce;
                })
                ->rawColumns(['Actions'])
                ->make(true);
        } else {
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('Actions', function($row){
                    $btnEdit = '<a href="#" class="btn btn-sm btn-clean btn-icon" id="btnEdit" data-value="'.$row->id_kategori.'" title="Edit details"><i class="la la-edit"></i></a>';
                    $btnSoft = '<a href="#" class="btn btn-sm btn-clean btn-icon" id="btnSoft" data-value="'.$row->id_kategori.'" title="Delete"><i class="la la-trash"></i></a>';
                    return $btnEdit.$btnSoft;
                })
                ->rawColumns(['Actions'])
                ->make(true);
        }
    }

    public function idKategori()
    {
        $idKategori = Kategori::orderBy('id_kategori', 'desc')->first();
        $num;
        if(!$idKategori)
        {
            $num = 1;
        } else {
            $urutan = (int) substr($idKategori->id_kategori, 3, 3);
            $num = $urutan + 1;
        }
        $id = "K".sprintf("%05s", $num);
        return $id;
    }

    /**
     * @param Request $request
     * @return false|string JSON
     */
    public function store(Request $request)
    {
        $request->validate([
            'idLokal'=> 'required|string|max:255',
            'nama'=> 'required|string|max:255',
        ]);

        $data = [
            'id_kategori' => $this->idKategori(),
            'id_lokal' => $request->idLokal,
            'nama' => $request->nama,
            'keterangan' => $request->keterangan,
        ];
        $insert = Kategori::create($data);
        return json_encode(['status'=>TRUE, 'insert'=>$insert]);
    }

    /**
     * @param $id | id_cust
     * @return string
     */
    public function edit($id)
    {
        $data = Kategori::where('id_kategori', $id)->first();
        return json_encode($data);
    }

    /**
     * @param Request $request
     * @return false|string
     */
    public function update(Request $request)
    {
        $request->validate([
            'idLokal'=> 'required|string|max:255',
            'nama'=> 'required|string|max:255',
        ]);

        $data = [
            'id_lokal' => $request->idLokal,
            'nama' => $request->nama,
            'keterangan' => $request->keterangan,
        ];
        $update = Kategori::where('id_kategori', $request->id)->update($data);
        return json_encode(['status'=>TRUE, 'update'=>$update]);
    }

    /**
     * @param $id | string id
     * @return false|string
     */
    public function softDeletes($id)
    {
        $softDeletes = Kategori::where('id_kategori', $id)->delete();
        return json_encode(['status'=>TRUE, 'delete'=>$softDeletes]);
    }

    /**
     * @param $id | string id
     * @return false|string
     */
    public function restore($id)
    {
        $restore = Kategori::withTrashed()->where('id_kategori', $id)->restore();
        return json_encode(['status'=>TRUE, 'delete'=>$restore]);
    }

    /**
     * @param $id | string id
     * @return false|string
     */
    public function destroy($id)
    {
        $delete = Kategori::withTrashed()->where('id_kategori', $id)->forceDelete();
        return json_encode(['status'=>TRUE, 'delete'=>$delete]);
    }

    public function select2(Request $request)
    {
        if ($request->has('q')) {
            $cari = $request->q;
            $data = Kategori::where('nama', 'LIKE', '%'.$cari.'%')->get();
            return json_encode($data);
        } else {
            $data = Kategori::get();
            return json_encode($data);
        }
    }
}
