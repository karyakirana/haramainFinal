<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePenjualanTemp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('penjualan_temp', function (Blueprint $table) {
            $table->id();
            $table->string('jenisTemp')->comment('penjualan, retur bersih, atau retur rusak');
            $table->string('id_jual')->nullable();
            $table->string('idSales')->comment('id Users');
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
        Schema::dropIfExists('penjualan_temp');
    }
}
