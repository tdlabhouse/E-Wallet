<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTTransferTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_transfer', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('id_pengirim')->constrained('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('id_penerima')->constrained('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('id_transaksi')->constrained('transaksi')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('t_transfer');
    }
}
