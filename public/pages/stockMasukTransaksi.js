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
        $('#tanggalMasuk').datepicker({
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

jQuery(document).ready(function() {
    produkTable.init();
    KTBootstrapDatepicker.init();
    supplierTable.init();
    detilTable.init();
});

// buttton Cari Produk
$('#cariProdukBtn').on("click",function(){

    $('#modalDaftarProduk').modal('show'); // show bootstrap modal
    $('#modalDaftarProduk').on('shown.bs.modal', function(){

        $('#tableProduk').DataTable().ajax.reload();

    });

});

// table produk
var produkTable = function(){

    var initTable = function(){
        var table = $('#tableProduk');

        // begin first table
        table.DataTable({
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

    };

    return {

        //main function to initiate the module
        init: function() {
            initTable();
        },

    };
}();

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
})

// buttton Cari Supplier
$('#cariSupplierBtn').on("click",function(){

    $('#modalDaftarSupplier').modal('show'); // show bootstrap modal
    $('#tableSupplier').DataTable().ajax.reload();

});

var supplierTable = function(){

    var initTable = function(){
        var table = $('#tableSupplier');

        // begin first table
        table.DataTable({
            responsive: true,
            searchDelay: 500,
            processing: true,
            serverSide: true,
            order : [],
            ajax: {
                headers : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                url: HOST_me + '/stock/temp/supplier',
                type: 'PATCH',
                data: {
                    // parameters for custom backend script demo
                    columnsDef: [
                        'kode', 'jenisSupplier',
                        'namaSupplier', 'tlpSupplier'
                    ],
                },
            },
            columns: [
                {data: 'DT_RowIndex'},
                {data: 'namaSupplier'},
                {data: 'jenisSupplier'},
                {data: 'tlpSupplier'},
                {data: 'alamatSupplier'},
                {data: 'keteranganSupplier'},
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
            initTable();
        },

    };
}();

// set Supplier Form
$('body').on("click", '#btnAddSupplier', function(){
    var dataEdit = $(this).data("value");

    saveMethod = 'edit';
    console.log(saveMethod);
    $.ajax({
        headers : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url : HOST_me+"/"+dataEdit+"/supplier/",
        method: "GET",
        dataType : "JSON",
        success : function (data) {
            // insert value
            $('[name="id_supplier"]').val(data.idSupplier);
            $('[name="supplier"]').val(data.namaSupplier);
            $('#modalDaftarSupplier').modal('hide');
        },
        error : function (jqXHR, textStatus, errorThrown)
        {
            swal.fire({
                html: jqXHR.responseJSON.message+"<br><br>"+jqXHR.responseJSON.file+"<br><br>Line: "+jqXHR.responseJSON.line,
            });
        }
    });
})

// datatable rincian produk
var detilTable = function(){

    var initTable = function(){
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
                url: HOST_me + '/stock/detil/'+$('[name="temp"]').val(),
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
                // {data: 'kode_lokal'},
                // {data: 'harga'},
                {data: 'jumlah'},
                // {data: 'diskon'},
                // {data: 'sub_total'},
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
            initTable();
        },

    };
}();

var reloadDetil = function () {
    $('#kt_datatable').DataTable().ajax.reload();
}

$('#tambahBtn').on('click', function (){

    $.ajax({
        headers : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url : HOST_me+"/stock/temp/simpan",
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

$('body').on("click", "#btnEdit", function (){

    var dataEdit = $(this).data("value");

    $.ajax({
        headers : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url : HOST_me+"/stock/detil/"+dataEdit,
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
})

// button delete
$('body').on("click", "#btnSoft", function (){

    var dataEdit = $(this).data("value");

    $.ajax({
        headers : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url : HOST_me+"/stock/temp/produk/"+dataEdit,
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
})

// save Global
$('#submitGlobal').on('click', function (){
    saveGlobal();
})

function saveGlobal()
{
    $.ajax({
        headers : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url : HOST_me+"/stock/masuk",
        method : "POST",
        data : $('#master, #formFooter').serialize(),
        dataType : "JSON",
        success : function(data)
        {
            if(data.status){
                swal.fire({
                    html : data.insertMaster,
                });
                window.location.href = HOST_me+'/stock/masuk';
            } else {
                swal.fire({
                    html : data.keterangan
                })
            }
        },
        error : function(jqXHR, textStatus, errorThrown0)
        {
            // console.log(jqXHR.responseJSON);
            swal.fire({
                html: jqXHR.responseJSON.message+"<br><br>"+jqXHR.responseJSON.file+"<br><br>Line: "+jqXHR.responseJSON.line,
            });
            for (const property in jqXHR.responseJSON.errors) {
                console.log(`${property}: ${jqXHR.responseJSON.errors[property]}`);
                $("#alertText").append("<li>"+`${jqXHR.responseJSON.errors[property]}`+"</li>");
            }
        }
    });
}

// update Global
$('#submitUpdateGlobal').on('click', function (){
    updateGlobal();
})

function updateGlobal()
{
    $.ajax({
        headers : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url : HOST_me+"/stock/masuk",
        method : "PUT",
        data : $('#master, #formFooter').serialize(),
        dataType : "JSON",
        success : function(data)
        {
            if(data.status){
                swal.fire({
                    html : data.insertMaster,
                });
                window.location.href = HOST_me+'/stock/masuk';
            } else {
                swal.fire({
                    html : data.keterangan
                })
            }
        },
        error : function(jqXHR, textStatus, errorThrown0)
        {
            // console.log(jqXHR.responseJSON);
            swal.fire({
                html: jqXHR.responseJSON.message+"<br><br>"+jqXHR.responseJSON.file+"<br><br>Line: "+jqXHR.responseJSON.line,
            });
            for (const property in jqXHR.responseJSON.errors) {
                console.log(`${property}: ${jqXHR.responseJSON.errors[property]}`);
                $("#alertText").append("<li>"+`${jqXHR.responseJSON.errors[property]}`+"</li>");
            }
        }
    });
}
