<?php

namespace JumpGate\Users\Http\Routes;

use JumpGate\Core\Contracts\Routes;
use JumpGate\Core\Providers\Routes as BaseRoutes;
use Illuminate\Routing\Router;

class Auth extends BaseRoutes implements Routes
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
            'auth',
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
        $router->get('logout')
               ->name('auth.logout')
               ->uses('AuthController@logout');
    }

    private function socialAuth(Router $router)
    {
        $router->get('logout')
               ->name('auth.logout')
               ->uses('SocialAuthController@logout');
    }
}
