<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetilPenjualanTemp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detil_penjualan_temp', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('idPenjualanTemp');
            $table->string('idBarang');
            $table->integer('jumlah')->comment('jumlah barang');
            $table->bigInteger('harga');
            $table->integer('diskon');
            $table->bigInteger('sub_total');
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
        Schema::dropIfExists('detil_penjualan_temp');
    }
}
