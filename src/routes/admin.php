<?php

Route::group(['prefix' => 'admin', 'middleware' => ['web', 'auth']], function()
{
    CRUD::resource('user', 'JumpGate\Users\Http\Controllers\Admin\User');
    CRUD::resource('role', 'JumpGate\Users\Http\Controllers\Admin\Role');
    CRUD::resource('permission', 'JumpGate\Users\Http\Controllers\Admin\Permission');
    CRUD::resource('token', 'JumpGate\Users\Http\Controllers\Admin\Token');
});
