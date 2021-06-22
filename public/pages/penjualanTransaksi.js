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
        $('#tanggalNota, #tanggalTempo').datepicker({
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

// buttton Cari Produk
$('#cariProdukBtn').on("click",function(){

    $('#modalDaftarProduk').modal('show'); // show bootstrap modal
    $('#modalDaftarProduk').on('shown.bs.modal', function(){

        $('#tableProduk').DataTable().ajax.reload();

    });

});

jQuery(document).ready(function() {
    produkTable.init();
    customerTable.init();
    detilTable.init();
    KTBootstrapDatepicker.init();
    totalKabeh();
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
                url: HOST_me + '/penjualan/produk/data',
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
                {data: 'harga', render : $.fn.dataTable.render.number( '.', ',', 0, '')},
                {data: 'stock', render : $.fn.dataTable.render.number( '.', ',', 0, '')},
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
        url : HOST_me+"/penjualan/produk/data/"+dataEdit,
        method: "GET",
        dataType : "JSON",
        success : function (data) {
            var stock = (data.stock) ? data.stock : '';
            var harga = data.harga;
            var diskon = $('[name="diskonCustomer"]').val();
            $('#formProduk').trigger('reset'); // reset form on modals
            // insert value
            $('[name="idProduk"]').val(data.produkId);
            $('[name="namaProduk"]').val(data.nama_produk+'\n'+data.idLokal+'\n'+data.cover+'\n'+stock);
            $('[name="kategoriHarga"]').val(data.kategoriHarga);
            $('[name="harga"]').val(data.harga);
            $('[name="diskon"]').val(diskon)
            $('[name="hargaDiskon"]').val(harga - (harga * diskon / 100));
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

var customerTable = function(){

    var initTable = function(){
        var table = $('#tableCustomer');

        // begin first table
        table.DataTable({
            responsive: true,
            searchDelay: 500,
            processing: true,
            serverSide: true,
            order : [],
            ajax: {
                headers : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                url: HOST_me + '/penjualan/produk/customer',
                type: 'PATCH',
                data: {
                    // parameters for custom backend script demo
                    columnsDef: [
                        'id_cust', 'nama_cust',
                        'telp_cust'
                    ],
                },
            },
            columns: [
                {data: 'id_cust'},
                {data: 'nama_cust'},
                {data: 'diskon'},
                {data: 'telp_cust'},
                {data: 'addr_cust'},
                {data: 'keterangan'},
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

// buttton Cari Customer
$('#cariCustomerBtn').on("click",function(){

    $('#modalDaftarCustomer').modal('show'); // show bootstrap modal
    $('#tableCustomer').DataTable().ajax.reload();

});

// set Customer form
$('body').on("click", '#btnAddCustomer', function(){
    var dataEdit = $(this).data("value");

    saveMethod = 'edit';
    console.log(saveMethod);
    $.ajax({
        headers : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url : HOST_me+"/customer/"+dataEdit,
        method: "GET",
        dataType : "JSON",
        success : function (data) {
            var stock = (data.stock) ? data.stock : '';
            // insert value
            $('[name="id_customer"]').val(data.id_cust);
            $('[name="customer"]').val(data.nama_cust);
            $('[name="diskonCustomer"]').val(data.diskon);
            $('#modalDaftarCustomer').modal('hide');
        },
        error : function (jqXHR, textStatus, errorThrown)
        {
            swal.fire({
                html: jqXHR.responseJSON.message+"<br><br>"+jqXHR.responseJSON.file+"<br><br>Line: "+jqXHR.responseJSON.line,
            });
        }
    });
})

// perkalian subtotal
$('body').on('keyup', '#jumlah', function (){

    var hargaDiskon = $('[name="hargaDiskon"]').val();
    var jumlah = $('[name="jumlah"]').val();

    var hasil = hargaDiskon * jumlah;
    $('#subTotal').val(hasil);

});

// pergantian diskon
$('body').on('keyup', '#diskon', function (){

    var harga = $('[name="harga"]').val();
    var diskon = ($('[name="diskon"]').val()).replace(/,/g, '.');

    var hasil = parseInt(harga - (harga * parseFloat(diskon) / 100));
    $('#hargaDiskon').val(hasil);

    var hargaDiskon = $('[name="hargaDiskon"]').val();
    var jumlah = $('[name="jumlah"]').val();

    hasil = hargaDiskon * jumlah;
    $('#subTotal').val(hasil);

});

// dataTable rincian produk
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
                url: HOST_me + '/penjualan/detil/'+$('[name="temp"]').val(),
                type: 'PATCH',
                data: {
                    // parameters for custom backend script demo
                    columnsDef: [
                        'namaBarang', 'harga',
                    ],
                },
            },
            drawCallback : function (){
                totalKabeh();
            },
            columns: [
                // {data: 'DT_RowIndex'},
                {data: 'namaBarang', name: 'namaBarang'},
                // {data: 'kode_lokal'},
                {data: 'harga'},
                {data: 'jumlah'},
                {data: 'diskon', render : $.fn.dataTable.render.number( '.', ',', 2, '', ' %')}, // default diskon
                {data: 'sub_total'},
                {data: 'Actions', responsivePriority: -1},
            ],
            columnDefs: [
                {
                    targets : [-1],
                    orderable: false
                },
                {
                    targets: [1,4],
                    render : $.fn.dataTable.render.number( '.', ',', 0, 'Rp. '),
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
var saveMethod;
$('#tambahBtn').on('click', function (){

    $.ajax({
        headers : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url : HOST_me+"/penjualan/add/detil/",
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
            for (const property in jqXHR.responseJSON.errors) {
                // console.log(`${property}: ${jqXHR.responseJSON.errors[property]}`);
                $('.invalid-feedback').remove();
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
        url : HOST_me+"/penjualan/detil/"+dataEdit,
        method: "GET",
        dataType : "JSON",
        success : function (data) {
            var stock = (data.stock) ? data.stock : '';
            var harga = data.hargaBarang;
            var diskon = data.diskon;
            $('#formProduk').trigger('reset'); // reset form on modals
            // insert value
            $('[name="idDetil"]').val(data.id);
            $('[name="idProduk"]').val(data.produkId);
            $('[name="namaProduk"]').val(data.nama_produk+'\n'+data.idLokal+'\n'+data.cover+'\n'+stock);
            $('[name="kategoriHarga"]').val(data.kategoriHarga);
            $('[name="harga"]').val(data.hargaBarang);
            $('[name="diskon"]').val(data.diskon)
            $('[name="hargaDiskon"]').val(harga - (harga * diskon / 100));
            $('[name="jumlah"]').val(data.jumlah);
            $('[name="subTotal"]').val(data.sub_total);
        },
        error : function (jqXHR, textStatus, errorThrown)
        {
            swal.fire({
                html: jqXHR.responseJSON.message+"<br><br>"+jqXHR.responseJSON.file+"<br><br>Line: "+jqXHR.responseJSON.line,
            });
        }
    });
})

$('body').on("click", "#btnSoft", function (){

    var dataEdit = $(this).data("value");

    $.ajax({
        headers : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url : HOST_me+"/penjualan/detil/"+dataEdit,
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

const formatter = new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0
})

jQuery.fn.dataTable.Api.register( 'sum()', function ( ) {
    return this.flatten().reduce( function ( a, b ) {
        if ( typeof a === 'string' ) {
            a = a.replace(/[^\d.-]/g, '') * 1;
        }
        if ( typeof b === 'string' ) {
            b = b.replace(/[^\d.-]/g, '') * 1;
        }

        return a + b;
    }, 0 );
} );

function totalKabeh()
{
    // jumlah sub_total + ppn + biaya lain
    var total = $('#kt_datatable').DataTable().column(-2).data().sum();
    var ppn = ($('[name="ppn"]').val()) ? $('[name="ppn"]').val() : 0;
    var biayaLain = ($('[name="biayaLain"]').val()) ? $('[name="biayaLain"]').val() : 0;

    var hasil = (total + Number(biayaLain) + (total * Number(ppn) / 100))

    $('[name="totalSemuanya"]').val(formatter.format(hasil));
    $('[name="hiddenTotalSemuanya"]').val(hasil);
    // console.log(hasil)
}

// Total + PPN + Biaya Lain
$('[name="ppn"], [name="biayaLain"]').keyup(function(){
    totalKabeh();
});

$('#kt_datatable').on('change', function () {
    $totalKabeh();
})

$('#submitGlobal').on('click', function (){
    saveGlobal();
})

$('#submitUpdateGlobal').on('click', function (){
    updateGlobal();
})

function saveGlobal()
{
    $.ajax({
        headers : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url : HOST_me+"/penjualan",
        method : "POST",
        data : $('#master, #formFooter').serialize(),
        dataType : "JSON",
        success : function(data)
        {
            if(data.status){
                swal.fire({
                    html : data.insertMaster,
                });
                window.location.href = HOST_me+'/print/penjualan/'+data.nomorPenjualan;
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

function updateGlobal()
{
    $.ajax({
        headers : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url : HOST_me+"/penjualan",
        method : "PUT",
        data : $('#master, #formFooter').serialize(),
        dataType : "JSON",
        success : function(data)
        {
            if(data.status){
                swal.fire({
                    html : data.insertMaster,
                });
                window.location.href = HOST_me+'/print/penjualan/'+data.nomorPenjualan;
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

