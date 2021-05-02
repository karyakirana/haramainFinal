<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class CustomerController extends Controller
{
    /**
     * view Customer
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('pages.master.customer');
    }

    public function daftarCustomer()
    {
        $data = Customer::orderBy('id_cust', 'desc')->get();
        if (Auth::user()->role == 'SuperAdmin'){
            return DataTables::of($data)
                ->addColumn('Actions', function($row){
                    $btnEdit = '<a href="#" class="btn btn-sm btn-clean btn-icon" id="btnEdit" data-value="'.$row->id_cust.'" title="Edit"><i class="la la-edit"></i></a>';
                    $btnSoft = '<a href="#" class="btn btn-sm btn-clean btn-icon" id="btnSoft" data-value="'.$row->id_cust.'" title="Delete"><i class="la la-trash"></i></a>';
                    $btnRestore = '<a href="#" class="btn btn-sm btn-clean btn-icon" id="btnRestore" data-value="'.$row->id_cust.'" title="unDelete"><i class="fab fa-opencart"></i></a>';
                    $btnForce = '<a href="#" class="btn btn-sm btn-clean btn-icon" id="btnForce" data-value="'.$row->id_cust.'" title="Delete"><i class="flaticon-delete-2"></i></a>';
                    return $btnEdit.$btnSoft.$btnRestore.$btnForce;
                })
                ->rawColumns(['Actions'])
                ->make(true);
        } else {
            return DataTables::of($data)
                ->addColumn('Actions', function($row){
                    $btnEdit = '<a href="#" class="btn btn-sm btn-clean btn-icon" id="btnEdit" data-value="'.$row->id_cust.'" title="Edit details"><i class="la la-edit"></i></a>';
                    $btnSoft = '<a href="#" class="btn btn-sm btn-clean btn-icon" id="btnSoft" data-value="'.$row->id_cust.'" title="Delete"><i class="la la-trash"></i></a>';
                    return $btnEdit.$btnSoft;
                })
                ->rawColumns(['Actions'])
                ->make(true);
        }

    }

    public function idCustomer()
    {
        $idCusutomer = Customer::orderBy('id_cust', 'desc')->first();
        $num;
        if(!$idCusutomer)
        {
            $num = 1;
        } else {
            $urutan = (int) substr($idCusutomer->id_cust, 3, 3);
            $num = $urutan + 1;
        }
        $id = "C".sprintf("%05s", $num);
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
        ]);

        $data = [
            'id_cust' => $this->idCustomer(),
            'nama_cust' => $request->nama,
            'diskon' => $request->diskon,
            'telp_cust' => $request->telepon,
            'addr_cust' => $request->alamat,
            'keterangan' => $request->keterangan,
        ];
        $insert = Customer::create($data);
        return json_encode(['status'=>TRUE, 'insert'=>$insert]);
    }

    /**
     * @param $id | id_cust
     * @return string
     */
    public function edit($id)
    {
        $data = Customer::where('id_cust', $id)->first();
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
        ]);

        $data = [
            'nama_cust' => $request->nama,
            'diskon' => $request->diskon,
            'telp_cust' => $request->telepon,
            'addr_cust' => $request->alamat,
            'keterangan' => $request->keterangan,
        ];
        $update = Customer::where('id_cust', $request->id)->update($data);
        return json_encode(['status'=>TRUE, 'update'=>$update]);
    }

    /**
     * @param $id | string id_cust
     * @return false|string
     */
    public function softDeletes($id)
    {
        $softDeletes = Customer::where('id_cust', $id)->delete();
        return json_encode(['status'=>TRUE, 'delete'=>$softDeletes]);
    }

    /**
     * @param $id | string id_cust
     * @return false|string
     */
    public function restore($id)
    {
        $restore = Customer::withTrashed()->where('id_cust', $id)->restore();
        return json_encode(['status'=>TRUE, 'delete'=>$softDeletes]);
    }

    /**
     * @param $id | string id_cust
     * @return false|string
     */
    public function destroy($id)
    {
        $delete = Customer::withTrashed()->where('id_cust', $id)->forceDelete();
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
            $data = Customer::where('nama_cust', 'LIKE', '%'.$cari.'%')->get();
            return json_encode($data);
        }
    }
}
