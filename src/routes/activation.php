<?php

use Illuminate\Routing\Router;

Route::group(['middleware' => 'guest', 'prefix' => 'activation', 'namespace' => 'JumpGate\Users\Http\Controllers'], function (Router $router) {
    if (config('jumpgate.users.social_auth_only') === false) {
        $router->get('sent')
               ->name('auth.activation.sent')
               ->uses('Activation@sent');

        $router->get('generate/{user_id}')
               ->name('auth.activation.generate')
               ->uses('Activation@generate');

        $router->get('inactive')
               ->name('auth.activation.inactive')
               ->uses('Activation@inactive');

        $router->get('re-send/{token}')
               ->name('auth.activation.resend')
               ->uses('Activation@resend');

        $router->get('failed/{token}')
               ->name('auth.activation.failed')
               ->uses('Activation@failed');

        $router->get('{token}')
               ->name('auth.activation.activate')
               ->uses('Activation@activate');
    }
});
