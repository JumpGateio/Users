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
        'acl',
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

        $router->get('delete/{id}')
            ->name('admin.users.delete')
            ->uses('Users@delete')
            ->middleware('active:admin.users.delete');

        $router->get('data-table')
            ->name('admin.users.data-table')
            ->uses('Users@dataTable')
            ->middleware('active:admin.users.data-table');
    }
}
