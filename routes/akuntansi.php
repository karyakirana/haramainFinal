<?php

use App\Http\Controllers\Akuntansi\Master\KategoriAkunController;

use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function(){

    Route::get('akuntansi/master/kategoriakun', [KategoriAkunController::class, 'index']);
    Route::patch('akuntansi/master/kategoriakun', [KategoriAkunController::class, 'daftarKategori'])->name('kategoriAkunList');
    Route::post('akuntansi/master/kategoriakun', [KategoriAkunController::class, 'store']);
    Route::get('akuntansi/master/kategoriakun/{id}', [KategoriAkunController::class, 'edit']);
    Route::delete('akuntansi/master/kategoriakun/{id}', [KategoriAkunController::class, 'destroy']);



    // Livewire Routing
//    Route::get('akuntansi/master/kategoriakun', App\Http\Livewire\Akuntansi\TableKategoriAkun::class);

});
