<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Supplier;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;

class SupplierController extends Controller
{
    /**
     * view Customer
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('pages.master.supplier');
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function daftarSupplier()
    {
        $data = Supplier::leftJoin('jenissupplier as js', 'supplier.jenisSupplier', '=', 'js.id')
            ->select(
                'supplier.id as idSupplier',
                'kodeSupplier',
                'js.jenis as jenis',
                'namaSupplier',
                'alamatSupplier',
                'tlpSupplier',
                'npwpSupplier',
                'emailSupplier',
                'keteranganSupplier'
            )
            ->orderBy('supplier.id', 'desc')->get();
        if (Auth::user()->role == 'SuperAdmin'){
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('Actions', function($row){
                    $btnEdit = '<a href="#" class="btn btn-sm btn-clean btn-icon" id="btnEdit" data-value="'.$row->idSupplier.'" title="Edit"><i class="la la-edit"></i></a>';
                    $btnSoft = '<a href="#" class="btn btn-sm btn-clean btn-icon" id="btnSoft" data-value="'.$row->idSupplier.'" title="Delete"><i class="la la-trash"></i></a>';
                    $btnRestore = '<a href="#" class="btn btn-sm btn-clean btn-icon" id="btnRestore" data-value="'.$row->idSupplier.'" title="unDelete"><i class="fab fa-opencart"></i></a>';
                    $btnForce = '<a href="#" class="btn btn-sm btn-clean btn-icon" id="btnForce" data-value="'.$row->idSupplier.'" title="Delete"><i class="flaticon-delete-2"></i></a>';
                    return $btnEdit.$btnSoft.$btnRestore.$btnForce;
                })
                ->rawColumns(['Actions'])
                ->make(true);
        } else {
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('Actions', function($row){
                    $btnEdit = '<a href="#" class="btn btn-sm btn-clean btn-icon" id="btnEdit" data-value="'.$row->idSupplier.'" title="Edit details"><i class="la la-edit"></i></a>';
                    $btnSoft = '<a href="#" class="btn btn-sm btn-clean btn-icon" id="btnSoft" data-value="'.$row->idSupplier.'" title="Delete"><i class="la la-trash"></i></a>';
                    return $btnEdit.$btnSoft;
                })
                ->rawColumns(['Actions'])
                ->make(true);
        }
    }

    public function kodeSupplier()
    {
        $idSupplier = Supplier::latest()->first();
        $num;
        if(!$idSupplier)
        {
            $num = 1;
        } else {
            $urutan = (int) substr($idSupplier->kodeSupplier, 3, 3);
            $num = $urutan + 1;
        }
        $id = "S".sprintf("%05s", $num);
        return $id;
    }

    /**
     * @param Request $request
     * @return false|string JSON
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama'=> 'required|string|max:255',
            'alamat'=> 'required|string|max:255',
        ]);

        $data = [
            'kodeSupplier' => $this->kodeSupplier(),
            'jenisSupplier' => $request->jenis,
            'namaSupplier' => $request->nama,
            'alamatSupplier' => $request->alamat,
            'tlpSupplier' => $request->telepon,
            'npwpSupplier' => $request->npwp,
            'emailSupplier' => $request->email,
            'keteranganSupplier' => $request->keterangan,
        ];
        $insert = Supplier::create($data);
        return json_encode(['status'=>TRUE, 'insert'=>$insert]);
    }

    /**
     * @param $id | id_cust
     * @return string
     */
    public function edit($id)
    {
        $data = Supplier::find($id)->first();
        return json_encode($data);
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
        $update = Supplier::where('id', $request->id)->update($data);
        return json_encode(['status'=>TRUE, 'update'=>$update]);
    }

    /**
     * @param $id | string id
     * @return false|string
     */
    public function softDeletes($id)
    {
        $softDeletes = Supplier::where('id', $id)->delete();
        return json_encode(['status'=>TRUE, 'delete'=>$softDeletes]);
    }

    /**
     * @param $id | string id
     * @return false|string
     */
    public function restore($id)
    {
        $restore = Supplier::withTrashed()->where('id', $id)->restore();
        return json_encode(['status'=>TRUE, 'delete'=>$restore]);
    }

    /**
     * @param $id | string id
     * @return false|string
     */
    public function destroy($id)
    {
        $delete = Supplier::withTrashed()->where('id', $id)->forceDelete();
        return json_encode(['status'=>TRUE, 'delete'=>$delete]);
    }
}
