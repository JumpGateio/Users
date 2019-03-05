<?php

namespace JumpGate\Users\Listeners;

use App\Exceptions\RedirectionException;
use App\Models\User;

class BlockIfRegistrationDisabled
{
    /**
     * Handle the event.
     *
     * @param  \JumpGate\Users\Events\UserLoggingIn|\JumpGate\Users\Events\UserRegistering $event
     *
     * @return bool
     * @throws \App\Exceptions\RedirectionException
     */
    public function handle($event)
    {
        if (config('jumpgate.users.settings.allow_registration')) {
            return true;
        }

        $email = $event->socialUser->getEmail();

        if (User::where('email', $email)->count() === 0) {
            throw new RedirectionException(
                500,
                'Registration is disabled for this site.',
                route('home')
            );
        }
    }
}
