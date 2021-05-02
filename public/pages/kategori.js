"use strict";

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
                url: HOST_me + '/produk/kategori',
                type: 'PATCH',
                data: {
                    // parameters for custom backend script demo
                    columnsDef: [
                        'id_kategori', 'id_lokal',
                        'nama', 'keterangan'
                    ],
                },
            },
            columns: [
                {data: 'id_kategori'},
                {data: 'id_lokal'},
                {data: 'nama'},
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
            initTable1();
        },

    };

}();

var saveMethod;

$('#btnNew').on("click",function(){
    saveMethod = 'add';
    $('#formModal').trigger('reset'); // reset form on modals
    $('#modalCrud').modal('show'); // show bootstrap modal
});

$('#btnSubmit').on("click", function(){

    var method = (saveMethod == 'add') ? "POST" : "PUT";

    $.ajax({
        headers : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url : HOST_me+"/produk/kategori",
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
        url : HOST_me+"/"+dataEdit+"/produk/kategori",
        method: "GET",
        dataType : "JSON",
        success : function (data) {
            $('#formModal').trigger('reset'); // reset form on modals
            // insert value
            $('[name="id"]').val(data.id_kategori);
            $('[name="idLokal"]').val(data.id_lokal);
            $('[name="nama"]').val(data.nama);
            $('[name="keterangan"]').val(data.keterangan);
            $('#modalCrud').modal('show'); // show bootstrap modal
            $('#formModal').on('shown.bs.modal', function(){
                select2jenis();
            });
            var dataSelect2 = {
                text: data.jenis,
                id: data.idJenis,
            }
            var newOption = new Option(dataSelect2.text, dataSelect2.id, false, false);
            $('#jenisSupplier').append(newOption).trigger('change');
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
            url : HOST_me+"/"+dataEdit+"/produk/kategori",
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
        url : HOST_me+"/"+dataEdit+"/produk/kategori",
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
        url : HOST_me+"/"+dataEdit+"/produk/kategori",
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
