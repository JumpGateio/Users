<?php

namespace Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        parent::setUp();

        // Make sure we load the view directory.
        $this->app['view']->addLocation(__DIR__ . '/../src/views/bootstrap4');

        $this->app['router']->group([], __DIR__ . '/../src/routes/routes.php');
    }

    /**
     * Get package providers.  At a minimum this is the package being tested, but also
     * would include packages upon which our package depends, e.g. Cartalyst/Sentry
     * In a normal app environment these would be added to the 'providers' array in
     * the config/app.php file.
     *
     * @param  \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            \JumpGate\Users\Providers\UsersServiceProvider::class,
        ];
    }
}
