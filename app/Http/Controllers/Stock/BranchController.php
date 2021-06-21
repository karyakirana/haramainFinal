<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use App\Models\Stock\Branch;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class BranchController extends Controller
{
    public function index()
    {
        return view('pages.stock.branchStock');
    }

    public function branchList()
    {
        $branchLIst = Branch::latest()->get();
        return DataTables::of($branchLIst)
            ->addIndexColumn()
            ->addColumn('Actions', function($row){
                $btnEdit = '<a href="#" class="btn btn-sm btn-clean btn-icon" id="btnEdit" data-value="'.$row->id.'" title="Edit details"><i class="la la-edit"></i></a>';
                $btnSoft = '<a href="#" class="btn btn-sm btn-clean btn-icon" id="btnSoft" data-value="'.$row->id.'" title="Delete"><i class="la la-trash"></i></a>';
                return $btnEdit.$btnSoft;
            })
            ->rawColumns(['Actions'])
            ->make(true);
    }
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'namaGudang'=>'required',
            'alamat'=>'required',
        ]);

        $data = [
            'branchName'=>$request->namaGudang,
            'alamat'=>$request->alamat,
            'kota'=>$request->kota,
            'keterangan'=>$request->keterangan
        ];
        $store = Branch::updateOrCreate(['id'=> $request->id], $data);
        return response()->json(['status'=>true, 'keterangan'=>$store]);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($id)
    {
        $data = Branch::find($id);
        return response()->json($data);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $destroy = Branch::destroy($id);
        return response()->json(['status'=>true, 'keterangan'=>$destroy]);
    }
}
