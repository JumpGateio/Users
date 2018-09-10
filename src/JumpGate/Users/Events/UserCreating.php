<?php

namespace JumpGate\Users\Events;

use Illuminate\Queue\SerializesModels;
use Laravel\Socialite\AbstractUser;
use JumpGate\Users\Models\User;

class UserCreating
{
    use SerializesModels;

    /**
     * @var \JumpGate\Users\Models\User
     */
    public $user;

    /**
     * Create a new event instance.
     *
     * @param \JumpGate\Users\Models\User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }
}
