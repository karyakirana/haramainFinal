<x-metronics-layout>

    <x-slot name="subHeader">
        <x-sub-header title="Kasir" subTitle="Pembayaran Nota"></x-sub-header>
    </x-slot>

    <x-metronics-card>
        <x-slot name="title">
            <span class="card-icon">
                <i class="flaticon2-supermarket text-primary"></i>
            </span>
            <h3 class="card-label">Input Pembayaran Nota</h3>
        </x-slot>

        <form id="pembayaranForm" action="{{route('submitPembayaran')}}" method="POST">
            @csrf
            <input type="text" name="id" hidden>
            <div class="form-group row">
                <div class="col-lg-6">
                    <label>Kode</label>
                    <input type="text" name="kode" class="form-control" id="kode" readonly>
                    @error('title')
                    <span class="form-text text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-lg-6">
                    <label>Kode Internal</label>
                    <input type="text" class="form-control @error('kodeInternal') is-invalid @enderror" name="kodeInternal" value="{{ old('kodeInternal') }}">
                    @error('kodeInternal')
                    <span class="form-text text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="form-group row">
                <div class="col-lg-6">
                    <label>ID Penjualan</label>
                    <div class="input-group">
                        <input type="text" name="idPenjualan" class="form-control @error('idPenjualan') is-invalid @enderror" value="{{ old('idPenjualan') }}" readonly>
                        <div class="input-group-append">
                            <button class="btn btn-primary" id="penjualanBtn" onclick="addPenjualan()" type="button">Cari</button>
                        </div>
                    </div>
                    @error('idPenjualan')
                    <span class="form-text text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-lg-6">
                    <label>Jenis Bayar</label>
                    <select name="jenisBayar" class="form-control @error('jenisBayar') is-invalid @enderror" id="jenisBayar">
                        <option disabled {{(!old('jenisBayar')) ? 'selected' : ''}}>Silahkan Pilih</option>
                        <option value="cash" @if(old('jenisBayar') == 'cash') selected @endif>Cash</option>
                        <option value="transfer" @if(old('jenisBayar') == 'transfer') selected @endif>Transfer</option>
                    </select>
                    @error('jenisBayar')
                    <span class="form-text text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="form-group row">
                <div class="col-lg-6">
                    <label>Tanggal</label>
                    <input type="text" id="tanggal" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror" value="{{old('tanggal')}}">
                    @error('tanggal')
                    <span class="form-text text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-lg-6">
                    <label>Nominal</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Rp .</span>
                        </div>
                        <input type="text" class="form-control @error('nominal') is-invalid @enderror" name="nominal">
                    </div>
                    @error('nominal')
                    <span class="form-text text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="form-group row">
                <div class="col-lg-6">
                    <label>User</label>
                    <input type="text" name="user" class="form-control" readonly value="{{Auth()->user()->name}}">
                    <span class="form-text text-danger"></span>
                </div>
                <div class="col-lg-6">
                    <label>Keterangan</label>
                    <input type="text" class="form-control" name="keterangan">
                    <span class="form-text text-danger"></span>
                </div>
            </div>
        </form>
        <x-slot name="footer">
            <button type="submit" onclick='$( "#pembayaranForm" ).submit()' id="submitBtn" class="btn btn-primary mr-2">Submit</button>
        </x-slot>
    </x-metronics-card>

    <x-modals modalId="modalCrud" formId="formModal" ukuran="besar">
        <table class="table table-bordered table-hover table-checkable" id="kt_datatable" style="margin-top: 13px !important">
            <thead>
            <tr>
                <th width="15%">Nomor Nota</th>
                <th>Customer</th>
                <th>Tanggal Nota</th>
                <th>Tanggal Tempo</th>
                <th>Status Bayar</th>
                <th>Jumlah Bayar</th>
                <th class="none">Keterangan</th>
                <th width="5%">Actions</th>
            </tr>
            </thead>
        </table>

        <x-slot name="footer">
            <button type="button"  class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
        </x-slot>
    </x-modals>

    <x-slot name="scripts">
        <!--begin::Page Vendors(used by this page)-->
        <script src="/assets/plugins/custom/datatables/datatables.bundle.js"></script>
        <!--end::Page Vendors-->

        <script>
            jQuery(document).ready(function() {
                KTDatatablesDataSourceAjaxServer.init();
                KTBootstrapDatepicker.init();
            });

            var KTDatatablesDataSourceAjaxServer = function() {

                var initTable1 = function() {
                    var table = $('#kt_datatable');

                    // begin first table
                    table.DataTable({
                        responsive: true,
                        searchDelay: 500,
                        processing: true,
                        serverSide: true,
                        autoWidth: false,
                        order : [],
                        ajax: {
                            headers : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            url: HOST_me + '/kasir/pembayaran/penjualan',
                            type: 'PATCH',
                            data: {
                                // parameters for custom backend script demo
                                columnsDef: [
                                    'id_jual', 'tgl_nota', 'status_bayar'
                                ],
                            },
                        },
                        columns: [
                            {data: 'id_jual'},
                            {data: 'nama_cust'},
                            {data: 'tglNota'},
                            {data: 'tglTempo'},
                            {data: 'status_bayar'},
                            {data: 'total_bayar', render: $.fn.dataTable.render.number( '.', ',', 0, 'Rp. ')},
                            {data: 'penket'},
                            {data: 'Actions', width : "5%",responsivePriority: -1},
                        ],
                        columnDefs: [
                            {
                                targets : [-1],
                                orderable: false
                            }
                        ],
                    });
                };

                return {

                    //main function to initiate the module
                    init: function() {
                        initTable1();
                    },

                };

            }();

            function addPenjualan(){
                $('#modalCrud').modal('show'); // show bootstrap modal
            }

            function setPenjualan($id)
            {
                $('[name="idPenjualan"]').val($id);
                $('#modalCrud').modal('hide'); // show bootstrap modal
            }

            // datepicker
            var KTBootstrapDatepicker = function () {

                var arrows;
                if (KTUtil.isRTL()) {
                    arrows = {
                        leftArrow: '<i class="la la-angle-right"></i>',
                        rightArrow: '<i class="la la-angle-left"></i>'
                    }
                } else {
                    arrows = {
                        leftArrow: '<i class="la la-angle-left"></i>',
                        rightArrow: '<i class="la la-angle-right"></i>'
                    }
                }

                // Private functions
                var demos = function () {
                    // minimum setup
                    $('#tanggal').datepicker({
                        rtl: KTUtil.isRTL(),
                        format: 'dd-mm-yyyy',
                        todayHighlight: true,
                        orientation: "bottom left",
                        templates: arrows
                    });
                }

                return {
                    // public functions
                    init: function() {
                        demos();
                    }
                };
            }();
        </script>
    </x-slot>

</x-metronics-layout>
