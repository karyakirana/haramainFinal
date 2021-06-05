<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePreorderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('preorder', function (Blueprint $table) {
            $table->id();
            $table->string('kode_preorder')->unique();
            $table->date('tglPembuatan');
            $table->date('tglSelesai')->nullable();
            $table->string('kodeSupplier');
            $table->bigInteger('id_pembuat');
            $table->integer('jumlahItem')->nullable();
            $table->string('status')->nullable(); // dibuat, dikirim, diterima
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
        Schema::dropIfExists('preorder');
    }
}
