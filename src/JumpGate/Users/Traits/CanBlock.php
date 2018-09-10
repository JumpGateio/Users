<?php

namespace JumpGate\Users\Traits;

use JumpGate\Users\Models\User\Status;

trait CanBlock
{
    /**
     * Block a user from logging in.
     */
    public function block()
    {
        // Update the user table with the needed details.
        $this->trackTime('blocked_at');

        // Set the user's status to be correct.
        $this->setStatus(Status::BLOCKED);
    }

    /**
     * Unblock a user allowing them to log in.
     */
    public function unblock()
    {
        // Set the user's status to be correct.
        $this->setStatus(Status::ACTIVE);
    }
}
