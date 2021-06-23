<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRekonsiliasiTemp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rekonsiliasi_temp', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->nullable();
            $table->date('tglBuat')->nullable();
            $table->bigInteger('branchIdAsal')->nullable();
            $table->bigInteger('branchIdAkhir')->nullable();
            $table->bigInteger('pembuat');
            $table->string('nomorPo')->nullable();
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
        Schema::dropIfExists('rekonsiliasi_temp');
    }
}
