<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order', function (Blueprint $table) {
            $table->id();
            $table->integer('id_ads');
            $table->integer('id_order_user');
            $table->string('name');
            $table->string('email');
            $table->string('phonenumber');
            $table->string('description', 1024)->nullable();
            $table->integer('status')->comment('0:request, 1:confirm, 2:complete , 3:cancel, 4:decline');
            $table->string('etc', 1024)->nullable();
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
        Schema::dropIfExists('order');
    }
}
