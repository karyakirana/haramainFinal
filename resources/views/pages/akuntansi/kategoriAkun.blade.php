<x-metronics-layout>
    <x-slot name="css">
        <link href="/assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
        @livewireStyles
    </x-slot>

    <x-slot name="subHeader">
        <x-sub-header title="Kasir" subTitle="Kategori Akun"></x-sub-header>
    </x-slot>

    <x-metronics-card>
        <x-slot name="title">
            <span class="card-icon">
                <i class="flaticon2-supermarket text-primary"></i>
            </span>
            <h3 class="card-label">Data Pembayaran</h3>
        </x-slot>

        <!--begin: Datatable-->
        @livewire('akuntansi.table-kategori-akun')

        <x-modals modalId="modalCrud" formId="formModal">
            <input type="text" name="id" hidden>
            <div class="form-group row">
                <label class="col-3 col-form-label">Kode</label>
                <div class="col-9">
                    <input class="form-control" name="kode" type="text">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-3 col-form-label">Kategori</label>
                <div class="col-9">
                    <input class="form-control" name="nama" type="text">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-3 col-form-label">Keterangan</label>
                <div class="col-9">
                    <textarea class="form-control" name="keterangan" type="text" id="example-text-input"></textarea>
                </div>
            </div>

            <x-slot name="footer">
                <button type="button"  class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                <button type="button" id="btnSubmit" class="btn btn-primary font-weight-bold">Save changes</button>
            </x-slot>
        </x-modals>

    </x-metronics-card>

    <x-modals modalId="kategoriAkun" headerId="kategoriAkun" formId="kategoriAkun">
        <x-slot name="modalTitle">Tambah Kategori Akun</x-slot>
        <div class="form-group row">
            <label class="col-2 col-form-label">Kode Akun</label>
            <div class="col-10">
                <input class="form-control" type="text" name="jenis">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-2 col-form-label">Jenis Supplier</label>
            <div class="col-10">
                <input class="form-control" type="text" name="jenis">
            </div>
        </div>
        <x-slot name="footer"></x-slot>
    </x-modals>

    <x-slot name="scripts">
        <!--begin::Page Vendors(used by this page)-->

        <script src="/assets/plugins/custom/datatables/datatables.bundle.js"></script>
        <!--end::Page Vendors-->
        <!--begin::Page Scripts(used by this page)-->
        <script>

            var tableKategoriAkun = $('#kt_datatable');

            tableKategoriAkun.DataTable({
                ajax : {
                    headers : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url: '{{ route('kategoriAkunList') }}',
                    type: 'PATCH',
                    data: {
                        // parameters for custom backend script demo
                        columnsDef: [
                            'kode', 'idPenjualan', 'tgl_nota'
                        ],
                    },
                },
                columns : [
                    {data : 'kode'},
                    {data : 'nama'},
                    {data : 'deskripsi'},
                    {data : 'Actions'}
                ],
                columnDefs: [
                    {
                        targets : [-1],
                        orderable: false
                    }
                ],
                responsive: true,
                searchDelay: 100,
                processing: true,
                serverSide: true,
                order : [],
            });

            function reloadTable()
            {
                tableKategoriAkun.DataTable().ajax.reload();
            }

            /*
             * CRUD Ajax
             */

            // Modal kategori akun show

        </script>
        @livewireScripts
    </x-slot>
</x-metronics-layout>
