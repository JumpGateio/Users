<?php namespace JumpGate\Users\Providers;

use Config;
use Illuminate\Support\ServiceProvider;
use JumpGate\Users\Console\Commands\AddPermissions;

class UsersServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        if (! class_exists('\App\Models\User')) {
            $this->addUserModel();
        }

        $this->mergeConfigFrom(
            __DIR__ . '/../../../config/users.php', 'jumpgate.users'
        );
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadConfigs();
        $this->loadMigrations();
        $this->loadViews();
        $this->loadCommands();
    }

    /**
     * Load the configs.
     *
     * @return void
     */
    protected function loadConfigs()
    {
        $this->publishes([
            __DIR__ . '/../../../config/users.php' => config_path('jumpgate/users.php'),
        ]);
    }

    /**
     * Load the migrations.
     *
     * @return void
     */
    protected function loadMigrations()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../../../database/migrations');

        if ($this->app['config']->get('jumpgate.users.enable_social')) {
            $this->loadMigrationsFrom(__DIR__ . '/../../../database/social_migrations');
        }
    }

    /**
     * Register views
     *
     * @return void
     */
    protected function loadViews()
    {
        if ($this->app['config']->get('jumpgate.users.load_views')) {
            $viewPath = __DIR__ . '/../../../views/' . $this->app['config']->get('jumpgate.users.css_framework');

            $this->app['view']->addLocation($viewPath);

            $this->loadViewsFrom($viewPath . '/auth', 'jumpgate.users.auth');
            $this->loadViewsFrom($viewPath . '/admin/user', 'jumpgate.users.admin');

            $this->publishes([
                $viewPath . '/auth'       => resource_path('views/vendor/auth'),
                $viewPath . '/admin/user' => resource_path('views/vendor/admin/user'),
            ]);
        }
    }

    private function loadCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                AddPermissions::class,
            ]);
        }
    }

    private function addUserModel()
    {
        $files = app('files');

        if (! $files->exists(app_path('Models'))) {
            $files->makeDirectory(app_path('Models'), 0755, true);
        }

        $files->copy(__DIR__ . '/../../../stubs/UserModel.php', app_path('Models/User.php'));
    }
}
