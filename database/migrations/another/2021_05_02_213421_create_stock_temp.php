<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockTemp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_temp', function (Blueprint $table) {
            $table->id();
            $table->string('jenisTemp');
            $table->bigInteger('stockMasuk')->nullable();
            $table->bigInteger('idSupplier')->nullable();
            $table->bigInteger('idUser');
            $table->date('tglMasuk')->nullable();
            $table->string('nomorPo')->nullable();
            $table->text('keterangan')->nullable();
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
        Schema::dropIfExists('stock_temp');
    }
}
