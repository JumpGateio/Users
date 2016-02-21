<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTables extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username')->unique();
            $table->string('password', 64);
            $table->string('email')->index();
            $table->string('first_name')->index()->nullable();
            $table->string('last_name')->index()->nullable();
            $table->string('display_name')->index()->nullable();
            $table->string('timezone')->default('GMT');
            $table->string('location')->nullable();
            $table->string('url')->nullable();
            $table->string('remember_token', 100)->nullable();
            $table->string('social_id')->index()->nullable();
            $table->string('social_avatar')->index()->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}
