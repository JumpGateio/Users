<?php

namespace JumpGate\Users\Http\Routes;

use JumpGate\Core\Contracts\Routes;
use JumpGate\Core\Http\Routes\BaseRoute;
use Illuminate\Routing\Router;

class Activation extends BaseRoute implements Routes
{
    public $namespace = 'JumpGate\Users\Http\Controllers';

    public $context = 'default';

    public $prefix = 'activation';

    public $middleware = [
        'web',
        'guest',
    ];

    public function routes(Router $router)
    {
        if (config('jumpgate.users.enable_social') == false) {
            $this->standardAuth($router);
        }
    }

    private function standardAuth(Router $router)
    {
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
}
