<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\JenisSupplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class JenisSupplierController extends Controller
{
    /**
     * view Customer
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('pages.master.jenisSupplier');
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function daftarJenis()
    {
        $data = JenisSupplier::latest()->get();
        if (Auth::user()->role == 'SuperAdmin'){
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('Actions', function($row){
                    $btnEdit = '<a href="#" class="btn btn-sm btn-clean btn-icon" id="btnEdit" data-value="'.$row->id.'" title="Edit"><i class="la la-edit"></i></a>';
                    $btnSoft = '<a href="#" class="btn btn-sm btn-clean btn-icon" id="btnSoft" data-value="'.$row->id.'" title="Delete"><i class="la la-trash"></i></a>';
                    $btnRestore = '<a href="#" class="btn btn-sm btn-clean btn-icon" id="btnRestore" data-value="'.$row->id.'" title="unDelete"><i class="fab fa-opencart"></i></a>';
                    $btnForce = '<a href="#" class="btn btn-sm btn-clean btn-icon" id="btnForce" data-value="'.$row->id.'" title="Delete"><i class="flaticon-delete-2"></i></a>';
                    return $btnEdit.$btnSoft.$btnRestore.$btnForce;
                })
                ->rawColumns(['Actions'])
                ->make(true);
        } else {
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('Actions', function($row){
                    $btnEdit = '<a href="#" class="btn btn-sm btn-clean btn-icon" id="btnEdit" data-value="'.$row->id.'" title="Edit details"><i class="la la-edit"></i></a>';
                    $btnSoft = '<a href="#" class="btn btn-sm btn-clean btn-icon" id="btnSoft" data-value="'.$row->id.'" title="Delete"><i class="la la-trash"></i></a>';
                    return $btnEdit.$btnSoft;
                })
                ->rawColumns(['Actions'])
                ->make(true);
        }
    }

    /**
     * @param Request $request
     * @return false|string JSON
     */
    public function store(Request $request)
    {
        $request->validate([
            'jenis'=> 'required|string|max:255',
        ]);

        $data = [
            'jenis' => $request->jenis,
            'keterangan' => $request->keterangan,
        ];
        $insert = JenisSupplier::create($data);
        return json_encode(['status'=>TRUE, 'insert'=>$insert]);
    }

    /**
     * @param $id | id_cust
     * @return string
     */
    public function edit($id)
    {
        $data = JenisSupplier::find($id);
        return json_encode($data);
    }

    /**
     * @param Request $request
     * @return false|string
     */
    public function update(Request $request)
    {
        $request->validate([
            'jenis'=> 'required|string|max:255',
        ]);

        $data = [
            'jenis' => $request->jenis,
            'keterangan' => $request->keterangan,
        ];
        $update = JenisSupplier::where('id', $request->id)->update($data);
        return json_encode(['status'=>TRUE, 'update'=>$update]);
    }

    /**
     * @param $id | string id
     * @return false|string
     */
    public function softDeletes($id)
    {
        $softDeletes = JenisSupplier::where('id', $id)->delete();
        return json_encode(['status'=>TRUE, 'delete'=>$softDeletes]);
    }

    /**
     * @param $id | string id
     * @return false|string
     */
    public function restore($id)
    {
        $restore = JenisSupplier::withTrashed()->where('id', $id)->restore();
        return json_encode(['status'=>TRUE, 'delete'=>$restore]);
    }

    /**
     * @param $id | string id
     * @return false|string
     */
    public function destroy($id)
    {
        $delete = JenisSupplier::withTrashed()->where('id', $id)->forceDelete();
        return json_encode(['status'=>TRUE, 'delete'=>$delete]);
    }

    /**
     * @param Request $request
     * @return false|string
     */
    public function select2(Request $request)
    {
        if ($request->has('q')) {
            $cari = $request->q;
            $data = JenisSupplier::where('jenis', 'LIKE', '%'.$cari.'%')->get();
            return json_encode($data);
        } else {
            $data = JenisSupplier::get();
            return json_encode($data);
        }
    }
}
