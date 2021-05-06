<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Kasir\Pembayaran;
use App\Models\Transaksi\Penjualan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class PembayaranController extends Controller
{
    /**
     * tampilan daftar pembayaran
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('pages.kasir.pembayaran');
    }

    /**
     * datatable pembayaran (daftar pembayaran)
     * @return mixed
     * @throws \Exception
     */
    public function daftarPembayaran()
    {
        $data = Pembayaran::leftJoin('users as u', 'pembayaran.idUser', '=', 'u.id')
                ->select(
                    'pembayaran.id as idPembayaran', 'kode', 'idPenjualan', 'jenisBayar', 'kodeInternal',
                    'tglPembayaran', 'u.id as idUser', 'u.name as name', 'pembayaran.created_at as created_at', 'nominal'
                )
                ->latest()
                ->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('Actions', function($row){
                $btnEdit = '<a href="'.url('/kasir/pembayaran').'/edit/'.$row->idPembayaran.'" class="btn btn-sm btn-clean btn-icon" id="btnEdit" title="Edit"><i class="la la-edit"></i></a>';
                $btnDelete = '<a href="#" onclick="hapus('.$row->idPembayaran.')" class="btn btn-sm btn-clean btn-icon" id="btnDelete" title="delete"><i class="la la-trash"></i></a>';
                return $btnEdit.$btnDelete;
            })
            ->rawColumns(['Actions'])
            ->make(true);
    }

    public function daftarPenjualan()
    {
        $data = Penjualan::leftJoin('customer as c', 'penjualan.id_cust', '=', 'c.id_cust')
            ->select(
                'id_jual', 'tgl_nota', 'tgl_tempo', 'status_bayar',
                'total_jumlah', 'penjualan.keterangan as penket',
                'nama_cust'
            )
            ->orderBy('id_jual', 'desc')->get();
        return DataTables::of($data)
            ->addColumn('Actions', function ($row){
                $btnEdit = '<button type="button" class="btn btn-sm btn-clean btn-icon" onclick="setPenjualan('."'".$row->id_jual."'".')"><i class="la la-edit"></i></button>';
                return $btnEdit;
            })
            ->rawColumns(['Actions'])
            ->make(true);
    }

    public function create()
    {
        return view('pages.kasir.pembayaranCreate');
    }

    protected function kode($tanggal)
    {
        $data = Pembayaran::latest()->first();
        if ($data){
            $id = $data->kode;
            $awalan = (int) substr($id, 0,4);
            $bulan = substr($id, 5,2);

            // check nilai bulan
            if ($bulan == date('m', $tanggal)){
                $awalan = $awalan + 1;
            } else {
                $awalan = 1;
            }
            $nomor = sprintf("%04s", $awalan).'/'.date('m')."/BYR/".date('Y');
        } else {
            $nomor = "0001/".date('m')."/BYR/".date('Y');
        }
        return $nomor;
    }

    public function store(Request $request)
    {
        $request->validate([
            'kodeInternal' => 'required|string',
            'idPenjualan' => 'required|string',
            'jenisBayar' => 'required|string',
            'tanggal' => 'required',
            'nominal' => 'required',
        ]);

        $data = [
            'kode' => $this->kode(strtotime($request->tanggal)),
            'kodeInternal' => $request->kodeInternal,
            'idPenjualan' => $request->idPenjualan,
            'jenisBayar' => $request->jenisBayar,
            'idUser' => (int) Auth::user()->id,
            'tglPembayaran' => date('Y-m-d', strtotime($request->tanggal)),
            'nominal' => (int) $request->nominal,
            'keterangan' => $request->keterangan,
        ];

        $hasil = Pembayaran::updateOrCreate(['id'=>$request->id], $data);
        return redirect('/kasir/pembayaran/data');
    }

    public function edit($id)
    {
        $dataPembayaran = Pembayaran::find($id);
        $data = [
            'id' => $dataPembayaran->id,
            'kode' => $dataPembayaran->kode,
            'kodeInternal' => $dataPembayaran->kodeInternal,
            'idPenjualan' => $dataPembayaran->idPenjualan,
            'jenisBayar' => $dataPembayaran->jenisBayar,
            'namaUser' => User::find($dataPembayaran->idUser)->id,
            'tanggal' => date('d-m-Y', strtotime($dataPembayaran->tglPembayaran)),
            'nominal' => $dataPembayaran->nominal,
            'keterangan' => $dataPembayaran->keterangan,
        ];
        return view('pages.kasir.pembayaranEdit', $data);
    }

    public function destroy($id)
    {
        $delete = Pembayaran::destroy($id);
        return json_encode(['status'=>true]);
    }
}
