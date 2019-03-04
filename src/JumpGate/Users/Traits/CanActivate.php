<?php

namespace JumpGate\Users\Traits;

use JumpGate\Users\Models\User\Status;
use JumpGate\Users\Services\Activation;

trait CanActivate
{
    /**
     * Activate a user.
     */
    public function activate()
    {
        // Remove the activation token.
        $this->getActivationToken()->delete();

        $this->updateActivationDetails();
    }

    /**
     * Invite a user.
     *
     * @param string                             $email
     * @param collection|object|array|string|int $roles
     *
     * @return \App\Models\User
     */
    public function sendNewUserActivation($email, $roles)
    {
        $user = static::firstOrCreate(compact('email'));
        $user->assignRole($roles);

        /** @var Activation $invites */
        $invites = app(Activation::class);
        $invites->generateToken($user->id);

        Detail::firstOrCreate([
            'user_id'      => $user->id,
            'display_name' => $email,
        ]);

        return $user;
    }

    /**
     * Update a users activation details.
     */
    public function updateActivationDetails()
    {
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
