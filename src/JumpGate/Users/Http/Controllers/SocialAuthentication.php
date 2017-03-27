<?php

namespace JumpGate\Users\Http\Controllers;

use App\Http\Controllers\BaseController;
use JumpGate\Users\Services\SocialLogin;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthentication extends BaseController
{
    /**
     * @var \JumpGate\Users\Services\SocialLogin
     */
    private $login;

    /**
     * SocialAuthentication constructor.
     *
     * @param \JumpGate\Users\Services\SocialLogin $login
     */
    public function __construct(SocialLogin $login)
    {
        $this->login = $login;
    }

    /**
     * Redirect the user to the social providers auth page.
     *
     * @param null|string $provider
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login($provider = null)
    {
        return $this->login->redirect($provider);
    }

    /**
     * Use the returned user to register (if needed) and login.
     *
     * @param null|string $provider
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function callback($provider = null)
    {
        $this->login->loginUser($provider);

        return redirect()
            ->intended(route('home'))
            ->with('message', 'You have been logged in.');
    }
}
