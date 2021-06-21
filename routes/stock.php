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
