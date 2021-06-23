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
                <form action="#" id="master" method="POST">
                    @csrf
                    <input type="text" name="temp" hidden value="{{$tempId}}">
                    <input type="text" name="idRekonsiliasi" hidden value="">
                    <input type="text" name="diskonCustomer" hidden>
                    <input type="text" name="idSales" value="{{Auth()->user()->id}}" hidden>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label text-lg-left" for="nomorTransaksi">Nomor </label>
                        <div class="col-md-4">
                            <input type="text" class="form-control" id="nomorTransaksi" name="nomorTransaksi" value="{{$kode}}">
                        </div>
                        <label class="col-md-2 col-form-label text-lg-left" for="tanggalNota">Tanggal</label>
                        <div class="col-md-4">
                            <input type="text" class="form-control" id="tanggalNota" name="tanggalNota" value="{{ date('d-M-Y') }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        @php
                        $branch = \App\Models\Stock\Branch::all();
                        @endphp
                        <label class="col-md-2 col-form-label text-lg-left" for="gudangAsal">Gudang Asal </label>
                        <div class="col-md-4">
                            <select name="gudangAsal" id="gudangAsal" class="form-control">
                                @forelse($branch as $row)
                                    <option value="{{ $row->id }}" class="option-control" {{ ($row->id == (isset($branchIdAsal))) ? 'selected' : ''}}>{{ $row->branchName }}</option>
                                @empty
                                @endforelse
                            </select>
                        </div>
                        <label class="col-md-2 col-form-label text-lg-left" for="gudangTujuan">Gudang Tujuan</label>
                        <div class="col-md-4">
                            <select name="gudangTujuan" id="gudangTujuan" class="form-control">
                                @forelse($branch as $row)
                                    <option value="{{ $row->id }}" class="option-control" {{ ($row->id == (isset($branchIdAkhir))) ? 'selected' : ''}}>{{ $row->branchName }}</option>
                                @empty
                                @endforelse
                            </select>
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
                        <label class="col-md-2 col-form-label text-lg-left" for="nomorPo">Nomor Po </label>
                        <div class="col-md-4">
                            <input type="text" name="nomorPo" class="form-control">
                        </div>
                    </div>
                </form>

                <!--begin: Datatable-->
                <div class="example">
                    <div class="example-preview">
                        <table class="table table-bordered table-hover" id="kt_datatable" style="margin-top: 13px !important">
                            <thead>
                            <tr>
                                <th></th>
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
                            <input type="text" name="idTemp" value="{{$tempId}}" hidden>
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
                    <button class="btn btn-primary btn-lg font-weight-boldest font-size-h3 px-18" id="submitGlobal">
                        Simpan dan Cetak
                    </button>
                </div>

            </div>
        </div>

    </x-metronics-card>

    {{--begin::modals produk--}}
    <x-modals modalId="modalDaftarProduk" formId="formProdukModal" ukuran="besar">
        <table class="table table-bordered table-hover" id="tableProduk" style="margin-top: 13px !important" width="100%">
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

    <x-slot name="scripts">
        <!--begin::Page Vendors(used by this page)-->
        <script src="/assets/plugins/custom/datatables/datatables.bundle.js"></script>
        <!--end::Page Vendors-->
        <!--begin::Page Scripts(used by this page)-->
        <script>

            // buttton Cari Produk
            $('#cariProdukBtn').on("click",function(){

                $('#modalDaftarProduk').modal('show'); // show bootstrap modal
                $('#modalDaftarProduk').on('shown.bs.modal', function(){

                    $('#tableProduk').DataTable().ajax.reload();

                });

            });

            //datatable
            $('#tableProduk').DataTable({
                responsive: true,
                searchDelay: 500,
                processing: true,
                serverSide: true,
                order : [],
                ajax: {
                    headers : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url: HOST_me + '/stock/temp/produk',
                    type: 'PATCH',
                    data: {
                        // parameters for custom backend script demo
                        columnsDef: [
                            "produkId",
                            "kategori",
                            "nama_produk",
                            "kategoriHarga",
                            "penerbit",
                            "hal",
                            "size",
                            "cover",
                            "harga",
                            "deskripsi",
                            "produkId"
                        ],
                    },
                },
                columns: [
                    {data: 'produkId'},
                    {data: 'kLokal'},
                    {data: 'nama_produk'},
                    {data: 'kategori'},
                    {data: 'kategoriHarga'},
                    {data: 'penerbit'},
                    {data: 'hal'},
                    {data: 'size'},
                    {data: 'cover'},
                    {data: 'deskripsi'},
                    {data: 'Actions', responsivePriority: -1},
                ],
                columnDefs: [
                    {
                        targets : [-1],
                        orderable: false
                    }
                ],
            });

            // set produk form
            $('body').on("click", '#btnAddProduk', function(){
                var dataEdit = $(this).data("value");

                saveMethod = 'edit';
                console.log(saveMethod);
                $.ajax({
                    headers : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url : HOST_me+"/stock/temp/produk/"+dataEdit,
                    method: "GET",
                    dataType : "JSON",
                    success : function (data) {
                        var stock = (data.stock) ? data.stock : '';
                        $('#formProduk').trigger('reset'); // reset form on modals
                        // insert value
                        $('[name="idProduk"]').val(data.produkId);
                        $('[name="namaProduk"]').val(data.nama_produk+'\n'+data.idLokal+'\n'+data.cover+'\n'+stock);
                        $('[name="kategoriHarga"]').val(data.kategoriHarga);
                        $('#modalDaftarProduk').modal('hide');
                    },
                    error : function (jqXHR, textStatus, errorThrown)
                    {
                        swal.fire({
                            html: jqXHR.responseJSON.message+"<br><br>"+jqXHR.responseJSON.file+"<br><br>Line: "+jqXHR.responseJSON.line,
                        });
                    }
                });
            });

            $('#tambahBtn').on('click', function (){

                $.ajax({
                    headers : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url : HOST_me+"/stock/rekonsiliasi/temp",
                    method : "POST",
                    data : $('#formProduk').serialize(),
                    dataType : "JSON",
                    success : function(data)
                    {
                        if(data.status){
                            $('#alertTable').addClass('alert-light-success show');
                            $("#alertText").append("Data Berhasil Diubah");
                            reloadDetil();
                            $('#formProduk').trigger('reset'); // reset form on modals
                        }
                    },
                    error : function(jqXHR, textStatus, errorThrown0)
                    {
                        console.log(jqXHR.responseJSON);
                        swal.fire({
                            html: jqXHR.responseJSON.message+"<br><br>"+jqXHR.responseJSON.file+"<br><br>Line: "+jqXHR.responseJSON.line,
                        });
                        $('.invalid-feedback').remove();
                        $('.is-invalid').remove('is-invalid');
                        for (const property in jqXHR.responseJSON.errors) {
                            // console.log(`${property}: ${jqXHR.responseJSON.errors[property]}`);
                            $('[name="'+`${property}`+'"').addClass('is-invalid').after('<div class="invalid-feedback" style="display: block;">'+`${jqXHR.responseJSON.errors[property]}`+'</div>');
                            $("#alertText").empty();
                            $("#alertText").append("<li>"+`${jqXHR.responseJSON.errors[property]}`+"</li>");
                        }
                    }
                });
            })

            $('#kt_datatable').DataTable({
                responsive: true,
                searchDelay: 500,
                processing: true,
                serverSide: true,
                order : [],
                ajax: {
                    headers : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url: HOST_me + '/stock/rekonsiliasi/temp/'+'{{ $tempId }}',
                    type: 'PATCH',
                    data: {
                        // parameters for custom backend script demo
                        columnsDef: [
                            'namaBarang',
                        ],
                    },
                },
                columns: [
                    {data: 'DT_RowIndex'},
                    {data: 'namaBarang', name: 'namaBarang'},
                    {data: 'jumlah'},
                    {data: 'Actions', responsivePriority: -1},
                ],
                columnDefs: [
                    {
                        targets : [-1],
                        orderable: false
                    }
                ],
            });

            var reloadDetil = function () {
                $('#kt_datatable').DataTable().ajax.reload();
            }

            $('body').on("click", "#btnEdit", function (){

                var dataEdit = $(this).data("value");

                $.ajax({
                    headers : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url : HOST_me+"/stock/rekonsiliasi/temp/"+dataEdit,
                    method: "GET",
                    dataType : "JSON",
                    success : function (data) {
                        $('#formProduk').trigger('reset'); // reset form on modals
                        // insert value
                        $('[name="idDetil"]').val(data.id);
                        $('[name="idProduk"]').val(data.produkId);
                        $('[name="namaProduk"]').val(data.nama_produk+'\n'+data.idLokal+'\n'+data.cover);
                        $('[name="kategoriHarga"]').val(data.kategoriHarga);
                        $('[name="jumlah"]').val(data.jumlah);
                    },
                    error : function (jqXHR, textStatus, errorThrown)
                    {
                        swal.fire({
                            html: jqXHR.responseJSON.message+"<br><br>"+jqXHR.responseJSON.file+"<br><br>Line: "+jqXHR.responseJSON.line,
                        });
                    }
                });
            });

            // button delete
            $('body').on("click", "#btnSoft", function (){

                var dataEdit = $(this).data("value");

                $.ajax({
                    headers : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url : HOST_me+"/stock/rekonsiliasi/temp/"+dataEdit,
                    method: "delete",
                    dataType : "JSON",
                    success : function (data) {
                        reloadDetil();
                    },
                    error : function (jqXHR, textStatus, errorThrown)
                    {
                        swal.fire({
                            html: jqXHR.responseJSON.message+"<br><br>"+jqXHR.responseJSON.file+"<br><br>Line: "+jqXHR.responseJSON.line,
                        });
                    }
                });
            });

            // save Global
            $('#submitGlobal').on('click', function (){
                $( "#master" ).submit();
            })

        </script>
        <!--end::Page Scripts-->
    </x-slot>

</x-metronics-layout>
