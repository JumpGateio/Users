<?php

namespace JumpGate\Users\Services;

use App\Models\User;
use Illuminate\Support\Str;
use JumpGate\Users\Events\UserCreated;
use JumpGate\Users\Events\UserCreating;
use JumpGate\Users\Events\UserRegistered;
use JumpGate\Users\Events\UserRegistering;
use JumpGate\Users\Models\User\Detail;
use JumpGate\Users\Models\User\Status;

class Registration
{
    /**
     * @var \JumpGate\Users\Models\User
     */
    private $user;

    /**
     * @var \JumpGate\Users\Models\User\Detail
     */
    private $userDetails;

    /**
     * @param \JumpGate\Users\Models\User\Detail $userDetails
     */
    public function __construct(Detail $userDetails)
    {
        $userModel = config('auth.providers.users.model');

        $this->user        = new $userModel;
        $this->userDetails = $userDetails;
    }

    /**
     * Register a new user.
     *
     * @return \JumpGate\Users\Models\User|boolean
     */
    public function registerUser()
    {
        // Create the new user
        $user = $this->user->create($this->getUserFromRequest());

        if (! $user) {
            return $user;
        }

        // Fire the registering event.
        event(new UserRegistering($user));

        // Add the extra details needed to finish them.
        // Add the user details.
        $this->userDetails->create($this->getUserDetailsFromRequest($user));

        // Assign the user to the default group
        $user->attachRole(config('jumpgate.users.default_role'));

        // If we do not require activation, set the user as active.
        if (! config('jumpgate.users.require_email_activation')) {
            $user->setStatus(Status::ACTIVE);
        }

        // Fire the registered event.
        event(new UserRegistered($user));

        return $user;
    }

    /**
     * Register a new user from a social login.
     *
     * @param object $socialUser
     * @param string $provider
     *
     * @return bool|\JumpGate\Users\Models\User
     */
    public function registerSocialUser($socialUser, $provider)
    {
        // Create the new user
        $user = $this->user->create($this->getUserFromSocial($socialUser));

        if (! $user) {
            return $user;
        }

        // Fire the registering event.
        event(new UserRegistering($user, $socialUser));

        // Add the extra details needed to finish them.
        // Add the user details.
        $this->userDetails->create($this->getUserDetailsFromSocial($socialUser, $user));

        // Assign the user to the default group
        $user->attachRole(config('jumpgate.users.default_role'));

        // Add the user's social account details.
        $user->addSocial($socialUser, $provider);

        // If we do not require activation, set the user as active.
        if (! config('jumpgate.users.require_email_activation')) {
            $user->updateActivationDetails();
        }

        // Fire the registered event.
        event(new UserRegistered($user, $socialUser));

        return $user;
    }

    /**
     * Create a new user.
     *
     * @param array $user
     * @param array $roles
     *
     * @return bool|\JumpGate\Users\Models\User
     */
    public function createUser($user, $roles = [])
    {
        // Create the new user
        $password = [
            'password' => bcrypt(Str::random(10)),
        ];

        $user = array_merge($password, $user);
        $user = $this->user->create($user);

        if (! $user) {
            return $user;
        }

        // Fire the creating event.
        event(new UserCreating($user));

        // Add the extra details needed to finish them.
        // Add the user details.
        $this->userDetails->create($this->getUserDetailsFromRequest($user));

        // Assign the user to the default group
        if (is_array($roles) || $roles instanceof Collection) {
            $user->syncRoles($roles);
        }

        // the user should always have the default role.
        if (empty($roles) || $roles === '') {
            $user->attachRole(config('jumpgate.users.default_role'));
        }

        // If we do not require activation, set the user as active.
        if (! config('jumpgate.users.require_email_activation')) {
            $user->updateActivationDetails();
        }

        // New user needs to set their password.
        $user->generateNewUserPasswordResetToken();

        // Fire the created event.
        event(new UserCreated($user));

        return $user;
    }

    /**
     * Separate the user core information from the extra details.
     *
     * @return array
     */
    private function getUserFromRequest()
    {
        return request()->only('email', 'password');
    }

    /**
     * Separate the user core information from the extra details.
     *
     * @param \App\Models\User $user
     *
     * @return array
     */
    private function getUserDetailsFromRequest(User $user)
    {
        $details = request()->only(
            'first_name',
            'middle_name',
            'last_name',
            'display_name',
            'timezone',
            'location',
            'url'
        );

        $details['user_id'] = $user->id;

        return array_filter($details);
    }

    /**
     * Get a possible user email from the social object.
     *
     * @param $socialUser
     *
     * @return array
     */
    private function getUserFromSocial($socialUser)
    {
        return ['email' => $socialUser->getEmail()];
    }

    /**
     * Try to get as many details as possible from the social user object.
     *
     * @param $socialUser
     * @param $user
     *
     * @return array
     */
    private function getUserDetailsFromSocial($socialUser, $user)
    {
        $names    = explode(' ', $socialUser->getName());
        $username = is_null($socialUser->getNickname())
            ? $socialUser->getEmail()
            : $socialUser->getNickname();

        $details = [
            'user_id'      => $user->id,
            'first_name'   => isset($names[0]) ? $names[0] : null,
            'last_name'    => isset($names[1]) ? $names[1] : null,
            'display_name' => $username,
        ];

        return array_filter($details);
    }
}
