<x-metronics-layout>
    <x-slot name="css">
        <link href="/assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />

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

        <x-slot name="toolbar">
            <!--begin::Button-->
            <a href="#" onclick="add()" class="btn btn-primary font-weight-bolder" id="btnNew">
                <span class="svg-icon svg-icon-md">
                    <!--begin::Svg Icon | path:assets/media/svg/icons/Design/Flatten.svg-->
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <rect x="0" y="0" width="24" height="24" />
                            <circle fill="#000000" cx="9" cy="15" r="6" />
                            <path d="M8.8012943,7.00241953 C9.83837775,5.20768121 11.7781543,4 14,4 C17.3137085,4 20,6.6862915 20,10 C20,12.2218457 18.7923188,14.1616223 16.9975805,15.1987057 C16.9991904,15.1326658 17,15.0664274 17,15 C17,10.581722 13.418278,7 9,7 C8.93357256,7 8.86733422,7.00080962 8.8012943,7.00241953 Z" fill="#000000" opacity="0.3" />
                        </g>
                    </svg>
                    <!--end::Svg Icon-->
                </span>New Record
            </a>
            <!--end::Button-->
        </x-slot>

        <!--begin: Datatable-->
        <table class="table table-bordered table-hover table-checkable" id="kt_datatable" style="margin-top: 13px !important">
            <thead>
            <tr>
                <th>Kode</th>
                <th>Kategori</th>
                <th>Deskripsi</th>
                <th>Actions</th>
            </tr>
            </thead>
        </table>
        <!--end: Datatable-->

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
                    <input class="form-control" name="namaAkun" type="text">
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
                    {data : 'namaAkun'},
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
            function add()
            {
                saveMethod = 'add';
                $('#formModal').trigger('reset'); // reset form on modals
                $('#modalCrud').modal('show'); // show bootstrap modal
            }

            $('#btnSubmit').on('click', function (){
                store();
            });

            function store()
            {
                $.ajax({
                    headers : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url : HOST_me+"/akuntansi/master/kategoriakun/",
                    method : 'POST',
                    data : $('#formModal').serialize(),
                    dataType : "JSON",
                    success : function(data)
                    {
                        if(data.status){
                            $('#modalCrud').modal('hide');
                            $('#alertTable').addClass('alert-light-success show');
                            $("#alertText").append("Data Berhasil Diubah");
                            reloadTable();
                        }
                    },
                    error : function(jqXHR, textStatus, errorThrown0)
                    {
                        // console.log(jqXHR.responseJSON);
                        swal.fire({
                            html: jqXHR.responseJSON.message+"<br><br>"+jqXHR.responseJSON.file+"<br><br>Line: "+jqXHR.responseJSON.line,
                        });
                        for (const property in jqXHR.responseJSON.errors) {
                            // console.log(`${property}: ${jqXHR.responseJSON.errors[property]}`);
                            $('.invalid-feedback').remove();

                            $('[name="'+`${property}`+'"').addClass('is-invalid').after('<div class="invalid-feedback" style="display: block;">'+`${jqXHR.responseJSON.errors[property]}`+'</div>');
                            $("#alertText").empty();
                            $("#alertText").append("<li>"+`${jqXHR.responseJSON.errors[property]}`+"</li>");
                        }
                    }
                });
            }

            function edit(id)
            {
                saveMethod = 'edit';
                console.log(saveMethod);
                $.ajax({
                    headers : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url : HOST_me+"/akuntansi/master/kategoriakun/"+id,
                    method: "GET",
                    dataType : "JSON",
                    success : function (data) {
                        $('#formModal').trigger('reset'); // reset form on modals
                        // insert value
                        $('[name="id"]').val(data.id);
                        $('[name="kode"]').val(data.kode);
                        $('[name="namaAkun"]').val(data.namaAkun);
                        $('[name="keterangan"]').val(data.deskripsi);
                        $('#modalCrud').modal('show'); // show bootstrap modal
                    },
                    error : function (jqXHR, textStatus, errorThrown)
                    {
                        swal.fire({
                            html: jqXHR.responseJSON.message+"<br><br>"+jqXHR.responseJSON.file+"<br><br>Line: "+jqXHR.responseJSON.line,
                        });
                    }
                });
            }

            function destroy(id)
            {
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url: HOST_me + "/akuntansi/master/kategoriakun/" + id,
                    type: "DELETE",
                    dataType: "JSON",
                    success: function (data) {
                        //if success reload ajax table
                        reloadTable();
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

        </script>

    </x-slot>
</x-metronics-layout>
