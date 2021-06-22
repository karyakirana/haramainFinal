<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInventoryRealTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_real', function (Blueprint $table) {
            $table->string('idProduk');
            $table->bigInteger('branchId');
            $table->bigInteger('stockIn');
            $table->bigInteger('stockOut');
            $table->bigInteger('stockNow');
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
        Schema::dropIfExists('inventory_real');
    }
}
