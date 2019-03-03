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

    /**
     * Determine if this user is currently active.
     *
     * @return bool
     */
    public function isActive()
    {
        return $this->status_id === Status::ACTIVE;
    }

    /**
     * Determine if this user is currently inactive.
     *
     * @return bool
     */
    public function isInactive()
    {
        return $this->status_id === Status::INACTIVE;
    }
}
