<?php

namespace JumpGate\Users\Events;

use Illuminate\Queue\SerializesModels;
use Laravel\Socialite\AbstractUser;

class UserLoggingIn
{
    use SerializesModels;

    /**
     * @var \Laravel\Socialite\AbstractUser|null
     */
    public $socialUser;

    /**
     * Create a new event instance.
     *
     * @param \Laravel\Socialite\AbstractUser|null $socialUser
     */
    public function __construct(AbstractUser $socialUser = null)
    {
        $this->socialUser = $socialUser;
    }
}
