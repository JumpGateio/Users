<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        //Registered::class => [
        //    SendEmailVerificationNotification::class,
        //],
        \JumpGate\Users\Events\UserCreating::class    => [],
        \JumpGate\Users\Events\UserCreated::class     => [],
        \JumpGate\Users\Events\UserFailedLogin::class => [],
        \JumpGate\Users\Events\UserLoggingIn::class   => [],
        \JumpGate\Users\Events\UserLoggedIn::class    => [],
        \JumpGate\Users\Events\UserRegistering::class => [],
        \JumpGate\Users\Events\UserRegistered::class  => [],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
