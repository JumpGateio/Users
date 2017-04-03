<?php

use Illuminate\Routing\Router;

Route::group(['middleware' => 'guest', 'namespace' => 'JumpGate\Users\Http\Controllers'], function (Router $router) {
    if (config('jumpgate.users.social_auth_only') === false) {
        $router->get('sent')
               ->name('auth.password.sent')
               ->uses('ForgotPassword@sent');

        // Trigger reset.
        $router->get('reset')
               ->name('auth.password.reset')
               ->uses('ForgotPassword@reset');

        $router->post('reset')
               ->name('auth.password.reset')
               ->uses('ForgotPassword@sendEmail');

        // Finish resetting.
        $router->get('confirm/{token}')
               ->name('auth.password.confirm')
               ->uses('ForgotPassword@confirm');

        $router->post('confirm/{token}')
               ->name('auth.password.confirm')
               ->uses('ForgotPassword@handle');
    }
});
