<?php

namespace JumpGate\Users\Services;

use App\Models\User;
use JumpGate\Core\Services\Response;
use JumpGate\Users\Models\User\Token;

class ForgotPassword
{
    /**
     * @var \App\Models\User
     */
    private $users;

    /**
     * @var \JumpGate\Users\Models\User\Token
     */
    private $tokens;

    /**
     * ForgotPassword constructor.
     *
     * @param \App\Models\User                  $users
     * @param \JumpGate\Users\Models\User\Token $tokens
     */
    public function __construct(User $users, Token $tokens)
    {
        $this->users  = $users;
        $this->tokens = $tokens;
    }

    /**
     * If the email exists, generate a reset token for that user.
     *
     * @param string $email The user's email.
     */
    public function sendEmail($email)
    {
        $user = $this->users->where('email', $email)->first();

        if (! is_null($user)) {
            $user->generatePasswordResetToken();
        }
    }

    /**
     * Attempt to update the user's password.
     *
     * @param string $token    The token string.
     * @param string $email    The provided email.
     * @param string $password The new password.
     *
     * @return Response
     */
    public function updatePassword($token, $email, $password)
    {
        // Get the token being used.
        $token = $this->tokens->findByToken($token);

        // Make sure the correct email was supplied.
        if ($token->user->email !== $email) {
            return Response::failed('The email does not match this token.')
                           ->route('auth.password.reset');
        }

        // Reset the user's password and let them log in.
        $token->user->resetPassword($password);

        return Response::passed('Password updated.')
                       ->route('auth.login');
    }
}
