<?php

namespace JumpGate\Users\Traits;

use JumpGate\Users\Models\User\Status;

trait CanActivate
{
    /**
     * Activate a user.
     */
    public function activate()
    {
        // Remove the activation token.
        $this->getActivationToken()->delete();

        // Update the user table with the needed details.
        $this->updateLogin();
        $this->trackTime('activated_at');

        // Set the user's status to be correct.
        $this->setStatus(Status::ACTIVE);
    }
}
