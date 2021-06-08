<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSupplierTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supplier', function (Blueprint $table) {
            $table->id();
            $table->string('kodeSupplier')->unique();
            $table->bigInteger('jenisSupplier')->nullable();
            $table->string('namaSupplier');
            $table->text('alamatSupplier')->nullable();
            $table->string('tlpSupplier')->nullable();
            $table->string('npwpSupplier')->nullable();
            $table->string('emailSupplier')->nullable();
            $table->text('keteranganSupplier')->nullable();
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
        Schema::dropIfExists('supplier');
    }
}
