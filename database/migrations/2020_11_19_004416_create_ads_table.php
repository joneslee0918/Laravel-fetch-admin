<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ads', function (Blueprint $table) {
            $table->id();
            $table->integer('id_user');
            $table->integer('id_category');
            $table->integer('id_breed');
            $table->integer('age');
            $table->integer('gender')->comment('0:male, 1:female');
            $table->integer('price');
            $table->string('lat');
            $table->string('long');
            $table->string('description')->nullable();
            $table->integer('status')->default(1)->comment('0:closed, 1:active');
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
        Schema::dropIfExists('ads');
    }
}
