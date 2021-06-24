let tableProduk = document.getElementById('tableProduk')
$(tableProduk).DataTable({
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
