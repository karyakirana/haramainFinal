<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

//Route::get('/dashboard', function () {
//    return view('dashboard');
//})->middleware(['auth'])->name('dashboard');

Route::get('/signin', 'Adib\AuthController@login')->name('login');
Route::post('/login', 'Adib\AuthController@authenticate');
Route::post('/logout', 'Adib\AuthController@logout')->name('logout');
Route::post('/register', 'Adib\AuthController@register');

Route::middleware(['auth'])->group(function(){

    // dashboard
    Route::get('/dashboard', 'DashboardController@index')->name('dashboard');

    Route::get('/customer/data', 'Master\CustomerController@index');
    Route::patch('/customer/data', 'Master\CustomerController@daftarCustomer'); // datatables
    Route::post('/customer', 'Master\CustomerController@store'); // save
    Route::put('/customer', 'Master\CustomerController@update'); // update
    Route::get('/customer/{id}', 'Master\CustomerController@edit'); // edit
    Route::post('/customer/{id}', 'Master\CustomerController@softDeletes'); // softdelete
    Route::put('/customer/{id}', 'Master\CustomerController@restore'); // restore
    Route::delete('/customer/{id}', 'Master\CustomerController@destroy'); // force delete
    Route::post('/master/customer/select2', 'Master\CustomerController@select2');

    Route::get('/supplier/jenis', 'Master\JenisSupplierController@index');
    Route::patch('/supplier/jenis', 'Master\JenisSupplierController@daftarJenis'); // datatables
    Route::post('/supplier/jenis', 'Master\JenisSupplierController@store'); // save
    Route::put('/supplier/jenis', 'Master\JenisSupplierController@update'); // update
    Route::get('/supplier/jenis/{id}', 'Master\JenisSupplierController@edit'); // edit
    Route::post('/supplier/jenis/{id}', 'Master\JenisSupplierController@softDeletes'); // softdelete
    Route::put('/supplier/jenis/{id}', 'Master\JenisSupplierController@restore'); // restore
    Route::delete('/supplier/jenis/{id}', 'Master\JenisSupplierController@destroy'); // force delete

    Route::get('/supplier/data', 'Master\SupplierController@index');
    Route::patch('/supplier/data', 'Master\SupplierController@daftarSupplier');
    Route::post('/supplier/data', 'Master\SupplierController@store');
    Route::put('/supplier/data', 'Master\SupplierController@update');
    Route::get('/{id}/supplier', 'Master\SupplierController@edit');
    Route::post('/{id}/supplier', 'Master\SupplierController@softDeletes');
    Route::put('/{id}/supplier', 'Master\SupplierController@restore');
    Route::delete('/{id}/supplier', 'Master\SupplierController@destroy');
    Route::post('/master/jenissupplier/select2', 'Master\JenisSupplierController@select2');

    Route::get('/produk/kategori', 'Master\KategoriController@index');
    Route::patch('/produk/kategori', 'Master\KategoriController@daftarKategori');
    Route::post('/produk/kategori', 'Master\KategoriController@store');
    Route::put('/produk/kategori', 'Master\KategoriController@update');
    Route::get('/{id}/produk/kategori', 'Master\KategoriController@edit');
    Route::post('/{id}/produk/kategori', 'Master\KategoriController@softDeletes');
    Route::put('/{id}/produk/kategori', 'Master\KategoriController@restore');
    Route::delete('/{id}/produk/kategori', 'Master\KategoriController@destroy');
    Route::post('/master/kategori/select2', 'Master\KategoriController@select2');

    Route::get('/produk/kategoriharga', 'Master\KategoriHargaController@index');
    Route::patch('/produk/kategoriharga', 'Master\KategoriHargaController@daftarkategori');
    Route::post('/produk/kategoriharga', 'Master\KategoriHargaController@store');
    Route::put('/produk/kategoriharga', 'Master\KategoriHargaController@update');
    Route::get('/{id}/produk/kategoriharga', 'Master\KategoriHargaController@edit');
    Route::post('/{id}/produk/kategoriharga', 'Master\KategoriHargaController@softdeletes');
    Route::put('/{id}/produk/kategoriharga', 'Master\KategoriHargaController@restore');
    Route::delete('/{id}/produk/kategoriharga', 'Master\KategoriHargaController@destroy');
    Route::post('/master/kategoriharga/select2', 'Master\KategoriHargaController@select2');

    Route::get('/produk/data', 'Master\ProdukController@index');
    Route::patch('/produk/data', 'Master\ProdukController@daftarProduk');
    Route::post('/produk/data', 'Master\ProdukController@store');
    Route::put('/produk/data', 'Master\ProdukController@update');
    Route::get('/{id}/produk', 'Master\ProdukController@edit');
    Route::post('/{id}/produk', 'Master\ProdukController@softdeletes');
    Route::put('/{id}/produk', 'Master\ProdukController@restore');
    Route::delete('/{id}/produk', 'Master\ProdukController@destroy');

    Route::get('/penjualan/data', 'Transaksi\PenjualanController@index');
    Route::patch('/penjualan/data', 'Transaksi\PenjualanController@daftarPenjualan');
    Route::get('/penjualan/baru', 'Transaksi\PenjualanController@create');
    Route::get('/penjualan/{id}/edit', 'Transaksi\PenjualanController@edit');
    Route::patch('/penjualan/produk/data', 'Transaksi\PenjualanTempController@daftarProduk');
    Route::get('/penjualan/produk/data/{id}', 'Transaksi\PenjualanTempController@setProduk');
    Route::patch('/penjualan/produk/customer', 'Transaksi\PenjualanTempController@daftarCustomer');
    Route::patch('/penjualan/detil/{id}', 'Transaksi\PenjualanTempController@daftarDetil');
    Route::post('/penjualan/add/detil/', 'Transaksi\PenjualanTempController@store');
    Route::get('/penjualan/detil/{id}', 'Transaksi\PenjualanTempController@edit'); // edit
    Route::delete('/penjualan/detil/{id}', 'Transaksi\PenjualanTempController@destroy'); // edit

    // simpan penjualan
    Route::post('/penjualan', 'Transaksi\PenjualanController@store');
    Route::put('/penjualan', 'Transaksi\PenjualanController@update');

    // print
    Route::get('/print/penjualan/{id}', 'Transaksi\PenjualanController@print');
    Route::get('/print/penjualan/{id}/pdf', 'Transaksi\PenjualanController@printPdf');

    Route::get('/stock/masuk', 'Stock\StockMasukController@index');
    Route::post('/stock/masuk', 'Stock\StockMasukController@store');
    Route::put('/stock/masuk', 'Stock\StockMasukController@update');
    Route::patch('/stock/masuk', 'Stock\StockMasukController@daftarStockMasuk');

    // Stock Masuk Baru
    Route::get('/stock/masuk/baru', 'Stock\StockMasukController@create');
    Route::get('/stock/masuk/edit/{id}', 'Stock\StockMasukController@edit');

    Route::patch('/stock/temp/produk', 'Stock\StockTempController@daftarProduk');
    Route::patch('/stock/temp/supplier', 'Stock\StockTempController@daftarSupplier');
    Route::get('/stock/temp/produk/{id}', 'Stock\StockTempController@setProduk');
    Route::get('/stock/detil/{id}', 'Stock\StockTempController@edit');

    Route::patch('/stock/detil/{id}', 'Stock\StockTempController@daftarDetil');
    Route::post('/stock/temp/simpan', 'Stock\StockTempController@store');
    Route::delete('/stock/temp/produk/{id}', 'Stock\StockTempController@destroy');

    // pembayaran
    Route::get('/kasir/pembayaran/data', 'Kasir\PembayaranController@index');
    Route::patch('/kasir/pembayaran/data', 'Kasir\PembayaranController@daftarPembayaran');
    Route::post('/kasir/pembayaran/data', 'Kasir\PembayaranController@store')->name('submitPembayaran');

    Route::get('/kasir/pembayaran/baru', 'Kasir\PembayaranController@create');
    Route::patch('/kasir/pembayaran/penjualan', 'Kasir\PembayaranController@daftarPenjualan');
    Route::get('/kasir/pembayaran/edit/{id}', 'Kasir\PembayaranController@edit');
    Route::delete('/kasir/pembayaran/delete/{id}', 'Kasir\PembayaranController@destroy');

    // Preoder
    Route::get('predorder/data', 'Preoder\PreorderController@index'); // index menampilkan data preorder
    Route::get('preoder/baru', 'Preorder\PreorderController@create');

    /*
     * Route Akuntansi
     */

    // Daftar akuntansi


});

//require __DIR__.'/auth.php';
