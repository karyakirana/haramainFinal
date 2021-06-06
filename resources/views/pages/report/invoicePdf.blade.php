<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Hello</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" >
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
    <style type="text/css">
        @page  {
            margin: 0cm 0cm;
        }

        body{
            font-size: 12px;
            /*margin: 15px;*/
            margin-top: 3cm;
            margin-left: 0.5cm;
            margin-right: 0.5cm;
            margin-bottom: 3cm;
        }
        th{
            align-content: center;
            text-align: center;
        }
        td{
            padding-left: 4px;
            padding-right: 4px;
        }
        .tengah {
            text-align: center;
        }
        .kanan {
            text-align: right;
        }

        .rincian > thead >th {
            border-top:dotted;
            border-bottom: dotted;
            border: 1px;
        }

        .rincian > tbody > tr > td{
            border-bottom: dotted;
            border: 1px;
            font-size: 12px;
        }
        .totalan {
            border-color: transparent;
            position: fixed;
            margin-left: 12cm;
            bottom: 0.5cm;
            right: 0.5cm;
            height: 4cm;
        }

        .keterangan{
            position: fixed;
            bottom: 0.5cm;
            height: 4cm;
            left: 0.5cm;
        }

        .rincian tfoot > tr > td {
            font-size: 12px;
            font-weight: bold;
        }

        .ttd{
            margin-top: 10px;
        }

        .namattd{
            margin-top: 40px;
        }

        header{
            position: fixed;
            top: 0.5cm;
            left: 0.5cm;
            right: 0.5cm;
            height: 0.5cm;
        }

        .garis{
            border-top: dotted;
            border: 1px;
            position: fixed;
            bottom: 0cm;
            left: 0.5cm;
            height: 4.5cm;
            width: 100%;
            margin-right: -0.5cm;
        }

        footer{
            position: fixed;
            bottom: 0cm;
            left: 0.5cm;
            right: 0.5cm;
            height: 4cm;
        }

    </style>

</head>
<body>
<header>
    <table width="100%" style="">
        <tr>
            <td width="30%" style="font-size: large; text-decoration: underline; font-weight: bold;">Nota Penjualan</td>
            <td width="30%" style="font-size: large">Colly :</td>
            <td width="40%">
                Surabaya,  {{$dataUtama->tgl_nota}} <br>
            </td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td>
                Kepada Yth, {{$dataUtama->namaCustomer}} <br>
                <span style="align-content: center">{{$dataUtama->addr_cust}}</span>
            </td>
        </tr>
    </table>
    <table width="100%">
        <tr>
            <td width="60%" style="font-size: 13px; font-weight: bolder">Nomor : {{ $dataUtama->penjualanId }}</td>
            <td>Jatuh Tempo : {{ $dataUtama->tgl_tempo }}</td>
        </tr>
    </table>
</header>

<div class="rincian">
    <table class="rincian" style="padding-top: 5px; table-layout: fixed; width: 100%; !important;">
        <thead>
        <tr>
            <th width="10%">Kode</th>
            <th width="40%">Produk</th>
            <th width="10%">Qty</th>
            <th width="10%">Harga</th>
            <th width="10%">Diskon</th>
            <th width="20">Sub Total</th>
        </tr>
        </thead>
        <tbody>
        <?php $jumlah =0; ?>
        @foreach($dataDetail as $row)
            <tr>
                <td class="tengah">{{$row->kode_lokal}}</td>
                <td>{{$row->nama_produk}}</td>
                <td class="tengah">{{$row->jumlah}}</td>
                <td class="kanan">{{number_format($row->harga, 0, ',', '.')}}</td>
                <td class="tengah">{{ substr((string)$row->diskon, 3, 2) == "00" ? substr((string)$row->diskon, 0, 2) : $row->diskon }}%</td>
                <td class="kanan">{{number_format($row->sub_total, 0, ',', '.')}}</td>
                <?php
                    $jumlah += $row->sub_total;
                ?>
            </tr>
        @endforeach
        </tbody>

    </table>
</div>

<footer>
    <div class="garis"></div>
    <div class="keterangan">
        Keterangan : {{ (isset($dataUtama->penket)) ? $dataUtama->penket : '-' }}
    </div>
    <div class="ttd">
        <table width="50%">
            <tr>
                <td></td>
                <td class="tengah">Disiapkan Oleh</td>
                <td></td>
                <td></td>
                <td class="tengah">Disiapkan Oleh</td>
                <td></td>
            </tr>
        </table>
    </div>
    <div class="namattd">
        <table width="50%">
            <tr>
                <td>(</td>
                <td class="tengah"></td>
                <td class="kanan">)</td>
                <td>(</td>
                <td class="tengah"></td>
                <td class="kanan">)</td>
            </tr>
        </table>
    </div>
    <div class="totalan">
        <table border="0px" width="100%">
            <tr>
                <td colspan="2">Jumlah :</td>
                <td class="kanan">Rp. {{number_format($jumlah, 0, ',', '.')}}</td>
            </tr>
            <tr>
                <td colspan="2">PPN :</td>
                <td class="kanan">Rp. {{ (isset($dataUtama->ppn)) ? $dataUtama->ppn : 0 }}</td>
            </tr>
            <tr>
                <td colspan="2">Biaya Lain :</td>
                <td class="kanan">Rp. {{ (isset($dataUtama->biaya_lain)) ? $dataUtama->biaya_lain : 0 }}</td>
            </tr>
            <tr>
                <td colspan="2" style="border: 1px !important; border-top: dashed !important; font-weight: bolder !important; font-size: 13px">Total :</td>
                <td class="kanan" style="border: 1px !important; border-top: dashed !important; font-weight: bolder !important; font-size: 13px">Rp. {{ (isset($dataUtama->total_bayar)) ? number_format($dataUtama->total_bayar, 0, ',', '.') : 0 }}</td>
            </tr>
        </table>
    </div>
    <div>
        <p style="font-style: italic; padding-top: 20px">Barang tidak dapat dikembalikan kecuali rusak / Perjanjian sebelumnya.</p>
    </div>
</footer>


</body>
</html>
