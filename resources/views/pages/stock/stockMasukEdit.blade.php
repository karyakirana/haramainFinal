<x-metronics-layout>
    <x-slot name="css">
        <link href="/assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
    </x-slot>

    <x-slot name="subHeader">
        <x-sub-header title="Transaksi Baru" subTitle="Penjualan"></x-sub-header>
    </x-slot>

    <x-metronics-card>
        <div class="row">
            <div class="col-md-8">
                <form action="#" id="master">
                    <input type="text" name="id" value="{{$id}}" hidden>
                    <input type="text" name="temp" hidden value="{{$idTemp}}">
                    <input type="text" name="id_stock" hidden value="{{$kode}}">
                    <input type="text" name="id_supplier" hidden value="{{$idSupplier}}">
                    <input type="text" name="idSales" value="{{Auth()->user()->id}}" hidden>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label text-lg-left" for="nomorTransaksi">Nomor </label>
                        <div class="col-md-4">
                            <input type="text" class="form-control" id="nomorTransaksi" name="nomorTransaksi" value="{{$kode}}">
                        </div>
                        <label class="col-md-2 col-form-label text-lg-left" for="tanggalMasuk">Tanggal Masuk</label>
                        <div class="col-md-4">
                            <input type="text" class="form-control" id="tanggalMasuk" name="tanggalMasuk" value="{{ date('d-M-Y') }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label text-lg-left" for="customerInput">Supplier </label>
                        <div class="col-md-4">
                            <div class="input-group">
                                <input type="text" class="form-control" id="supplierInput" name="supplier" readonly value="{{$namaSupplier}}">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" id="cariSupplierBtn" type="button">Cari</button>
                                </div>
                            </div>
                        </div>
                        <label class="col-md-2 col-form-label text-lg-left" for="nomorPo">Nomor PO</label>
                        <div class="col-md-4">
                            <input type="text" class="form-control" id="nomorPo" name="nomorPo" value="{{$nomorPo}}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label text-lg-left" for="user">User </label>
                        <div class="col-md-4">
                            <select name="gudang" id="gudang" class="form-control">
                                <option value="" selected>Harus diisi</option>
                                @forelse($branch as $row)
                                    <option value="{{$row->id}}" {{ ($row->id == $idBranch) ? 'selected' : ''}}>{{ $row->branchName }}</option>
                                @empty
                                    <option value="" selected>Tidak ada data</option>
                                @endforelse
                            </select>
                        </div>
                        <label class="col-md-2 col-form-label text-lg-left" for="keterangan">Keterangan</label>
                        <div class="col-md-4">
                            <textarea name="keterangan" id="keterangan" rows="0" class="form-control">{{$keterangan}}</textarea>
                        </div>
                    </div>
                </form>

                <!--begin: Datatable-->
                <div class="example">
                    <div class="example-preview">
                        <table class="table table-bordered table-hover" id="kt_datatable" style="margin-top: 13px !important">
                            <thead>
                            <tr>
                                <th>Nomor</th>
                                <th>Nama Produk</th>
                                <th>Jumlah</th>
                                <th></th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <!--end: Datatable-->

            </div>
            <div class="col-md-4">
                <div class="example">
                    <div class="example-preview">
                        <form action="#" id="formProduk">
                            <input type="text" name="idTemp" value="{{$idTemp}}" hidden>
                            <input type="text" name="idDetil" hidden>
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label" for="idProduk">ID Produk</label>
                                <div class="col-md-8">
                                    <input class="form-control" type="text" id="idProduk" name="idProduk" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label" for="namaProduk">Produk</label>
                                <div class="col-md-8">
                                    <textarea name="namaProduk" id="namaProduk" rows="3" class="form-control" readonly></textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label" for="kategoriHarga">Kategori</label>
                                <div class="col-md-8">
                                    <input class="form-control" type="text" id="kategoriHarga" name="kategoriHarga" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label" for="jumlah">Jumlah</label>
                                <div class="col-md-8">
                                    <input class="form-control" type="text" id="jumlah" name="jumlah">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2"></div>
                                <div class="col-md-5">
                                    <button type="button" class="btn btn-success mr-2 btn-block" id="tambahBtn">simpan</button>
                                </div>
                                <div class="col-md-5">
                                    <button type="button" class="btn btn-primary btn-block" id="cariProdukBtn" data-toggle="modal" data-target="#modalProduk">Cari Produk</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card-spacer-x py-5">
                    <button class="btn btn-primary btn-lg font-weight-boldest font-size-h3 px-18" id="submitUpdateGlobal">
                        Simpan dan Cetak
                    </button>
                </div>

            </div>
        </div>

    </x-metronics-card>

    {{--begin::modals produk--}}
    <x-modals modalId="modalDaftarProduk" formId="formProdukModal" ukuran="besar">
        <table class="table table-bordered table-hover" id="tableProduk" style="margin-top: 13px !important">
            <thead>
            <tr>
                <th>Kode</th>
                <th>ID Lokal</th>
                <th width="20%">Judul</th>
                <th>Kategori</th>
                <th>Kategori Harga</th>
                <th>Penerbit</th>
                <th class="none">Halaman</th>
                <th class="none">Size</th>
                <th>Cover</th>
                <th class="none">Keterangan</th>
                <th></th>
            </tr>
            </thead>
        </table>

        <x-slot name="footer">
            <button type="button"  class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
            <button type="button" id="btnSubmit" class="btn btn-primary font-weight-bold">Save changes</button>
        </x-slot>
    </x-modals>
    {{--end::modals produk--}}

    {{--begin::modals Supplier--}}
    <x-modals modalId="modalDaftarSupplier" formId="formSupplierModal" ukuran="besar">
        <table class="table table-bordered table-hover" id="tableSupplier" style="margin-top: 13px !important">
            <thead>
            <tr>
                <th>ID</th>
                <th>Supplier</th>
                <th>Jenis</th>
                <th>Telepon</th>
                <th>Alamat</th>
                <th>Keterangan</th>
                <th>Actions</th>
            </tr>
            </thead>
        </table>

        <x-slot name="footer">
            <button type="button"  class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
            <button type="button" id="btnSubmit" class="btn btn-primary font-weight-bold">Save changes</button>
        </x-slot>
    </x-modals>
    {{--end::modals Supplier--}}

    <x-slot name="scripts">
        <!--begin::Page Vendors(used by this page)-->
        <script src="/assets/plugins/custom/datatables/datatables.bundle.js"></script>
        <!--end::Page Vendors-->
        <!--begin::Page Scripts(used by this page)-->
        <script src="/pages/stockMasukTransaksi.js"></script>
        <!--end::Page Scripts-->
    </x-slot>
</x-metronics-layout>
