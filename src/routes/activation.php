<?php

use Illuminate\Routing\Router;

Route::group(['middleware' => 'guest', 'prefix' => 'activation', 'namespace' => 'JumpGate\Users\Http\Controllers'], function (Router $router) {
    if (config('jumpgate.users.enable_social') === false) {
        $router->get('sent')
               ->name('auth.activation.sent')
               ->uses('Activation@sent');

        $router->get('generate/{user_id}')
               ->name('auth.activation.generate')
               ->uses('Activation@generate');

        $router->get('re-send/{token}')
               ->name('auth.activation.re-send')
               ->uses('Activation@reSend');

        $router->get('failed/{token}')
               ->name('auth.activation.failed')
               ->uses('Activation@failed');

        $router->get('{token}')
               ->name('auth.activation.activate')
               ->uses('Activation@activate');
    }
});
