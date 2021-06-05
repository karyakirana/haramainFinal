# Aplikasi Haramain

Aplikasi ini berisi tentang ERP proses bisnis UD. Haramain yang dikerjakan dengan target setahun masa percobaan dan penambahan fitur pada tahun berikutnya.

## Fitur atau Sub App Aplikasi Haramain

### Sistem Penjualan
- Mencatat transaksi penjualan yang terbagi atas cash dan tempo. Cash aadalah bayar langsung sedangkan tempo adalah pembayaran mundur.
- Mencetak Invoice dengan printer Dot-Matrix Epson LQ310
- Diskon barang dengan diskon 2 angka di belakang koma (12.50%)
- Reporting Sistem Penjualan berdasarkan tanggal dan kategori tertentu sesuai dengan kebutuhan
- Terintegrasi dengan sistem yang lain seperti :
  - Sistem penjualan terintegrasi dengan stock. Pada saat nota dicetak, maka otomatis akan mengurangi stock yang ada. Hal ini dikarenakan Nota Penjualan atau invoice juga merupakan surat jalan. Surat jalan adalah bukti barang tersebut keluar.
  - Sistem penjualan terintegrasi dengan sistem pembayaran. Jika pembayaran adalah cash atau tunai, maka yang melakukan pencatatan adalah kasir tunai. Sedangkan untuk pembayaran melalui transfer bank, maka pembayaran tersebut akan ditangani oleh keuangan bank.  

#### Catatan Perubahan Sistem Penjualan :
- Diskon yang semula integer menjadi desimal

#### Catatan yang belum selesai pada Sistem Penjualan :
- Retur Penjualan Baik
- Retur Penjualan Rusak
- Reporting dari transaksi penjualan yang dicetak menggunakan media kertas a4 dengan printer inkjet

### Stock
Sistem yang bertugas untuk melakukan pencatatan barang keluar masuk serta memilah barang yang baik maupun rusak.
Sistem ini memiliki sub sistem sebagai berikut :
- Sistem Stock awal barang (Stock akhir di periode sebelumnya)
- Sistem Stock keluar (barang siap jual sekaligus dengan nota)
- Sistem Stock masuk (barang masuk)

### Kepegawaian (sederhana sekali)

### Kasir
Sistem pencatatan keluar-masuk arus cash dari UD. Haramain.
Sistem ini memiliki sub sistem sebagai berikut :
- Sub Sistem Pemasukan Uang
- Sub Sistem Pengeluaran Uang
- Sub Sistem Penomoran Akuntansi terkait dengan transaksi keuangan (sehingga terintegrasi dengan sistem akuntansi)

### Sistem Permintaan Barang kepada pihak ke-3 (Supplier)
- Sistem input Supplier (Sudah ada)
- Sistem input pre order dan print menggunakan dot matrix
- Sistem pengiriman PO

### Akuntansi
Akuntansi merupakan sistem informasi yang menyajikan dalam bentuk laporan keuangan meliputi :
- Laporan Arus Kas (Cash Flow)
- Laporan Laba Rugi
- Laporan Neraca

Pada Aplikasi Akuntansi ini juga memiliki sub-sistem yang terbagi atas :
- Sub-sistem penomoran akuntansi
- Sub-sistem Jurnal-jurnal (disesuaikan dengan kebutuhan dari UD.Haramain)
- Sub-sistem pembantu Buku Besar
- Sub-sistem pelaporan-pelaporan sesuai kebutuhan (reporting)

### Perhitungan Zakat
