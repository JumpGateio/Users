<?php

use Illuminate\Routing\Router;

Route::group(['middleware' => 'auth', 'namespace' => 'JumpGate\Users\Http\Controllers'], function (Router $router) {
    if (config('jumpgate.users.enable_social') === false) {
        $router->get('logout')
               ->name('auth.logout')
               ->uses('Authentication@logout');
    } else {
        $router->get('logout')
               ->name('auth.logout')
               ->uses('SocialAuthentication@logout');
    }
});
