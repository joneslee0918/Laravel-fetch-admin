<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBreedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('breed', function (Blueprint $table) {
            $table->id();
            $table->integer('order')->default(0);
            $table->string('name', 30);
            $table->integer('active')->default(1)->comment('0:hide, 1:show');
            $table->string('etc', 30)->nullable();
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
        Schema::dropIfExists('breed');
    }
}
