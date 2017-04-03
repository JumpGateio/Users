<?php

use Illuminate\Routing\Router;

Route::group(['middleware' => 'guest', 'namespace' => 'JumpGate\Users\Http\Controllers'], function (Router $router) {
    if (config('jumpgate.users.social_auth_only') === false) {
        $router->get('login')
               ->name('auth.login')
               ->uses('Authentication@index');
        $router->post('login')
               ->name('auth.login')
               ->uses('Authentication@handle');

        $router->get('blocked')
               ->name('auth.blocked')
               ->uses('Authentication@blocked');
    }

    if (config('jumpgate.users.enable_social') == true) {
        // Login
        $router->get('login/{provider?}')
               ->name('auth.social.login')
               ->uses('SocialAuthentication@login');

        $router->get('callback/{provider}')
               ->name('auth.social.callback')
               ->uses('SocialAuthentication@callback');
    }
});
