<x-metronics-layout>
    <x-slot name="css">
        <link href="/assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
    </x-slot>

    <x-slot name="subHeader">
        <x-sub-header title="Kasir" subTitle="Data Stock"></x-sub-header>
    </x-slot>

    <x-metronics-card>
        <x-slot name="title">
            <span class="card-icon">
                <i class="flaticon2-supermarket text-primary"></i>
            </span>
            <h3 class="card-label">Data Stock Realtime</h3>
        </x-slot>

        <x-slot name="toolbar">
            <!--begin::Button-->
            <a href="#" class="btn btn-primary font-weight-bolder mr-5" id="btnStockAkhir">From Stock Akhir</a>
            <a href="#" class="btn btn-warning font-weight-bolder mr-5" id="btnStockIn">From Stock Masuk</a>
            <a href="#" class="btn btn-primary font-weight-bolder" id="btnSales">From Sales</a>
            <!--end::Button-->
        </x-slot>

        <!--begin: Datatable-->
        <table class="table table-bordered table-hover table-checkable" id="kt_datatable" style="margin-top: 13px !important">
            <thead>
            <tr>
                {{--                <th>ID</th>--}}
                <th>Kode</th>
                <th>Gudang</th>
                <th>Produk</th>
                <th>Stock In</th>
                <th>Stock Out</th>
                <th>Stock Real</th>
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

            let table = document.getElementById('kt_datatable');

            // begin first table
            $(table).DataTable({
                responsive: true,
                searchDelay: 10,
                processing: true,
                serverSide: true,
                order : [],
                ajax: {
                    headers : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url: '{{ route('stockRealList') }}',
                    type: 'PATCH',
                    data: {
                        // parameters for custom backend script demo
                        columnsDef: [
                            'idProduk', 'branchId',
                            'produkName',
                            'branchName',
                            'stockIn', 'stockOut', 'stockNow'
                        ],
                    },
                },
                columns: [
                    // {data: 'kode'},
                    {data: 'idProduk'},
                    {data: 'branchName'},
                    {data: 'produkName'},
                    {data: 'stockIn', render : $.fn.dataTable.render.number( '.', ',', 0, '')},
                    {data: 'stockOut', render : $.fn.dataTable.render.number( '.', ',', 0, '')},
                    {data: 'stockNow', render : $.fn.dataTable.render.number( '.', ',', 0, '')},
                    // {data: 'Actions', responsivePriority: -1},
                ],
                columnDefs: [
                    {
                        // targets : [-1],
                        // orderable: false
                    }
                ],
            });

            $('#btnStockAkhir').on('click', function (){
                $.ajax({
                    headers : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url: '{{ route('refreshStockFromAkhir') }}',
                    type: 'PUT',
                    success : function (data) {
                       if (data.status){
                           reloadTable();
                           swal.fire({
                               html : data.insert+"<br/>"+data.update,
                           });
                       } else {
                           console.log(data.keterangan);
                           swal.fire({
                               html: data.keterangan,
                           });
                       }
                    },
                    error : function (jqXHR, textStatus, errorThrown)
                    {
                        swal.fire({
                            html: jqXHR.responseJSON.message+"<br><br>"+jqXHR.responseJSON.file+"<br><br>Line: "+jqXHR.responseJSON.line,
                        });
                    }
                });
            })

            $('#btnStockIn').on('click', function (){
                $.ajax({
                    headers : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url: '{{ route('refreshStockFromGudangIn') }}',
                    type: 'PUT',
                    success : function (data) {
                        if (data.status){
                            reloadTable();
                            swal.fire({
                                html : data.insert+"<br/>"+data.update,
                            });
                        } else {
                            console.log(data.keterangan);
                            swal.fire({
                                html: data.keterangan,
                            });
                        }
                    },
                    error : function (jqXHR, textStatus, errorThrown)
                    {
                        swal.fire({
                            html: jqXHR.responseJSON.message+"<br><br>"+jqXHR.responseJSON.file+"<br><br>Line: "+jqXHR.responseJSON.line,
                        });
                    }
                });
            })

            $('#btnSales').on('click', function (){
                $.ajax({
                    headers : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url: '{{ route('refreshStockFromGudangOut') }}',
                    type: 'PUT',
                    success : function (data) {
                        if (data.status){
                            reloadTable();
                            swal.fire({
                                html : data.insert+"<br/>"+data.update,
                            });
                        } else {
                            console.log(data.keterangan);
                            swal.fire({
                                html: data.keterangan.errorInfo,
                            });
                        }
                    },
                    error : function (jqXHR, textStatus, errorThrown)
                    {
                        swal.fire({
                            html: jqXHR.responseJSON.message+"<br><br>"+jqXHR.responseJSON.file+"<br><br>Line: "+jqXHR.responseJSON.line,
                        });
                    }
                });
            })
        </script>
        <!--end::Page Scripts-->
    </x-slot>

</x-metronics-layout>
