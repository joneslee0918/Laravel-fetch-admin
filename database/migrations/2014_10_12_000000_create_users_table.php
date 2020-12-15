<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('avatar')->nullable();
            $table->string('name')->default('lucky fetch');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('phonenumber')->nullable();
            $table->string('device_token')->nullable();
            $table->string('iphone_device_token')->nullable();
            $table->string('customer_id')->nullable();
            $table->integer('terms')->default(0);
            $table->integer('active')->default(1)->comment('1:activated, 0:deactivated');
            $table->integer('role')->default(0)->comment('1:admin, 0:user');
            $table->integer('is_social')->default(0)->comment('1: google, 2:facebook, 3:apple');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
