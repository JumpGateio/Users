<?php

namespace JumpGate\Users\Services;

use App\Models\User;
use JumpGate\Users\Events\UserRegistered;
use JumpGate\Users\Models\User\Detail;

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
     * @return \JumpGate\Users\Models\User|boolean
     */
    public function handle()
    {
        // Create the new user
        $user = $this->user->create($this->getUserFromRequest());

        // If we created the user, add the extra details needed to finish them.
        if ($user) {
            // Add the user details.
            $this->userDetails->create($this->getUserDetailsFromRequest($user));

            // Assign the user to the default group
            $user->assignRole(config('jumpgate.users.default_group'));

            // Fire the registered event.
            event(new UserRegistered($user));
        }

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
}
