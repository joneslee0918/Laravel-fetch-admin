<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoomTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('room', function (Blueprint $table) {
            $table->id();
            $table->integer('id_ads');
            $table->integer('id_user_sell');
            $table->integer('id_user_buy');
            $table->integer('status')->comment('0:closed, 1:actived');
            $table->integer('s_block_b')->default(0)->comment('seller blocked buyer, 0:unblock, 1:block');
            $table->integer('b_block_s')->default(0)->comment('buyer blocked seller, 0:unblock, 1:block');
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
        Schema::dropIfExists('room');
    }
}
