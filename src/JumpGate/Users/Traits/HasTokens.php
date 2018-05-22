<?php

namespace JumpGate\Users\Traits;

use JumpGate\Users\Models\User\Token;
use JumpGate\Users\Notifications\PasswordReset;
use JumpGate\Users\Notifications\UserActivation;
use JumpGate\Users\Notifications\UserCreated;

/**
 * Class HasTokens
 *
 * @package JumpGate\Users\Traits
 *
 * @property \JumpGate\Users\Models\User\Token[] $tokens
 */
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
     * Generate a new invitation token for a user.
     *
     * @param null|int $hours
     *
     * @return \JumpGate\Users\Models\User\Token
     */
    public function generateInvitationToken($hours = null)
    {
        $token = app(Token::class)->generate(Token::TYPE_INVITATION, $this, $hours);

        $this->notify(new UserInvitation);

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
     * Generate a new password reset token for a user.
     *
     * @param null|int $hours
     *
     * @return \JumpGate\Users\Models\User\Token
     */
    public function generateNewUserPasswordResetToken($hours = null)
    {
        $token = app(Token::class)->generate(Token::TYPE_PASSWORD_RESET, $this, $hours);

        $this->notify(new UserCreated);

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
     * Get the user's current invitation token.
     *
     * @return \JumpGate\Users\Models\User\Token
     */
    public function getInvitationToken()
    {
        return $this->tokens()->where('type', Token::TYPE_INVITATION)->first();
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
