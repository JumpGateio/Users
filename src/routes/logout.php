<?php

use Illuminate\Routing\Router;

Route::group(['middleware' => 'auth', 'namespace' => 'JumpGate\Users\Http\Controllers'], function (Router $router) {
    $router->get('logout')
           ->name('auth.logout')
           ->uses('Authentication@logout');
});
