<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePenjualan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('penjualan', function (Blueprint $table) {
            $table->string('id_jual')->primary();
            $table->string('activeCash');
            $table->string('id_cust');
            $table->string('id_user');
            $table->dateTime('tgl_nota');
            $table->dateTime('tgl_tempo')->nullable();
            $table->string('status_bayar', 50);
            $table->string('sudahBayar')->nullable();
            $table->integer('total_jumlah');
            $table->integer('ppn')->nullable();
            $table->integer('total_bayar');
            $table->mediumText('keterangan');
            $table->integer('print')->nullable();
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
        Schema::dropIfExists('penjualan');
    }
}
