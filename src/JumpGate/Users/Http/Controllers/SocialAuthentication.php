<?php

namespace JumpGate\Users\Http\Controllers;

use App\Http\Controllers\BaseController;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use JumpGate\Users\Events\UserLoggedIn;
use JumpGate\Users\Events\UserRegistered;
use JumpGate\Users\Models\User\Social;

class SocialAuthentication extends BaseController
{
    /**
     * @var array
     */
    protected $providers;

    /**
     * @var string
     */
    protected $driver;

    /**
     * @var array
     */
    protected $scopes;

    /**
     * @var array
     */
    protected $extras;

    public function __construct()
    {
        $this->providers = collect(config('users.providers'))->keyBy('driver');
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
        $this->getProviderDetails($provider);

        return Socialite::driver($this->driver)
                        ->scopes($this->scopes)
                        ->with($this->extras)
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
        $this->getProviderDetails($provider);

        $socialUser = Socialite::driver($this->driver)->user();
        $user       = User::where('email', $socialUser->getEmail())
                          ->orWhereHas('socials', function ($query) use ($socialUser) {
                              $query->where('email', $socialUser->getEmail())->where('provider', $this->driver);
                          })->first();

        if (is_null($user)) {
            $user = $this->register($socialUser);
        }

        if (! $user->hasProvider($this->driver)) {
            $user->addSocial($socialUser, $this->driver);
        } else {
            $user->getProvider($this->driver)->updateFromProvider($socialUser, $this->driver);
        }

        auth()->login($user, request('remember', false));
        event(new UserLoggedIn($user, $socialUser));

        return redirect()
            ->intended(route('home'))
            ->with('message', 'You have been logged in.');
    }

    /**
     * Create a new user from a social user.
     *
     * @param $socialUser
     *
     * @return mixed
     */
    private function register($socialUser)
    {
        $names    = explode(' ', $socialUser->getName());
        $username = is_null($socialUser->getNickname()) ? $socialUser->getEmail() : $socialUser->getNickname();

        $userDetails = [
            'username'     => $username,
            'email'        => $socialUser->getEmail(),
            'first_name'   => isset($names[0]) ? $names[0] : null,
            'last_name'    => isset($names[1]) ? $names[1] : null,
            'display_name' => $username,
        ];

        $user = User::create($userDetails);
        $user->assignRole(config('users.default'));
        $user->addSocial($socialUser, $this->driver);

        event(new UserRegistered($user));

        return $user;
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

    /**
     * Find the provider's driver, scopes and extras based on a given provider name.
     *
     * @param $provider
     *
     * @throws \Exception
     */
    private function getProviderDetails($provider)
    {
        if (empty($this->providers)) {
            throw new \Exception('No Providers have been set in users config.');
        }

        $provider = is_null($provider) ? $this->providers->first() : $this->providers->get($provider);

        if (is_null($provider['driver'])) {
            throw new \InvalidArgumentException('You must set a social driver to use the social authenticating features.');
        }

        $this->driver = $provider['driver'];
        $this->scopes = $provider['scopes'];
        $this->extras = $provider['extras'];
    }
}
