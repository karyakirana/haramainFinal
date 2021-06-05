<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Models\Master\Customer;
use App\Models\Transaksi\Penjualan;
use App\Models\Transaksi\PenjualanDetil;
use App\Models\Transaksi\PenjualanDetilTemp;
use App\Models\Transaksi\PenjualanTemp;
use App\Models\User;
use PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\DataTables;

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

        return DataTables::of($data)
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
                $btnEdit = '<a href="'.url('/penjualan/')."/".str_replace('/', '-', $row->penjualanId)."/edit".'" class="btn btn-sm btn-clean btn-icon" title="Edit details">
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
//        session()->forget('penjualan'); // hapus session temp
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
        $idPenjualanTemp = $request->temp;
        $tglPenjualan = date('Y-m-d', strtotime($request->tanggalNota));
        $tglTempo = date('Y-m-d', strtotime($request->tanggalTempo));

        // Ambil Data detail_penjualan_temp
        $detilTemp = PenjualanDetilTemp::where('idPenjualanTemp', $idPenjualanTemp)->get();

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
            'tgl_tempo' => ($request->statusBayar == 'Tunai') ? $tglTempo : null,
            'status_bayar' => $request->statusBayar,
            'sudahBayar'=> "belum", // pembuatan nota belum bayar
            'total_jumlah' => $detilTemp->count(), // jumlah Item
            'ppn' => $request->ppn,
            'biaya_lain' => $request->biayaLain,
            'total_bayar' => $detilTemp->sum('sub_total') + $request->ppn + $request->biayaLain, // total semua subtotal atau $request->hiddenTotalSemuanya
            'id_cust' => $request->id_customer,
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

    public function print($id)
    {
        $idPenjualan = str_replace('-', '/', $id);

        $dataPen = Penjualan::leftJoin('user as u', 'penjualan.id_user', '=', 'u.id_user')
            ->leftJoin('users', 'penjualan.id_user', '=', 'users.idUserOld')
            ->leftJoin('customer as c', 'penjualan.id_cust', '=', 'c.id_cust')
            ->select(
                'penjualan.id_jual as penjualanId',
                'c.nama_cust as namaCustomer',
                'addr_cust',
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
            ->where('id_jual', $idPenjualan)
            ->first();
        $dataPenjualan = [
            'penjualanId' => $dataPen->penjualanId,
            'namaCustomer' => $dataPen->namaCustomer,
            'addr_cust' => $dataPen->addr_cust,
            'tgl_nota' => date('d-m-Y', strtotime($dataPen->tgl_nota)),
            'tgl_tempo' => ( strtotime($dataPen->tgl_tempo) > 0) ? date('d-m-Y', strtotime($dataPen->tgl_tempo)) : '',
            'status_bayar' => $dataPen->status_bayar,
            'sudahBayar' => $dataPen->sudahBayar,
            'total_jumlah' => $dataPen->total_jumlah,
            'ppn' => $dataPen->ppn,
            'biaya_lain' => $dataPen->biaya_lain,
            'total_bayar' => $dataPen->total_bayar,
            'penket' => $dataPen->penket,
            'print' => $dataPen->print,
            'update' => $dataPen->update,
        ];
        // update print
        $updatePrint = Penjualan::where('id_jual', $idPenjualan)->update(['print' => $dataPen->print + 1]);
        // $dataPenjualan = Penjualan::where('id_jual', $idPenjualan)->first();
        // $dataPenjualanDetail = PenjualanDetail::where('id_jual', $idPenjualan)->get();
        $dataPenjualanDetail = PenjualanDetil::leftJoin('produk', 'detil_penjualan.id_produk', '=', 'produk.id_produk')
            ->whereNull('detil_penjualan.updated_at')
            ->where('id_jual', $idPenjualan)
            ->get();
        $data = [
            'dataUtama' => json_encode($dataPenjualan),
            'dataDetail' => $dataPenjualanDetail
        ];
        return view('pages.report.invoicePenjualan', $data);
    }

    public function printpdf($id)
    {
        $idPenjualan = str_replace('-', '/', $id);

        $dataPen = Penjualan::leftJoin('user as u', 'penjualan.id_user', '=', 'u.id_user')
            ->leftJoin('users', 'penjualan.id_user', '=', 'users.idUserOld')
            ->leftJoin('customer as c', 'penjualan.id_cust', '=', 'c.id_cust')
            ->select(
                'penjualan.id_jual as penjualanId',
                'c.nama_cust as namaCustomer',
                'addr_cust',
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
            ->where('id_jual', $idPenjualan)
            ->first();
        $dataPenjualan = [
            'penjualanId' => $dataPen->penjualanId,
            'namaCustomer' => $dataPen->namaCustomer,
            'addr_cust' => $dataPen->addr_cust,
            'tgl_nota' => date('d-m-Y', strtotime($dataPen->tgl_nota)),
            'tgl_tempo' => ( strtotime($dataPen->tgl_tempo) > 0) ? date('d-m-Y', strtotime($dataPen->tgl_tempo)) : '',
            'status_bayar' => $dataPen->status_bayar,
            'sudahBayar' => $dataPen->sudahBayar,
            'total_jumlah' => $dataPen->total_jumlah,
            'ppn' => $dataPen->ppn,
            'biaya_lain' => $dataPen->biaya_lain,
            'total_bayar' => $dataPen->total_bayar,
            'penket' => $dataPen->penket,
            'print' => $dataPen->print,
            'update' => $dataPen->update,
        ];
        // update print
        $updatePrint = Penjualan::where('id_jual', $idPenjualan)->update(['print' => $dataPen->print + 1]);
        // $dataPenjualan = Penjualan::where('id_jual', $idPenjualan)->first();
        // $dataPenjualanDetail = PenjualanDetail::where('id_jual', $idPenjualan)->get();
        $dataPenjualanDetail = PenjualanDetil::leftJoin('produk', 'detil_penjualan.id_produk', '=', 'produk.id_produk')
            ->whereNull('detil_penjualan.updated_at')
            ->where('id_jual', $idPenjualan)
            ->get();
        $data = [
            'dataUtama' => json_encode($dataPenjualan),
            'dataDetail' => $dataPenjualanDetail
        ];
        $pdf = PDF::loadView('pages.report.invoicePdf', $data);
        return $pdf->stream();
    }

    /**
     * Mengambil data sesuai dengan id
     *
     * @param  string id
     * @return json|false|string
     */
    public function edit($id)
    {
        // convert to id bersangkutan
        $uniqueId = str_replace('-', '/', $id);

        // jenisTemp
        $jenisTemp = substr($uniqueId, 5, 2);

        // ambil data detail yg diedit
        $detail = PenjualanDetil::where('id_jual', $uniqueId)->get();

        // ambil data Master
        $master = Penjualan::where('id_jual', $uniqueId)->first();

        // delete temp sebelumnya
        $deleteMaster = PenjualanTemp::where('id_jual', $uniqueId)->first();
        if ($deleteMaster) {
            PenjualanDetilTemp::where('idPenjualanTemp', $deleteMaster->id)->delete();
            PenjualanTemp::where('id_jual', $uniqueId)->delete();
        }

        $tempMaster = PenjualanTemp::create([
            'jenisTemp' => 'Penjualan',
            'id_jual' => $uniqueId,
            'idSales' => Auth::user()->id,
        ]);

        // jika $tempMaster gagal return error
        if(!isset($tempMaster->id))
        {
            return "error";
        }

        foreach ($detail as $temp) {
            $detailTemp = PenjualanDetilTemp::create([
                'idPenjualanTemp' => $tempMaster->id,
                'idBarang' => $temp->id_produk,
                'jumlah' => $temp->jumlah,
                'harga' => $temp->harga,
                'diskon' => $temp->diskon,
                'sub_total' => $temp->sub_total
            ]);
        }
        $data = [
            'idTemp' => $tempMaster->id,
            'idSales' => Auth::user()->id,
            'namaSales' => Auth::user()->name,
            'idPenjualan' => $uniqueId,
            'idCustomer' => $master->id_cust,
            'namaCustomer' => Customer::where('id_cust', $master->id_cust)->first()->nama_cust,
            'tgl_nota' => date('d-m-Y' , strtotime($master->tgl_nota)),
            'tgl_tempo' => (strtotime($master->tgl_tempo) > 0) ? date('d-m-Y' , strtotime($master->tgl_tempo)) : '',
            'status_bayar' => $master->status_bayar,
            'keterangan' => $master->keterangan,
            'ppn' => $master->ppn,
            'biaya_lain' => $master->biaya_lain,
        ];
        return view('pages.transaksi.penjualanTransaksiEdit', $data);
        // var_dump($dataDetailPenjualan);
    }

    public function update(Request $request)
    {
        // variabel-variabel umum
        $idPenjualan = $request->id_penjualan;
        $idPenjualanTemp = $request->temp;
        $tglPenjualan = date('Y-m-d', strtotime($request->tanggalNota));
        $tglTempo = date('Y-m-d', strtotime($request->tanggalTempo));

        // Ambil Data detail_penjualan_temp
        $detilTemp = PenjualanDetilTemp::where('idPenjualanTemp', $idPenjualanTemp)->get();

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
            // 'activeCash' => session('ClosedCash'),
            // 'id_jual' => $idPenjualan,
            'tgl_nota' => $tglPenjualan,
            'tgl_tempo' => ($request->statusBayar == 'Tunai') ? $tglTempo : null,
            'status_bayar' => $request->statusBayar,
            'sudahBayar'=> "belum", // pembuatan nota belum bayar
            'total_jumlah' => $detilTemp->count(), // jumlah Item
            'ppn' => $request->ppn,
            'biaya_lain' => $request->biayaLain,
            'total_bayar' => $detilTemp->sum('sub_total') + $request->ppn + $request->biayaLain, // total semua subtotal atau $request->hiddenTotalSemuanya
            'id_cust' => $request->id_customer,
            'id_user' => Auth::user()->id,
            'keterangan' => $request->keterangan
        ];

        // transaction start
        $jsonData = null;
        DB::beginTransaction();
        try {
            $deleteDetail = PenjualanDetil::where('id_jual', $idPenjualan)->delete();
            $insertDetail = PenjualanDetil::insert($dataDetil);
            $insertMaster = Penjualan::where('id_jual', $idPenjualan)->update($data);
            $deleteTempDetail = PenjualanDetilTemp::where('idPenjualanTemp', $idPenjualanTemp)->delete();
            $deleteTempMaster = PenjualanTemp::where('id', $idPenjualanTemp)->delete();
            DB::commit();
            // session()->forget('penjualan'); // hapus session temp
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
