<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockmasuk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stockmasuk', function (Blueprint $table) {
            $table->id();
            $table->string('activeCash');
            $table->string('kode')->unique();
            $table->bigInteger('idSupplier');
            $table->bigInteger('idUser');
            $table->date('tglMasuk');
            $table->string('nomorPo')->nullable();
            $table->text('keterangan')->nullable();
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
        Schema::dropIfExists('stockmasuk');
    }
}
