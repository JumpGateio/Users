<?php

namespace JumpGate\Users\Http\Routes;

use JumpGate\Core\Contracts\Routes;
use JumpGate\Core\Http\Routes\BaseRoute;
use Illuminate\Routing\Router;

class Logout extends BaseRoute implements Routes
{
    public $namespace = 'JumpGate\Users\Http\Controllers';

    public $context = 'default';

    public $middleware = [
        'web',
        'auth',
    ];

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
        $router->get('logout')
               ->name('auth.logout')
               ->uses('Authentication@logout');
    }

    private function socialAuth(Router $router)
    {
        $router->get('logout')
               ->name('auth.logout')
               ->uses('SocialAuthentication@logout');
    }
}
