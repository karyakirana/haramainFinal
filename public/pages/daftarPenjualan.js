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
                url: HOST_me + '/penjualan/data',
                type: 'PATCH',
                data: {
                    // parameters for custom backend script demo
                    columnsDef: [
                        'penjualanId', 'namaCustomer', 'tglNota', 'tglTempo', 'status_bayar', 'sudahBayar', 'total_jumlah',
                        'ppn', 'biaya_lain', 'total_bayar', 'penket', 'namaSales',
                    ],
                },
            },
            columns: [
                {data: 'penjualanId'}, //id transaksi
                {data: 'namaCustomer'}, // nama pelanggan
                {data: 'tglNota'}, // tanggal nota
                {data: 'tglTempo'}, // jatuh tempo
                {data: 'status_bayar'}, // status bayar
                {data: 'sudahBayar'}, // sudah bayar
                {data: 'total_jumlah'}, // total barang
                {data: 'ppn'}, // ppn
                {data: 'biaya_lain'}, // biaya lain
                {data: 'total_bayar'}, // total tagihan
                {data: 'namaSales', name: 'namaSales'}, // Nama Sales
                {data: 'print'}, // print
                {data: 'update'}, // jumlah print
                {data: 'penket'}, // keterangan
                {data: 'tombol', responsivePriority: -1},
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
