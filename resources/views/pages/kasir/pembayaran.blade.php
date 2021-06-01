<x-metronics-layout>
    <x-slot name="css">
        <link href="/assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
    </x-slot>

    <x-slot name="subHeader">
        <x-sub-header title="Kasir" subTitle="Data Pembayaran"></x-sub-header>
    </x-slot>

    <x-metronics-card>
        <x-slot name="title">
            <span class="card-icon">
                <i class="flaticon2-supermarket text-primary"></i>
            </span>
            <h3 class="card-label">Data Pembayaran</h3>
        </x-slot>

        <!--begin: Datatable-->
        <table class="table table-bordered table-hover table-checkable" id="kt_datatable" style="margin-top: 13px !important">
            <thead>
            <tr>
{{--                <th>ID</th>--}}
                <th>Kode</th>
                <th>Tgl Pembayaran</th>
                <th>ID Penjualan</th>
                <th>Jenis Bayar</th>
                <th>Pembuat</th>
                <th>Nominal</th>
                <th>Actions</th>
            </tr>
            </thead>
        </table>

    </x-metronics-card>

    <x-slot name="scripts">
        <!--begin::Page Vendors(used by this page)-->
        <script src="/assets/plugins/custom/datatables/datatables.bundle.js"></script>
        <!--end::Page Vendors-->
        <!--begin::Page Scripts(used by this page)-->
        <script>
            // reload table
            function reloadTable()
            {
                $('#kt_datatable').DataTable().ajax.reload();
            }

            jQuery(document).ready(function() {
                KTDatatablesDataSourceAjaxServer.init();
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
                        order : [],
                        ajax: {
                            headers : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            url: HOST_me + '/kasir/pembayaran/data',
                            type: 'PATCH',
                            data: {
                                // parameters for custom backend script demo
                                columnsDef: [
                                    'kode', 'idPenjualan', 'tgl_nota'
                                ],
                            },
                        },
                        columns: [
                            // {data: 'kode'},
                            {data: 'kodeInternal'},
                            {data: 'tglPembayaran'},
                            {data: 'idPenjualan'},
                            {data: 'jenisBayar'},
                            {data: 'name'},
                            {data: 'nominal', render : $.fn.dataTable.render.number( '.', ',', 0, 'Rp. ')},
                            {data: 'Actions', responsivePriority: -1},
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

            function hapus(id)
            {
                if (confirm('Serius untuk hapus data?')) {
                    // var dataDelete = $(this).data("value");
                    // ajax delete data to database
                    $.ajax({
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        url: HOST_me + "/kasir/pembayaran/delete/" + id,
                        type: "DELETE",
                        dataType: "JSON",
                        success: function (data) {
                            //if success reload ajax table
                            reloadTable();
                            $('#alertTable').addClass('alert-light-success show');
                            $("#alertText").append("Data Berhasil Dihapus");
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            swal.fire({
                                html: jqXHR.responseJSON.message + "<br><br>" + jqXHR.responseJSON.file + "<br><br>Line: " + jqXHR.responseJSON.line,
                            });
                            for (const property in jqXHR.responseJSON.errors) {
                                console.log(`${property}: ${jqXHR.responseJSON.errors[property]}`);
                                $("#alertText").append("<li>" + `${jqXHR.responseJSON.errors[property]}` + "</li>");
                            }
                        }
                    });
                }
            }
        </script>
        <!--end::Page Scripts-->
    </x-slot>

</x-metronics-layout>
