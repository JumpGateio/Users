<?php

namespace App\Services\Admin\Http\Routes;

use JumpGate\Core\Contracts\Routes;
use JumpGate\Core\Http\Routes\BaseRoute;
use Illuminate\Routing\Router;

class Users extends BaseRoute implements Routes
{
    public $namespace = 'App\Services\Admin\Http\Controllers';

    public $context = 'default';

    public $prefix = 'admin/users';

    public $role = 'admin';

    public $middleware = [
        'web',
        'auth',
        'role:admin',
    ];

    public function routes(Router $router)
    {
        $router->get('/')
            ->name('admin.users.index')
            ->uses('Users@index')
            ->middleware('active:admin.users.index');

        $router->get('create')
            ->name('admin.users.create')
            ->uses('Users@create')
            ->middleware('active:admin.users.create');
        $router->post('create')
            ->name('admin.users.create')
            ->uses('Users@store')
            ->middleware('active:admin.users.create');

        $router->get('edit/{id}')
            ->name('admin.users.edit')
            ->uses('Users@edit')
            ->middleware('active:admin.users.edit');
        $router->post('edit/{id}')
            ->name('admin.users.edit')
            ->uses('Users@update')
            ->middleware('active:admin.users.edit');

        $router->get('confirm/{id}/{status}/{action?}')
            ->name('admin.users.confirm')
            ->uses('Users@confirm')
            ->middleware('active:admin.users.confirm');
        $router->post('confirm/{id}/{status?}/{action?}')
            ->name('admin.users.confirm')
            ->uses('Users@confirmed')
            ->middleware('active:admin.users.confirm');
    }
}
