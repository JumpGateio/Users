<?php

namespace JumpGate\Users\Traits;

use JumpGate\Users\Models\User\Detail;
use JumpGate\Users\Models\User\Status;
use JumpGate\Users\Services\Invitation;

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

    /**
     * Invite a user.
     *
     * @param string                             $email
     * @param collection|object|array|string|int $roles
     *
     * @return \App\Models\User
     */
    public function inviteNewUser($email, $roles)
    {
        $user = static::firstOrCreate(compact('email'));
        $user->assignRole($roles);

        /** @var Invitation $invites */
        $invites = app(Invitation::class);
        $invites->generateToken($user->id);

        Detail::firstOrCreate([
            'user_id'      => $user->id,
            'display_name' => $email,
        ]);

        return $user;
    }

    /**
     * Resend an invitation to the user.
     */
    public function resendInvite()
    {
        $token = $this->getInvitationToken();

        $token->extend();
        $token->notifyUser();
    }

    /**
     * Remove the invite for the user.
     */
    public function revokeToken()
    {
        // Remove the invitation token.
        $this->getInvitationToken()->delete();
    }
}
