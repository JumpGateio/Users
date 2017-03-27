<?php

namespace JumpGate\Users\Http\Routes;

use JumpGate\Core\Contracts\Routes;
use JumpGate\Core\Http\Routes\BaseRoute;
use Illuminate\Routing\Router;

class Authentication extends BaseRoute implements Routes
{
    public $namespace = 'JumpGate\Users\Http\Controllers';

    public $context = 'default';

    public $middleware = [
        'web',
        'guest',
    ];

    public function routes(Router $router)
    {
        if (config('jumpgate.users.social_auth_only') == false) {
            $this->standardAuth($router);
        }

        if (config('jumpgate.users.enable_social') == true) {
            $this->socialAuth($router);
        }
    }

    private function standardAuth(Router $router)
    {
        $router->get('login')
               ->name('auth.login')
               ->uses('Authentication@index');
        $router->post('login')
               ->name('auth.login')
               ->uses('Authentication@handle');
    }

    private function socialAuth(Router $router)
    {
        // Login
        $router->get('login/{provider?}')
               ->name('auth.social.login')
               ->uses('SocialAuthentication@login');

        $router->get('callback/{provider}')
               ->name('auth.social.callback')
               ->uses('SocialAuthentication@callback');
    }
}
