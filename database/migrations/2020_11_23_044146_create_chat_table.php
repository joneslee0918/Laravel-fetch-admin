<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat', function (Blueprint $table) {
            $table->id();
            $table->integer('id_ads');
            $table->integer('id_user_snd');
            $table->integer('id_user_rcv');
            $table->text('message')->nullable();
            $table->string('attach_file')->nullable();
            $table->integer('message_type')->default(0)->commect('0:message, 1:attach_image');
            $table->integer('read_status')->default(0)->commect('0:unread, 1:read');
            $table->integer('last_seen_time');
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
        Schema::dropIfExists('chat');
    }
}
