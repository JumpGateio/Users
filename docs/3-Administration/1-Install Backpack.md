# Install Backpack

- []()

<a name=""></a>
# Installation

```
composer require backpack/base
composer require backpack/crud
```

# Update Configs

1. Open `config/app.php` and add the following files to the `providers` array.
    - `Backpack\Base\BaseServiceProvider::class,`
    - `Backpack\CRUD\CrudServiceProvider::class,` - This must come after base
2. Run the following commands to generate configs.
```
php artisan vendor:publish --provider="Backpack\Base\BaseServiceProvider" #publishes configs, langs, views and AdminLTE files
php artisan vendor:publish --provider="Prologue\Alerts\AlertsServiceProvider" # publish config for notifications - prologue/alerts

php artisan vendor:publish --provider="Backpack\CRUD\CrudServiceProvider" --tag="public" #publish CRUD assets
php artisan vendor:publish --provider="Backpack\CRUD\CrudServiceProvider" --tag="lang" #publish CRUD lang files 
php artisan vendor:publish --provider="Backpack\CRUD\CrudServiceProvider" --tag="config" #publish CRUD and custom elfinder config files
php artisan vendor:publish --provider="Backpack\CRUD\CrudServiceProvider" --tag="elfinder" #publish custom elFinder views
```
3. Define an `uploads` disk in your `config/filesystems.php`
```
'uploads' => [
    'driver' => 'local',
    'root'   => public_path('uploads'),
],
```
4. `mkdir public/uploads`

# Update App Code

1. Open your `app/Models/User.php` and add the `Backpack\CRUD\CrudTrait`.
2. Add the routes to your `app/Providers/RouteServiceProvider.php`
    - You can either include the standard route file (`include_once (base_path('vendor/jumpgate/users/src/routes/admin.php'));`)
    - Or you can use the route class `\JumpGate\Users\Http\Routes\Admin::class,`
    
# Add Extra Packages

This set up expects a few things.  Adding them will make sure you get all the desired functionality.

## Gravatar

1. Add the package.
```
composer require creativeorange/gravatar
```
2. Add the provider to `config/app.php`.
```
'providers' => [
    ...
    Creativeorange\Gravatar\GravatarServiceProvider::class,
    ...
],
```
3. Add the alias to `config/app.php`.
```
'aliases' => [
    ...
    'Gravatar'     => Creativeorange\Gravatar\Facades\Gravatar::class,
    ...
],
```
4. Publish the config.
`php artisan vendor:publish --provider="Creativeorange\Gravatar\GravatarServiceProvider"`
