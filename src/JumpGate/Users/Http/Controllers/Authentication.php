<?php

namespace JumpGate\Users\Http\Controllers;

use App\Http\Controllers\BaseController;
use JumpGate\Users\Events\UserLoggedOut;
use JumpGate\Users\Http\Requests\Login;
use JumpGate\Users\Services\Login as LoginService;

class Authentication extends BaseController
{
    /**
     * @var \JumpGate\Users\Services\Login
     */
    private $login;

    /**
     * @param \JumpGate\Users\Services\Login $login
     */
    public function __construct(LoginService $login)
    {
        $this->login = $login;
    }

    /**
     * Display the login form.
     */
    public function index()
    {
        $pageTitle = 'Login';

        return $this->response(
            compact('pageTitle'),
            'auth.login'
        );
    }

    /**
     * Display the blocked page.
     */
    public function blocked()
    {
        $pageTitle = 'Blocked';

        return $this->response(
            compact('pageTitle'),
            'auth.blocked'
        );
    }

    /**
     * Handle validating the login details.
     *
     * @param \JumpGate\Users\Http\Requests\Login $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handle(Login $request)
    {
        // Set up the auth data
        $userData = [
            'email'    => $request->get('email'),
            'password' => $request->get('password'),
        ];

        // Let the login service handle the checks.
        return $this->login
            ->loginUser($userData)
            ->redirectIntended();
    }

    /**
     * Log the user out.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        $user = auth()->user();
        auth()->logout();

        event(new UserLoggedOut($user));

        return redirect()
            ->route('home')
            ->with('message', 'You have been logged out.');
    }
}
