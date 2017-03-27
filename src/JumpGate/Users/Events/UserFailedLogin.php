<?php

namespace JumpGate\Users\Events;

use Illuminate\Queue\SerializesModels;
use Laravel\Socialite\AbstractUser;
use JumpGate\Users\Models\User;

class UserFailedLogin
{
    use SerializesModels;

    /**
     * @var string
     */
    public $reason;

    /**
     * Create a new event instance.
     *
     * @param string $reason
     */
    public function __construct($reason)
    {
        $this->reason = $reason;
    }
}
