<?php

namespace JumpGate\Users\Services;

use App\Models\User;
use JumpGate\Core\Services\Response;
use JumpGate\Users\Models\User\Status;
use JumpGate\Users\Models\User\Token;

class Invitation
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
     * @param \App\Models\User                  $users
     * @param \JumpGate\Users\Models\User\Token $tokens
     */
    public function __construct(User $users, Token $tokens)
    {
        $this->users  = $users;
        $this->tokens = $tokens;
    }

    /**
     * Generate an invitation token for a user.
     *
     * @param int $userId The user's ID.
     */
    public function generateToken($userId)
    {
        $user = $this->users->find($userId);

        if (! is_null($user)) {
            $user->generateInvitationToken();
            $user->trackTime('invited_at');
            $user->setStatus(Status::INACTIVE);
        }
    }

    /**
     * Find the invitation token, extend it's expire time and resend the email.
     *
     * @param string $tokenString The invitation token.
     */
    public function resend($tokenString)
    {
        $token = $this->findToken($tokenString);

        if (! is_null($token)) {
            session()->forget('inactive_email');

            $token->user->resendInvite();
        }
    }

    /**
     * Attempt to activate the user by the provided token.
     *
     * @param string $tokenString The invitation token.
     *
     * @return Response
     */
    public function activate($tokenString)
    {
        $token = $this->findToken($tokenString);

        // If we could not find a token or it has expired, fail the attempt.
        if (is_null($token) || $token->isExpired()) {
            return Response::failed('Activation failed.')
                           ->route('auth.invitation.failed', $tokenString);
        }

        // Activate the user and log them in.
        $token->user->activateInvitation();

        auth()->login($token->user);

        return Response::passed('Your account has been activated.')
                       ->route('home');
    }

    /**
     * Find a Token object by the token string.
     *
     * @param string $tokenString The invitation token.
     *
     * @return \JumpGate\Users\Models\User\Token|null
     */
    public function findToken($tokenString)
    {
        return $this->tokens->findByToken($tokenString);
    }

    /**
     * Find a Token object by the token string.
     *
     * @param string $email The user's email address.
     *
     * @return \JumpGate\Users\Models\User\Token|null
     */
    public function findTokenByEmail($email)
    {
        $user = $this->users->where('email', $email)->first();

        if (! is_null($user)) {
            return $user->getInvitationToken();
        }

        return null;
    }
}
