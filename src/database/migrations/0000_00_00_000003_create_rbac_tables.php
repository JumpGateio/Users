<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRbacTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rbac_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('label')->nullable();
            $table->timestamps();
        });

        Schema::create('rbac_permissions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('label')->nullable();
            $table->timestamps();
        });

        Schema::create('rbac_group_permission', function (Blueprint $table) {
            $table->integer('permission_id')->unsigned();
            $table->integer('group_id')->unsigned();

            $table->foreign('permission_id')
                  ->references('id')
                  ->on('rbac_permissions')
                  ->onDelete('cascade');

            $table->foreign('group_id')
                  ->references('id')
                  ->on('rbac_groups')
                  ->onDelete('cascade');

            $table->primary(['permission_Id', 'group_id']);
        });

        Schema::create('rbac_group_user', function (Blueprint $table) {
            $table->integer('group_id')->unsigned();
            $table->integer('user_id')->unsigned();

            $table->foreign('group_id')
                  ->references('id')
                  ->on('rbac_groups')
                  ->onDelete('cascade');

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

            $table->primary(['group_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('rbac_group_permission');
        Schema::drop('rbac_group_user');
        Schema::drop('rbac_groups');
        Schema::drop('rbac_permissions');
    }
}
