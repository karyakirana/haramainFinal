<?php

use Illuminate\Support\Facades\Route;

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

// branch Stock
Route::get('/stock/branch', [App\Http\Controllers\Stock\BranchController::class, 'index'])
    ->name('branchIndex');
Route::patch('/stock/branch', [App\Http\Controllers\Stock\BranchController::class, 'branchList'])
    ->name('branchList');
Route::post('/stock/branch', [App\Http\Controllers\Stock\BranchController::class, 'store'])
    ->name('branchStore');
Route::get('/stock/branch/edit/{id}', [App\Http\Controllers\Stock\BranchController::class, 'edit'])
    ->name('branchEdit');
Route::delete('/stock/branch/edit/{id}', [App\Http\Controllers\Stock\BranchController::class, 'destroy'])
    ->name('branchDelete');

// Stock Akhir
Route::get('/stock/stockAkhir', [\App\Http\Controllers\Stock\StockAkhirController::class, 'index'])
    ->name('stockAkhirIndex');
Route::patch('/stock/stockAkhir', [\App\Http\Controllers\Stock\StockAkhirController::class, 'stockAkhirList'])
    ->name('stockAkhirList');
Route::put('/stock/stockAkhir', [\App\Http\Controllers\Stock\StockAkhirController::class, 'tableProduk'])
    ->name('stockAkhirListProduk');
Route::post('/stock/stockAkhir', [\App\Http\Controllers\Stock\StockAkhirController::class, 'store'])
    ->name('stockAkhirStore');
Route::get('/stock/stockAkhir/{id}', [\App\Http\Controllers\Stock\StockAkhirController::class, 'edit']);
Route::delete('/stock/stockAkhir/{id}', [\App\Http\Controllers\Stock\StockAkhirController::class, 'destroy']);

// Stock Semua
//Route::get('/stock/semua');
