<?php

namespace JumpGate\Users\Providers;

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
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../../config/users.php', 'jumpgate.users'
        );
    }

    /**
     * Register the service provider.
     */
    public function boot()
    {
        $this->loadConfigs();
        $this->loadMigrations();
        $this->loadViews();
        $this->loadComponents();
        $this->loadCommands();
        $this->loadPublishable();
    }

    /**
     * Load the configs.
     */
    protected function loadConfigs()
    {
        $this->publishes([
            __DIR__ . '/../../../config/users.php'            => config_path('jumpgate/users.php'),
            __DIR__ . '/../../../config/laratrust.php'        => config_path('laratrust.php'),
            __DIR__ . '/../../../config/laratrust_seeder.php' => config_path('laratrust_seeder.php'),
        ]);
    }

    /**
     * Load the migrations.
     */
    protected function loadMigrations()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../../../database/migrations');

        if ($this->app['config']->get('jumpgate.users.enable_social')) {
            $this->loadMigrationsFrom(__DIR__ . '/../../../database/social_migrations');
        }
    }

    /**
     * Register views.
     */
    protected function loadViews()
    {
        $loadFiles = $this->app['config']->get('jumpgate.users.load_files');
        $driver    = $this->app['config']->get('jumpgate.users.driver');

        if ($driver === 'blade' && $loadFiles) {
            $viewPath = __DIR__ . '/../../../views/' . $this->app['config']->get('app.css_framework');

            $this->app['view']->addLocation($viewPath);

            $this->loadViewsFrom($viewPath . '/auth', 'jumpgate.users.auth');
            $this->loadViewsFrom($viewPath . '/admin', 'jumpgate.users.admin');

            $this->publishes([
                $viewPath . '/auth'  => resource_path('views/vendor/auth'),
                $viewPath . '/admin' => resource_path('views/vendor/admin'),
            ]);
        }
    }

    /**
     * Register inertia components.
     */
    protected function loadComponents()
    {
        $loadFiles = $this->app['config']->get('jumpgate.users.load_files');
        $driver    = $this->app['config']->get('jumpgate.users.driver');

        if ($driver === 'inertia' && $loadFiles) {
            $componentPath = __DIR__ . '/../../../components/';

            $this->loadViewsFrom($componentPath . '/auth', 'jumpgate.users.auth');
            $this->loadViewsFrom($componentPath . '/admin', 'jumpgate.users.admin');

            $this->publishes([
                $componentPath . '/auth'  => resource_path('js/Pages/Auth'),
                $componentPath . '/admin' => resource_path('js/Pages/Admin'),
            ]);
        }
    }

    /**
     * Register commands.
     */
    private function loadCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                AddPermissions::class,
            ]);
        }
    }

    /**
     * Add any extra publishable files to the site.
     */
    private function loadPublishable()
    {
        $publishDirectory = __DIR__ . '/../../../publish/';

        $publishable = [
            $publishDirectory . 'Commands/UserDatabase.php'        => app_path('Console/Commands/JumpGate/UserDatabase.php'),
            $publishDirectory . 'Http/Composers/AdminSidebar.php'  => app_path('Http/Composers/AdminSideBar.php'),
            $publishDirectory . 'Http/Composers/Menu.php'          => app_path('Http/Composers/Menu.php'),
            $publishDirectory . 'Http/Middleware/Authenticate.php' => app_path('Http/Middleware/Authenticate.php'),
            $publishDirectory . 'Models/User.php'                  => app_path('Models/User.php'),
            $publishDirectory . 'Providers/Event.php'              => app_path('Providers/EventServiceProvider.php'),
            $publishDirectory . 'Services/Admin'                   => app_path('Services/Admin/'),
            $publishDirectory . 'config/route.php'                 => base_path('config/route.php'),
            $publishDirectory . 'factories/User'                   => base_path('database/factories/User/'),
            $publishDirectory . 'factories/UserFactory.php'        => base_path('database/factories/UserFactory.php'),
            $publishDirectory . 'seeds'                            => base_path('database/seeds/'),
        ];

        $this->publishes($publishable, 'user_template_files');
    }
}
