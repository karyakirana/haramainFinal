#Preorder

##Fungsi dan fitur
- Mencatat preorder barang yang akan dikirim kepada penerbit
- Memantau status preorder (sudah dikirim atau belum kepada penerbit, jika sudah maka tertulis sudah dikirim)

### Tabel dan yang berkaitan :
- Tabel preorder (menggunakan soft delete)
- Tabel preorder_detil
- Tabel preorder_temp
- Tabel preorder_detil_temp

### Alur Proses Umum
- Sales atau Gudang menginputkan barang yang akan dipesan.
- Sales atau Gudang memberikan bukti print kepada supplier atau pihak ke-3 dan menandai jika PO sudah dikirim.
- Barang pesanan datang dan ditandai dengan PO sudah selesai.
- Kasir akan membayar sesuai dengan PO yang datang.

### Alur Proses Lain
- Sales atau Gudang merevisi PO yang akan dicetak sebelum dikirim
- Sales atau Gudang logout atau meninggalkan saat proses input preorder
- Integrasi dengan sistem barang masuk
- Integrasi dengan kasir mengenai pembayaran

## Kodingan dan Sistem yang Berjalan (controller, model, dan view)

### PreorderController

### PreorderTempController

### Preorder.blade

### PreorderEdit.blade

