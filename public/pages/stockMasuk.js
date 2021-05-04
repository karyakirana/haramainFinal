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
                url: HOST_me + '/stock/masuk',
                type: 'PATCH',
                data: {
                    // parameters for custom backend script demo
                    columnsDef: [
                        'stockmasukId', 'kode', 'tglNota', 'namaSupplier', 'name', 'tglMasuk', 'nomorPo',
                    ],
                },
            },
            columns: [
                {data: 'kode'},
                {data: 'namaSupplier'},
                {data: 'name'},
                {data: 'tglMasuk'},
                {data: 'nomorPo'},
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

// reload table
function reloadTable()
{
    $('#kt_datatable').DataTable().ajax.reload();
}

jQuery(document).ready(function() {
    KTDatatablesDataSourceAjaxServer.init();
});
