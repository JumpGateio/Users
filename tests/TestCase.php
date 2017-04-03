<?php

namespace Tests;

use Illuminate\Database\Eloquent\Factory;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Orchestra\Testbench\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    use DatabaseMigrations;

    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        $pathToFactories = realpath(dirname(__DIR__) . '/src/database/factories');

        parent::setUp();

        $this->runDatabaseMigrations();

        $this->withFactories($pathToFactories);
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
