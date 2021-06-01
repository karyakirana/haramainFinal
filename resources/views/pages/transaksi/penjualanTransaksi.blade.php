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
                    <input type="text" name="temp" hidden value="{{$idTemp}}">
                    <input type="text" name="id_penjualan" hidden value="{{$idPenjualan}}">
                    <input type="text" name="id_customer" hidden>
                    <input type="text" name="diskonCustomer" hidden>
                    <input type="text" name="idSales" value="{{Auth()->user()->id}}" hidden>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label text-lg-left" for="nomorTransaksi">Nomor </label>
                        <div class="col-md-4">
                            <input type="text" class="form-control" id="nomorTransaksi" name="nomorTransaksi" value="{{$idPenjualan}}">
                        </div>
                        <label class="col-md-2 col-form-label text-lg-left" for="tanggalNota">Tanggal Nota</label>
                        <div class="col-md-4">
                            <input type="text" class="form-control" id="tanggalNota" name="tanggalNota" value="{{ date('d-M-Y') }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label text-lg-left" for="customerInput">Customer </label>
                        <div class="col-md-4">
                            <div class="input-group">
                                <input type="text" class="form-control" id="customerInput" name="customer">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" id="cariCustomerBtn" type="button">Cari</button>
                                </div>
                            </div>
                        </div>
                        <label class="col-md-2 col-form-label text-lg-left" for="tanggalTempo">Tanggal Tempo</label>
                        <div class="col-md-4">
                            <input type="text" class="form-control" id="tanggalTempo" name="tanggalTempo" value="{{ date('d-M-Y', strtotime(" +2 months")) }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label text-lg-left" for="sales">Sales </label>
                        <div class="col-md-4">
                            <input type="text" class="form-control" id="sales" name="sales" value="{{Auth()->user()->name}}">
                        </div>
                        <label class="col-md-2 col-form-label text-lg-left" for="keterangan">Keterangan</label>
                        <div class="col-md-4">
                            <textarea name="keterangan" id="keterangan" rows="0" class="form-control"></textarea>
                        </div>
                        <label class="col-md-2 col-form-label text-lg-left" for="sales">Status Bayar </label>
                        <div class="col-md-4">
                            <select name="statusBayar" id="statusBayar" class="form-control">
                                <option value="Tunai" selected>Tunai</option>
                                <option value="Tempo">Tempo</option>
                            </select>
                        </div>
                    </div>
                </form>

                <!--begin: Datatable-->
                <div class="example">
                    <div class="example-preview">
                        <table class="table table-bordered table-hover" id="kt_datatable" style="margin-top: 13px !important">
                            <thead>
                            <tr>
{{--                                <th>id</th>--}}
                                <th>Nama Produk</th>
{{--                                <th>Kategori Harga</th>--}}
                                <th>Harga</th>
                                <th>Jumlah</th>
                                <th>Diskon</th>
                                <th>Sub Total</th>
                                <th></th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <!--end: Datatable-->
                <div class="card-spacer-x py-5">
                    <form action="#" id="formFooter">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group row">
                                    <label class="col-lg-4 col-form-label">PPN :</label>
                                    <div class="col-lg-8">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="ppn">
                                            <div class="input-group-append">
                                                <span class="input-group-text">%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-4 col-form-label">Biaya Lain</label>
                                    <div class="col-lg-8">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Rp. </span>
                                            </div>
                                            <input type="text" class="form-control" name="biayaLain">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="font-size-h3">Total Pembayaran :</label>
                                    <input type="text" name="totalSemuanya" class="form-control display-5 font-weight-bold font-size-h1 px-14" readonly>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>

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
                                <label class="col-md-4 col-form-label" for="harga">Harga</label>
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Rp. </span>
                                        </div>
                                        <input class="form-control" type="text" id="harga" name="harga" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label" for="diskon">Diskon</label>
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <input class="form-control" data-inputmask="'alias' : 'decimal', 'groupSeparator': ','" type="text" id="diskon" name="diskon">
                                        <div class="input-group-append">
                                            <span class="input-group-text">%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label" for="hargaDiskon"></label>
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Rp. </span>
                                        </div>
                                        <input class="form-control" type="text" id="hargaDiskon" name="hargaDiskon" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label" for="jumlah">Jumlah</label>
                                <div class="col-md-8">
                                    <input class="form-control" type="text" id="jumlah" name="jumlah">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label" for="subTotal">SubTotal</label>
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Rp. </span>
                                        </div>
                                        <input class="form-control" type="text" id="subTotal" name="subTotal" readonly>
                                    </div>
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
                    <button class="btn btn-primary btn-lg font-weight-boldest font-size-h3 px-18" id="submitGlobal">
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
                <th>Harga</th>
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

    {{--begin::modals customer--}}
    <x-modals modalId="modalDaftarCustomer" formId="formCustomerModal" ukuran="besar">
        <table class="table table-bordered table-hover" id="tableCustomer" style="margin-top: 13px !important">
            <thead>
            <tr>
                <th>ID</th>
                <th>Customer</th>
                <th>Diskon</th>
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
    {{--end::modals customer--}}

    <x-slot name="scripts">
        <!--begin::Page Vendors(used by this page)-->
        <script src="/assets/plugins/custom/datatables/datatables.bundle.js"></script>
        <!--end::Page Vendors-->
        <!--begin::Page Scripts(used by this page)-->
        <script src="/pages/penjualanTransaksi.js"></script>
        <!--end::Page Scripts-->
    </x-slot>
</x-metronics-layout>
