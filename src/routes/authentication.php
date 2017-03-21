<?php

use Illuminate\Routing\Router;

Route::group(['middleware' => 'guest', 'namespace' => 'JumpGate\Users\Http\Controllers'], function (Router $router) {
    if (config('jumpgate.users.enable_social') === false) {
        // Login
        $router->get('login')
               ->name('auth.login')
               ->uses('Authentication@login');
        $router->post('login')
               ->name('auth.login')
               ->uses('Authentication@handleLogin');

        // Registering
        $router->get('register')
               ->name('auth.register')
               ->uses('Authentication@register');
        $router->post('register')
               ->name('auth.register')
               ->uses('Authentication@handleRegister');
    } else {
        // Login
        $router->get('login/{provider?}')
               ->name('auth.login')
               ->uses('SocialAuthentication@login');

        $router->get('callback/{provider}')
               ->name('auth.callback')
               ->uses('SocialAuthentication@callback');
    }
});
