<?php

use App\Http\Controllers\Akuntansi\Master\KategoriAkunController;

use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function(){

    Route::get('akuntansi/master/kategoriakun', [KategoriAkunController::class, 'index']);
    Route::patch('akuntansi/master/kategoriakun', [KategoriAkunController::class, 'daftarKategori'])->name('kategoriAkunList');

});
