<?php

use Illuminate\Routing\Router;

Route::group(['middleware' => 'auth', 'prefix' => 'invitation', 'namespace' => 'JumpGate\Users\Http\Controllers'], function (Router $router) {
    if (config('jumpgate.users.allow_invitations') === true) {
        $router->get('sent')
               ->name('auth.invitation.sent')
               ->uses('Invitation@sent');

        $router->get('generate/{user_id}')
               ->name('auth.invitation.generate')
               ->uses('Invitation@generate');

        $router->get('inactive')
               ->name('auth.invitation.inactive')
               ->uses('Invitation@inactive');

        $router->get('re-send/{token}')
               ->name('auth.invitation.resend')
               ->uses('Invitation@resend');

        $router->get('failed/{token}')
               ->name('auth.invitation.failed')
               ->uses('Invitation@failed');

        $router->get('{token}')
               ->name('auth.invitation.activate')
               ->uses('Invitation@activate');
    }
});
