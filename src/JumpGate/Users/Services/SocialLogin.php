<?php

namespace JumpGate\Users\Services;

use App\Models\User;
use JumpGate\Users\Models\Social\Provider;
use Laravel\Socialite\Facades\Socialite;

class SocialLogin
{
    /**
     * @var array
     */
    protected $providers;

    /**
     * @var null|\JumpGate\Users\Models\Social\Provider
     */
    public $provider = null;

    /**
     * @var \App\Models\User
     */
    private $users;

    /**
     * @param \App\Models\User $users
     */
    public function __construct(User $users)
    {
        $this->users     = $users;
        $this->providers = collect(config('users.providers'))
            ->keyBy('driver');
    }

    /**
     * Try to log the user in and validate their status.
     *
     * @param string $provider The provider being logged in through.
     *
     * @return array
     */
    public function loginUser($provider)
    {
        $this->getProviderDetails($provider);

        $socialUser = Socialite::driver($this->provider->driver)->user();
        $user       = $this->users->where('email', $socialUser->getEmail())
                                  ->orWhereHas('socials', function ($query) use ($socialUser) {
                                      $query->where('email', $socialUser->getEmail())
                                            ->where('provider', $this->provider->driver);
                                  })->first();

        $user = $this->getUser($user, $socialUser);

        $this->updateFromProvider($user, $socialUser);

        return [
            $user,
            $socialUser,
        ];
    }

    /**
     * Make sure that we have a valid user to work with.
     *
     * @param null|\App\Models\User                $user
     * @param null|\Laravel\Socialite\AbstractUser $socialUser
     *
     * @return \App\Models\User
     */
    protected function getUser($user, $socialUser)
    {
        if (! is_null($user)) {
            return $user;
        }

        return app(Registration::class)
            ->registerSocialUser($socialUser, $this->provider->driver);
    }

    /**
     * Either update an existing provider record with the newest details
     * or add this provider to the user.
     *
     * @param null|\App\Models\User                $user
     * @param null|\Laravel\Socialite\AbstractUser $socialUser
     *
     * @return mixed
     */
    protected function updateFromProvider($user, $socialUser)
    {
        if (! $user->hasProvider($this->provider->driver)) {
            return $user->addSocial($socialUser, $this->provider->driver);
        }

        return $user->getProvider($this->provider->driver)
                    ->updateFromProvider($socialUser, $this->provider->driver);
    }

    /**
     * Find the provider's driver, scopes and extras based on a given provider name.
     *
     * @param string $provider The name of the provider.
     *
     * @return \JumpGate\Users\Models\Social\Provider|null
     * @throws \Exception
     * @throws \InvalidArgumentException
     */
    public function getProviderDetails($provider)
    {
        $this->checkProviders();

        $this->getProvider($provider);

        $this->checkDriver($provider);

        return $this->provider;
    }

    /**
     * Make sure we have a valid array of drivers.
     *
     * @throws \Exception
     */
    private function checkProviders()
    {
        if (empty($this->providers)) {
            throw new \Exception('No Providers have been set in users config.');
        }
    }

    /**
     * Get the provider from the supplied name.
     *
     * @param string $providerName The name of the provider.
     *
     * @return \JumpGate\Users\Models\Social\Provider
     */
    private function getProvider($providerName)
    {
        $provider = is_null($providerName)
            ? $this->providers->first()
            : $this->providers->get($providerName);

        return $this->provider = new Provider($provider);
    }

    /**
     * Make sure that the provider has a driver set.
     *
     * @throws \InvalidArgumentException
     */
    private function checkDriver()
    {
        if (is_null($this->provider->driver)) {
            throw new \InvalidArgumentException('You must set a social driver to use the social authenticating features.');
        }
    }
}