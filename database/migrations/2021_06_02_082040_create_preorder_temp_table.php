<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePreorderTempTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('preorder_temp', function (Blueprint $table) {
            $table->id();
            $table->string('kode_preorder')->unique();
            $table->string('kodeSupplier');
            $table->bigInteger('id_pembuat');
            $table->integer('jumlahItem')->nullable();
            $table->string('status')->nullable(); // dibuat, dikirim, diterima
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
        Schema::dropIfExists('preorder_temp');
    }
}
