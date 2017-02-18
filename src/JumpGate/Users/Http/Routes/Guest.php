<?php

namespace JumpGate\Users\Http\Routes;

use JumpGate\Core\Contracts\Routes;
use JumpGate\Core\Providers\Routes as BaseRoutes;
use Illuminate\Routing\Router;

class Guest extends BaseRoutes implements Routes
{
    public function namespacing()
    {
        return 'JumpGate\Users\Http\Controllers';
    }

    public function prefix()
    {
        return $this->getContext('default');
    }

    public function middleware()
    {
        return [
            'web',
            'guest',
        ];
    }

    public function patterns()
    {
        return [];
    }

    public function routes(Router $router)
    {
        if (config('jumpgate.users.enable_social') == false) {
            $this->standardAuth($router);
        } else {
            $this->socialAuth($router);
        }
    }

    private function standardAuth(Router $router)
    {
        $router->get('login')
               ->name('auth.login')
               ->uses('AuthController@login');
        $router->post('login')
               ->name('auth.login')
               ->uses('AuthController@handleLogin');

        $router->get('register')
               ->name('auth.register')
               ->uses('AuthController@register');
        $router->post('register')
               ->name('auth.register')
               ->uses('AuthController@handleRegister');

        $router->get('forgot-password')
               ->name('auth.forgot-password')
               ->uses('AuthController@forgotPassword');
        $router->post('forgot-password')
               ->name('auth.forgot-password')
               ->uses('AuthController@handleForgotPassword');
    }

    private function socialAuth(Router $router)
    {
        $router->get('login/{provider?}')
               ->name('auth.login')
               ->uses('SocialAuthController@login');

        $router->get('callback/{provider}')
               ->name('auth.callback')
               ->uses('SocialAuthController@callback');
    }
}
