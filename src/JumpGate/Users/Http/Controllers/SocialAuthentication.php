<?php

namespace JumpGate\Users\Http\Controllers;

use App\Http\Controllers\BaseController;
use JumpGate\Users\Services\SocialLogin;
use Laravel\Socialite\Facades\Socialite;
use JumpGate\Users\Events\UserLoggedIn;

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
        $provider = $this->login->getProviderDetails($provider);

        return Socialite::driver($provider->driver)
                        ->scopes($provider->scopes)
                        ->with($provider->extras)
                        ->redirect();
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
        list($user, $socialUser) = $this->login->loginUser($provider);

        auth()->login($user, request('remember', false));
        event(new UserLoggedIn($user, $socialUser));

        return redirect()
            ->intended(route('home'))
            ->with('message', 'You have been logged in.');
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
