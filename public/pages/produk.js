"use strict";

// format rupiah
const formatter = new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0
})

function select2kategori()
{
    $('#idKategori').select2({
        placeholder: "Select a state",
        ajax : {
            headers : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            placeholder: 'Select a state',
            allowClear: true,
            url : HOST_me + "/master/kategori/select2",
            type : 'POST',
            dataType: 'json',
            delay: 100,
            width: "resolve",
            data : function (params){
                return{
                    q: params.term, // search term
                };
            },
            processResults : function(data){
                return{
                    results: $.map(data, function (item){
                        return {
                            text: item.nama+' | '+item.id_lokal,
                            id: item.id_kategori,
                        }
                    }),
                };
            },
            cache : true,
        },
    });
}
function select2kategoriHarga()
{
    $('#kategoriHarga').select2({
        placeholder: "Select a state",
        ajax : {
            headers : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            placeholder: 'Select a state',
            allowClear: true,
            url : HOST_me + "/master/kategoriharga/select2",
            type : 'POST',
            dataType: 'json',
            delay: 100,
            width: "resolve",
            data : function (params){
                return{
                    q: params.term, // search term
                };
            },
            processResults : function(data){
                return{
                    results: $.map(data, function (item){
                        return {
                            text: item.nama_kat,
                            id: item.id_kat_harga,
                        }
                    }),
                };
            },
            cache : true,
        },
    });
}

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
                url: HOST_me + '/produk/data',
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
                {data: 'harga'},
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
            initTable1();
        },

    };

}();

// class CRUD
var adibCrud = function(){

    var modalButton;
    var submitButton;
    var cancelButton;
    var closeButton;

    modalButton.addEventListener("click", function () {
        //
    })
};

var saveMethod;

$('#btnNew').on("click",function(){
    saveMethod = 'add';
    $('#formModal').trigger('reset'); // reset form on modals
    $('#modalCrud').modal('show'); // show bootstrap modal
    $('#modalCrud').on('shown.bs.modal', function(){
        select2kategori();
        select2kategoriHarga();
        $('#idKategori').val(null).trigger('change');
        $('#kategoriHarga').val(null).trigger('change');

    });
});

$('#btnSubmit').on("click", function(){

    var method = (saveMethod == 'add') ? "POST" : "PUT";

    $.ajax({
        headers : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url : HOST_me+"/produk/data",
        method : method,
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
});

$('body').on("click", '#btnEdit', function(){
    var dataEdit = $(this).data("value");

    saveMethod = 'edit';
    console.log(saveMethod);
    $.ajax({
        headers : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url : HOST_me+"/"+dataEdit+"/produk/",
        method: "GET",
        dataType : "JSON",
        success : function (data) {
            $('#formModal').trigger('reset'); // reset form on modals
            // insert value
            $('[name="id"]').val(data.id);
            $('[name="nama"]').val(data.namaSupplier);
            $('[name="alamatSupplier"]').val(data.alamatSupplier);
            $('[name="telepon"]').val(data.tlpSupplier);
            $('[name="npwp"]').val(data.npwpSupplier);
            $('[name="email"]').val(data.emailSupplier);
            $('[name="keterangan"]').val(data.keteranganSupplier);
            $('#modalCrud').modal('show'); // show bootstrap modal
            $('#formModal').on('shown.bs.modal', function(){
                select2kategori();
                select2kategoriHarga();
            });
            var dataSelect2_1 = {
                text: data.nama_kategori+' | '+data.id_lokal,
                id: data.id_kategori,
            }
            var newOption = new Option(dataSelect2_1.text, dataSelect2_1.id, false, false);
            $('#idKategori').append(newOption).trigger('change');
            var dataSelect2 = {
                text: data.nama_kat,
                id: data.id_kat_harga,
            }
            var newOption_2 = new Option(dataSelect2.text, dataSelect2.id, false, false);
            $('#kategoriHarga').append(newOption_2).trigger('change');
        },
        error : function (jqXHR, textStatus, errorThrown)
        {
            swal.fire({
                html: jqXHR.responseJSON.message+"<br><br>"+jqXHR.responseJSON.file+"<br><br>Line: "+jqXHR.responseJSON.line,
            });
        }
    });
})

$('body').on("click", "#btnSoft", function(){
    if (confirm('Serius untuk hapus data?')) {
        var dataDelete = $(this).data("value");
        // ajax delete data to database
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url : HOST_me+"/"+dataEdit+"/produk/",
            type: "POST",
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
});

$('body').on("click", "#btnRestore", function(){
    var dataRestore = $(this).data("value");
    // ajax delete data to database
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url : HOST_me+"/"+dataEdit+"/produk/",
        type: "PUT",
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
});

$('body').on("click", "#btnForce", function(){
    var dataForce = $(this).data("value");
    // ajax delete data to database
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url : HOST_me+"/"+dataEdit+"/produk/",
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
});

// reload table
function reloadTable()
{
    $('#kt_datatable').DataTable().ajax.reload();
}

jQuery(document).ready(function() {
    KTDatatablesDataSourceAjaxServer.init();
});
