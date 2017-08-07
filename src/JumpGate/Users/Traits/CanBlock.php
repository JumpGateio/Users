<?php

namespace JumpGate\Users\Traits;

use JumpGate\Users\Models\User\Status;

trait CanBlock
{
    public function block()
    {
        // Update the user table with the needed details.
        $this->trackTime('blocked_at');

        // Set the user's status to be correct.
        $this->setStatus(Status::BLOCKED);
    }

    public function unblock()
    {
        // Set the user's status to be correct.
        $this->setStatus(Status::ACTIVE);
    }
}
