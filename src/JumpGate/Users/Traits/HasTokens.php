<?php

namespace JumpGate\Users\Traits;

use JumpGate\Users\Models\User\Token;
use JumpGate\Users\Notifications\PasswordReset;
use JumpGate\Users\Notifications\UserActivation;

trait HasTokens
{
    /**
     * Generate a new activation token for a user.
     *
     * @param null|int $hours
     *
     * @return \JumpGate\Users\Models\User\Token
     */
    public function generateActivationToken($hours = null)
    {
        $token = app(Token::class)->generate(Token::TYPE_ACTIVATION, $this, $hours);

        $this->notify(new UserActivation);

        return $token;
    }

    /**
     * Generate a new password reset token for a user.
     *
     * @param null|int $hours
     *
     * @return \JumpGate\Users\Models\User\Token
     */
    public function generatePasswordResetToken($hours = null)
    {
        $token = app(Token::class)->generate(Token::TYPE_PASSWORD_RESET, $this, $hours);

        $this->notify(new PasswordReset);

        return $token;
    }

    /**
     * Get the user's current activation token.
     *
     * @return \JumpGate\Users\Models\User\Token
     */
    public function getActivationToken()
    {
        return $this->tokens()->where('type', Token::TYPE_ACTIVATION)->first();
    }

    /**
     * Get the user's current password reset token.
     *
     * @return \JumpGate\Users\Models\User\Token
     */
    public function getPasswordResetToken()
    {
        return $this->tokens()->where('type', Token::TYPE_PASSWORD_RESET)->first();
    }

    /**
     * Any tokens generated for a user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tokens()
    {
        return $this->hasMany(Token::class, 'user_id');
    }
}