# Installation

- [Introduction](#introduction)
- [Service Providers](#service-providers)
- [Configs](#configs)
- [Migrations & Seeds](#migrations-seeds)
- [Model](#model)
- [Optional](#optional)
    - [Routes](#routes)
    - [Middleware](#middleware)
    - [Menu](#Menu)

<a name="introduction"></a>
## Introduction
If you used an installer version that included users, most of these will be done for you.  These are only useful if you are 
adding users manually.

<a name="service-providers"></a>
### Service Providers
Add the following service provider to ``configs/app.php``.

```
'providers' => [
    ...
    JumpGate\Users\Providers\UsersServiceProvider::class,
    ...
],
```

<a name="configs"></a>
### Configs
Once that is done, you can publish the configs.

`php artisan vendor:publish --provider="JumpGate\Users\Providers\UsersServiceProvider"`

This will create a `users.php` in your `config/jumpgate/` folder.

> Make sure to make any changes you need to the config before continuing.  It will determine some of the actions taken in 
the coming steps.

<a name="migrations-seeds"></a>
### Migrations & Seeds
The migration files for users are easily loadable once you have added the service provider.

`php artisan migrate`

This will create the users table and all of the acl tables needed for permissions.

> If you set the `enable_social` key to true in the config, this command will also add the `user_socials` table.

Make sure you update `config/jumpgate/users.php` and set all of your details before running these commands.

```
php artisan db:seed --class=AclSeeder
php artisan db:seed --class=UserStatusSeeder
php artisan db:seed --class=UserSeeder
```

<a name="model"></a>
### Model
Next add a model for your `User` model.  It can be empty but should extend `JumpGate\Users\Models\User`.

> Double check that you have declared this user model in `config/auth.php` in the `providers` array.

<a name="optional"></a>
## Optional
<a name="routes"></a>
### Routes
If you would like to use the included routes, add the following to your `app/Providers/RouteServiceProvider.php` file.

```php
$providers = [
    ...
    \JumpGate\Users\Http\Routes\Guest::class,
    \JumpGate\Users\Http\Routes\Auth::class,
];
```

<a name="middleware"></a>
### Middleware
Included is a middleware for helping with route protection.  You will need to add them to your ``app/Http/Kernel.php``
file.

```php
protected $routeMiddleware = [
    ...
    'acl'        => \App\Http\Middleware\CheckPermission::class,
];
```

To use it in your routes, you would set the parameter as the permission the route requires.

```php
// You can use it in route groups...
Route::group(['middleware' => ['acl:administrate-users']], function () {
    ...
});

// or in a single route...    
Route::get('/admin/users', [
    'as'         => 'admin.user.index',
    'uses'       => 'Admin\UserController@index',
    'middleware' => 'acl:administrate-users',
]);
```

If you are using class based routes, it would look like the following example.

```php
public function middleware()
{
    return [
        'web',
        'auth',
        'acl:administrate-users',
    ];
}
```

<a name="menu"></a>
### Menu
Here are some common additions to the menu located in `app/Http/Composers/MenuComposer.php`

```php
private function generateUserMenu()
{
    $rightMenu = \Menu::getMenu('rightMenu');
    
    if (auth()->guest()) {
        $rightMenu->link('login', function (Link $link) {
            $link->name = 'Login';
            $link->url = route('auth.login');
        });
        $rightMenu->link('register', function (Link $link) {
            $link->name = 'Register';
            $link->url = route('auth.register');
        });
    } else {
        $rightMenu->dropdown('user', auth()->user()->username, function (DropDown $dropDown) {
            $dropDown->link('user_logout', function (Link $link) {
                $link->name = 'Logout';
                $link->url = route('auth.logout');
            });
        });
    }
}
```
