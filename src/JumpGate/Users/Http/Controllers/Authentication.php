<?php

namespace JumpGate\Users\Http\Controllers;

use JumpGate\Core\Http\Controllers\BaseController;
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
        $layout = view()->exists('layouts.default')
            ? 'layouts.default'
            : 'layout';

        $pageTitle = 'Login';

        return view('auth.login', compact('layout', 'pageTitle'));
    }

    /**
     * Display the blocked page.
     */
    public function blocked()
    {
        $layout = view()->exists('layouts.default')
            ? 'layouts.default'
            : 'layout';

        $pageTitle = 'Blocked';

        return view('auth.blocked', compact('layout', 'pageTitle'));
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
        auth()->logout();

        return redirect(route('home'))
            ->with('message', 'You have been logged out.');
    }
}
