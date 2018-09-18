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
        $this->loadCommands();
        $this->loadPublishable();
    }

    /**
     * Load the configs.
     */
    protected function loadConfigs()
    {
        $this->publishes([
            __DIR__ . '/../../../config/users.php' => config_path('jumpgate/users.php'),
            __DIR__ . '/../../../config/acl.php'   => config_path('acl.php'),
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
        if ($this->app['config']->get('jumpgate.users.load_views')) {
            $viewPath = __DIR__ . '/../../../views/' . $this->app['config']->get('jumpgate.users.css_framework');

            $this->app['view']->addLocation($viewPath);

            $this->publishes([
                $viewPath . '/auth'   => resource_path('views/vendor/auth'),
                $viewPath . '/admin/' => resource_path('views/vendor/admin/'),
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

        $this->publishes([
            $publishDirectory . 'Commands/UserDatabase.php' => app_path('Console/Commands/JumpGate/UserDatabase.php'),
            $publishDirectory . 'Http/Composers/Menu.php'   => app_path('Http/Composers/Menu.php'),
            $publishDirectory . 'Http/Composers/Admin.php'  => app_path('Http/Composers/AdminSidebar.php'),
            $publishDirectory . 'Http/Kernel.php'           => app_path('Http/Kernel.php'),
            $publishDirectory . 'Models/User.php'           => app_path('Models/User.php'),
            $publishDirectory . 'Providers/Auth.php'        => app_path('Providers/AuthServiceProvider.php'),
            $publishDirectory . 'Providers/Composer.php'    => app_path('Providers/ComposerServiceProvider.php'),
            $publishDirectory . 'Providers/Event.php'       => app_path('Providers/EventServiceProvider.php'),
            $publishDirectory . 'Providers/Route.php'       => app_path('Providers/RouteServiceProvider.php'),
            $publishDirectory . 'Services/'                 => app_path('Services/'),
        ], 'user_template_files');
    }
}
