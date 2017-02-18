<?php

namespace JumpGate\Users\Traits;

use Laravel\Socialite\AbstractUser;
use JumpGate\Users\Models\User\Social;

trait HasSocials
{
    public function addSocial(AbstractUser $socialUser, $provider)
    {
        $refreshToken = isset($socialUser->refreshToken) && $socialUser->refreshToken
            ? $socialUser->refreshToken
            : null;

        $this->socials()->create([
            'provider'      => $provider,
            'social_id'     => $socialUser->getId(),
            'email'         => $socialUser->getEmail(),
            'avatar'        => $socialUser->getAvatar(),
            'token'         => $socialUser->token,
            'refresh_token' => $refreshToken,
            'expires_in'    => $socialUser->expiresIn,
        ]);
    }

    public function getProvider($provider)
    {
        return $this->socials()->where('provider', $provider)->first();
    }

    public function hasProvider($provider)
    {
        return $this->socials()->where('provider', $provider)->count() > 0;
    }

    public function socials()
    {
        return $this->hasMany(Social::class, 'user_id');
    }
}
