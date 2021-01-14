<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBoostTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('boost', function (Blueprint $table) {
            $table->id();
            $table->integer('id_ads');
            $table->integer('type')->comment("0: daily, 1: weekly, 2:monthly, 3:yearly");
            $table->datetime('started_at');
            $table->datetime('expired_at');
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
        Schema::dropIfExists('boost');
    }
}