<?php

namespace JumpGate\Users\Events;

use Illuminate\Queue\SerializesModels;
use Laravel\Socialite\AbstractUser;
use JumpGate\Users\Models\User;

class UserLoggedIn
{
    use SerializesModels;

    /**
     * @var \JumpGate\Users\Models\User
     */
    public $user;

    /**
     * @var \Laravel\Socialite\AbstractUser|null
     */
    public $socialUser;

    /**
     * Create a new event instance.
     *
     * @param \JumpGate\Users\Models\User          $user
     * @param \Laravel\Socialite\AbstractUser|null $socialUser
     */
    public function __construct(User $user, AbstractUser $socialUser = null)
    {
        $this->user       = $user;
        $this->socialUser = $socialUser;
    }
}
