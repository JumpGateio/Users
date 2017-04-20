<?php

namespace JumpGate\Users\Models\User;

use App\Models\BaseModel;
use App\Models\User;
use Laravel\Socialite\AbstractUser;

class Social extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_socials';

    /**
     * The attributes that can be safely filled.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'provider',
        'social_id',
        'email',
        'avatar',
        'token',
        'refresh_token',
        'expires_in',
    ];

    public function updateFromProvider(AbstractUser $socialUser, $provider)
    {
        $refreshToken = isset($socialUser->refreshToken) && $socialUser->refreshToken
            ? $socialUser->refreshToken
            : null;
        
        $token = is_null($socialUser->token)
            ? $provider
            : $socialUser->token;

        $attributes = [
            'user_id'       => $this->user_id,
            'provider'      => $provider,
            'social_id'     => $socialUser->getId(),
            'email'         => $socialUser->getEmail(),
            'avatar'        => $socialUser->getAvatar(),
            'token'         => $token,
            'refresh_token' => $refreshToken,
            'expires_in'    => isset($socialUser->expiresIn) ? $socialUser->expiresIn : null,
        ];

        $this->updateOrCreate(array_only($attributes, ['user_id', 'provider', 'email']), $attributes);
    }

    /**
     * Social details are for a specific user.
     *
     * @return  \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
