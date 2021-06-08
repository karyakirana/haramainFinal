<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetilPenjualan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detil_penjualan', function (Blueprint $table) {
            $table->integerIncrements('id_detil');
            $table->string('id_jual');
            $table->string('id_produk');
            $table->bigInteger('jumlah');
            $table->bigInteger('harga');
            $table->integer('diskon')->nullable();
            $table->bigInteger('sub_total');
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
        Schema::dropIfExists('detilpenjualan');
    }
}
