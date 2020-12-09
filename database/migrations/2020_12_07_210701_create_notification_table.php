<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notification', function (Blueprint $table) {
            $table->id();
            $table->integer('id_snd_user');
            $table->integer('id_rcv_user');
            $table->integer('id_type');
            $table->string('title');
            $table->string('body')->nullable();
            $table->integer('read_status')->comment('0: unread, 1: read');
            $table->datetime('deleted_at')->nullable();
            $table->integer('type')->comment('0: chat message, 1: ads order');
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
        Schema::dropIfExists('notification');
    }
}
