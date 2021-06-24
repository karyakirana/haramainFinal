<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockakhirMaster extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stockakhir_master', function (Blueprint $table) {
            $table->id();
            $table->string('kode');
            $table->bigInteger('branchId');
            $table->date('tglInput');
            $table->string('pencatat')->nullable();
            $table->bigInteger('idPembuat');
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
        Schema::dropIfExists('stockakhir_master');
    }
}
