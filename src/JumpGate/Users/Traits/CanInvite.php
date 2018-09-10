<?php

namespace JumpGate\Users\Traits;

use JumpGate\Users\Models\User\Status;

trait CanInvite
{
    /**
     * Activate a user from an invitation.
     */
    public function activateInvitation()
    {
        // Remove the invitation token.
        $this->getInvitationToken()->delete();

        // Update the user table with the needed details.
        $this->updateLogin();
        $this->trackTime('activated_at');

        // Set the user's status to be correct.
        $this->setStatus(Status::ACTIVE);
    }
}
