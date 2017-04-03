<?php

use Illuminate\Routing\Router;

Route::group(['middleware' => 'guest', 'namespace' => 'JumpGate\Users\Http\Controllers'], function (Router $router) {
    if (config('jumpgate.users.social_auth_only') === false) {
        $router->get('register')
               ->name('auth.register')
               ->uses('Registration@index');
        $router->post('register')
               ->name('auth.register')
               ->uses('Registration@handle');
    }
});
