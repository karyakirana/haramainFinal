<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TableRekonsiliasiBranch extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rekonsiliasi_branch', function (Blueprint $table) {
            $table->id();
            $table->string('kode');
            $table->date('tglBuat');
            $table->bigInteger('branchIdAsal');
            $table->bigInteger('branchIdAkhir');
            $table->bigInteger('pembuat');
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
        Schema::dropIfExists('rekonsiliasi_branch');
    }
}
