<?php

namespace JumpGate\Users\Services;

use JumpGate\Core\Services\Response;
use JumpGate\Users\Events\UserLoggedIn;
use JumpGate\Users\Models\User;
use JumpGate\Users\Models\User\Status;

class Login
{
    /**
     * Try to log the user in and validate their status.
     *
     * @param array $userData The supplied email/password.
     *
     * @return Response
     */
    public function loginUser(array $userData)
    {
        // Login has failed with this email/password.
        if (! auth()->attempt($userData, request('remember', false))) {
            return $this->handleInvalidCredentials();
        }

        // Check if the user is inactive.
        if (auth()->user()->status_id === Status::INACTIVE) {
            return $this->handleInactiveUser();
        }

        // Check if the user has been blocked.
        if (auth()->user()->status_id === Status::BLOCKED) {
            return $this->handleBlockedUsers();
        }

        // User has passed all checks and can be redirected to the site.
        return $this->handleSuccessfulLogin();
    }

    /**
     * Called when the user's email/password are not valid.
     *
     * @return array
     */
    private function handleInvalidCredentials()
    {
        // todo - create this event
        // event(new UserFailedLogin('password'));

        // Try to track the failed login.
        User::failedLogin(request('email'));

        return Response::failed('Your email or password was incorrect.')
            ->route('auth.login');
    }

    /**
     * Called when the user is inactive.
     *
     * @return array
     */
    private function handleInactiveUser()
    {
        // todo - create this event
        // event(new UserFailedLogin('inactive'));

        // Log the user out.
        auth()->logout();

        // todo - change route into a page offering to resend the email
        return Response::failed('Your account is not yet activated.')
                       ->route('auth.login');
    }

    /**
     * Called when the user is blocked.
     *
     * @return array
     */
    private function handleBlockedUsers()
    {
        // todo - create this event
        // event(new UserFailedLogin('blocked'));

        // Log the user out.
        auth()->logout();

        // todo - change route into a page detailing that they have been blocked.
        return Response::failed('You are not allowed to access the site at this time.')
                       ->route('auth.login');
    }

    /**
     * Called when the user has passed all authentication checks.
     *
     * @return array
     */
    private function handleSuccessfulLogin()
    {
        // todo - create this event
        event(new UserLoggedIn(auth()->user(), null));

        // Update the user with the log in details.
        auth()->user()->updateLogin();

        return Response::passed('You have been logged in.')
                       ->route('home');
    }
}
