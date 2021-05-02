<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Models\Transaksi\Penjualan;
use App\Models\Transaksi\PenjualanDetil;
use App\Models\Transaksi\PenjualanDetilTemp;
use App\Models\Transaksi\PenjualanTemp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PenjualanController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('pages.transaksi.daftarPenjualan');
    }

    public function daftarPenjualan()
    {
        $data = Penjualan::leftJoin('user as u', 'penjualan.id_user', '=', 'u.id_user')
            ->leftJoin('users', 'penjualan.id_user', '=', 'users.idUserOld')
            ->leftJoin('customer as c', 'penjualan.id_cust', '=', 'c.id_cust')
            ->select(
                'penjualan.id_jual as penjualanId',
                'c.nama_cust as namaCustomer',
                'tgl_nota',
                'tgl_tempo',
                'status_bayar',
                'sudahBayar',
                'total_jumlah',
                'ppn',
                'biaya_lain',
                'total_bayar',
                'penjualan.keterangan as penket',
                'u.username as namaSales1',
                'users.name as namaSales2',
                'print', // jumlah print
                'penjualan.updated_at as update', // last print
            )
            ->orderBy('penjualan.tgl_nota', 'desc')
            ->get();

        return Datatables::of($data)
            // ->addIndexColumn()
            ->addColumn('namaSales', function($row){
                if ($row->nameSales2 == null) {
                    $yoman = $row->namaSales1;
                } else {
                    $yoman = $row->namasales2;
                }
                return $yoman;
            })
            ->addColumn('tglNota', function($row){
                return date('d-m-Y', strtotime($row->tgl_nota));
            })
            ->addColumn('tglTempo', function($row){
                $yoman = (strtotime($row->tgl_tempo) > 946659600) ? date('d-m-Y', strtotime($row->tgl_tempo)) : "";
                return $yoman;
            })
            ->addColumn('tombol', function($row){
                // button for soft deletes
                $btnPrint = '<a href="'.url('/print/penjualan')."/".str_replace('/', '-', $row->penjualanId).'" class="btn btn-sm btn-clean btn-icon" title="Print">
                                    <i class="text-dark-50 flaticon2-print"></i>
                                </a>';
                $btnEdit = '<a href="'.url('/penjualan')."/".str_replace('/', '-', $row->penjualanId)."/edit".'" onClick=edit("'.$row->penjualanId.'") class="btn btn-sm btn-clean btn-icon" title="Edit details">
                                    <i class="la text-success la-edit"></i>
                                </a>';
                $btnDelete = '<a href="javascript:;" onClick=hapus("'.$row->penjualanId.'") class="btn btn-sm btn-clean btn-icon" title="Edit details">
                                    <i class="la text-danger la-trash"></i>
                                </a>';
                if (Gate::allows('SuperAdmin')) {
                    $hardDelete = '<a href="javascript:;" onClick=forceDelete("'.$row->penjualanId.'") class="btn btn-sm btn-danger btn-icon" title="Edit details">
                                    <i class="la text-warning la-trash"></i>
                                </a>';
                    return $btnEdit.$btnDelete.$hardDelete;
                } else {
                    return $btnPrint.$btnEdit;
                }
            })
            ->rawColumns(['namaSales', 'tombol'])
            ->make(true);
    }

    /**
     * membuat session untuk Penjualan Baru
     *
     * @return integer session('penjualan')
     */
    protected function createTemp()
    {
        $create = PenjualanTemp::create([
            'jenisTemp' => 'Penjualan',
            'idSales' => Auth::user()->id,
        ]);
        session(['penjualan'=>$create->id]);

        return $create;
    }

    public function create()
    {
        $sessionTemp = session('penjualan');
        if ($sessionTemp) {
            $penjualanTemp = PenjualanTemp::find($sessionTemp);
            $sales = User::find($penjualanTemp)->first();
            $data = [
                'idTemp' => $sessionTemp,
                'idSales' => $penjualanTemp->idSales,
                'namaSales' => $sales->name,
                'idPenjualan' => $this->idPenjualan(),
            ];
        } else {
            $temporary = $this->createTemp();
            $sales = User::find($temporary->idSales);
            $data = [
                'idTemp' => $temporary->id,
                'idSales' => $temporary->idSales,
                'namaSales' => $sales->name,
                'idPenjualan' => $this->idPenjualan(),
            ];
        }
        return view('pages.transaksi.penjualanTransaksi', $data);
    }

    public function idPenjualan()
    {
        $closedCash = session('ClosedCash');
        $data = Penjualan::where('activeCash', $closedCash)->latest()->first();
        $num = null;
        if(!$data){
            $num = 1;
        } else {
            $urutan = (int) substr($data->id_jual, 0, 4);
            $num = $urutan + 1;
        }
        $id = sprintf("%04s", $num)."/PJ/".date('Y');
        return $id;
    }

    public function store(Request $request)
    {
        // variabel-variabel umum
        $idPenjualan = $this->idPenjualan();
        $idPenjualanTemp = $request->idTemp;
        $tglPenjualan = date('Y-m-d', strtotime($request->tglNota));
        $tglTempo = date('Y-m-d', strtotime($request->tglTempo));

        // Ambil Data detail_penjualan_temp
        $detilTemp = DetilPenjualanTemp::where('idPenjualanTemp', $idPenjualanTemp)->get();

        $dataDetil = null;
        foreach ($detilTemp as $row) {
            $data = [
                'id_jual' => $idPenjualan,
                'id_produk' => $row->idBarang,
                'jumlah' => $row->jumlah,
                'harga' => $row->harga,
                'diskon' => $row->diskon,
                'sub_total' => $row->sub_total,
            ];
            $dataDetil [] = $data;
            // $hitungData++;
        }

        // Data Penjualan untuk di insert ke Tabel Penjualan
        $data = [
            'activeCash' => session('ClosedCash'),
            'id_jual' => $idPenjualan,
            'tgl_nota' => $tglPenjualan,
            'tgl_tempo' => $tglTempo,
            'status_bayar' => $request->radioStatusBayar,
            'sudahBayar'=> "belum", // pembuatan nota belum bayar
            'total_jumlah' => $detilTemp->count(), // jumlah Item
            'ppn' => $request->ppn,
            'biaya_lain' => $request->biayaLain,
            'total_bayar' => $detilTemp->sum('sub_total') + $request->ppn + $request->biayaLain, // total semua subtotal atau $request->hiddenTotalSemuanya
            'id_cust' => $request->idCust,
            'id_user' => Auth::user()->id,
            'keterangan' => $request->keterangan
        ];

        // transaction start
        $jsonData = null;
        DB::beginTransaction();
        try {

            $insertDetail = PenjualanDetil::insert($dataDetil);
            $insertMaster = Penjualan::create($data);
            $deleteTempDetail = PenjualanDetilTemp::where('idPenjualanTemp', $idPenjualanTemp)->delete();
            $deleteTempMaster = PenjualanTemp::where('id', $idPenjualanTemp)->delete();
            DB::commit();
            session()->forget('penjualan'); // hapus session temp
            $jsonData = [
                'status' => true,
                'detail' => $insertDetail,
                'master' => $insertMaster,
                'deleteDetail' => $deleteTempDetail,
                'deletemaster' => $deleteTempMaster,
                'nomorPenjualan' => str_replace('/', '-', $idPenjualan),
            ];
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            $jsonData = [
                'status' => false,
                'keterangan' => $e->getMessage(),
            ];
        }

        return json_encode($jsonData);
    }
}
