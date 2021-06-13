// reload table
function reloadTable()
{
    useTable.DataTable().ajax.reload();
}

jQuery(document).ready(function() {
    KTDatatablesDataSourceAjaxServer.init();
});

var KTDatatablesDataSourceAjaxServer = function() {

    var initTable1 = function() {
        var table = useTable;

        // begin first table
        table.DataTable({
            responsive: true,
            searchDelay: 500,
            processing: true,
            serverSide: true,
            order : [],
            ajax: {
                headers : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                url: urlTable,
                type: 'PATCH',
                data: {
                    // parameters for custom backend script demo
                    columnsDef: paramTable,
                },
            },
            columns: columnsTable,
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
