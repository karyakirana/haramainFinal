<!DOCTYPE html>
<html>
<head>
    <title>Hello</title>
    <style>

    </style>
</head>
<body>
<div class="container">
    <div class="col-md-6"></div>
    <div class="col-md-6">
        <h1>Nota Penjualan</h1>
        <table>
            <thead>
                <tr>
                    <th>Nomor</th>
                    <th>Item</th>
                    <th>Harga</th>
                    <th>Qty</th>
                    <th>Diskon</th>
                    <th>Sub Total</th>
                </tr>
            </thead>
            <tbody>
            @foreach($dataDetail as $row)
                <tr>
                    <td>{{}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
