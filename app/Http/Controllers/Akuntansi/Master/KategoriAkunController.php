<?php

namespace App\Http\Controllers\Akuntansi\Master;

use App\Http\Controllers\Controller;
use App\Models\Akuntansi\KategoriAkun;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class KategoriAkunController extends Controller
{
    /**
     * menampilkan daftar kategori buku besar akuntansi
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('pages.akuntansi.kategoriAkun');
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function daftarKategori()
    {
        $data = KategoriAkun::all();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('Actions', function($row){
                $btnEdit = '<a href="#" class="btn btn-sm btn-clean btn-icon" id="btnEdit" onClick="edit('."'".$row->id."'".')" data-value="'.$row->id.'" title="Edit details"><i class="la la-edit"></i></a>';
                $btnSoft = '<a href="#" class="btn btn-sm btn-clean btn-icon" id="btnSoft" onClick="destroy('."'".$row->id."'".')" data-value="'.$row->id.'" title="Delete"><i class="la la-trash"></i></a>';
                return $btnEdit.$btnSoft;
            })
            ->rawColumns(['Actions'])
            ->make(true);
    }

    public function edit($id)
    {
        $data = KategoriAkun::find($id);
        return response()->json($data);
    }

    /**
     * update atau insert data
     * @param Request $request
     * @return string
     */
    public function store(Request $request)
    {
        $data = [
            'kode' => $request->kode,
            'namaAkun' => $request->namaAkun,
            'deskripsi' => $request->keterangan,
        ];

        $simpan = KategoriAkun::updateOrCreate(['id'=>$request->id], $data);
        return json_encode(['status'=>true, 'simpan'=>$simpan]);
    }

    /**
     * hapus data
     * @param $id
     * @return string
     */
    public function destroy($id)
    {
        $destroy = KategoriAkun::destroy($id);
        return json_encode(['hasil'=>true, 'destroy'=>$destroy]);
    }
}
