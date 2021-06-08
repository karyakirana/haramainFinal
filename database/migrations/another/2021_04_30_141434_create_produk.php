<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProduk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produk', function (Blueprint $table) {
            $table->string('id_produk')->primary();
            $table->string('id_kategori');
            $table->string('kode_lokal')->nullable();
            $table->string('penerbit')->nullable();
            $table->string('nama_produk');
            $table->integer('stock')->nullable();
            $table->integer('hal');
            $table->string('cover')->nullable();
            $table->string('id_kat_harga');
            $table->integer('harga');
            $table->string('size')->nullable();
            $table->mediumText('deskripsi')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('produk');
    }
}
