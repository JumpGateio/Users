<?php

namespace JumpGate\Users\Http\Controllers;

use JumpGate\Core\Http\Controllers\BaseController;
use App\Models\User;
use JumpGate\Users\Models\User\Status;
use JumpGate\Users\Models\User\Token;

class Activation extends BaseController
{
    /**
     * Generate an activation token for a user.
     *
     * @param int $userId The ID of the user getting the token.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function generate($userId)
    {
        $user = User::find($userId);

        $user->generateActivationToken();
        $user->setStatus(Status::INACTIVE);

        return redirect(route('auth.activation.sent'));
    }

    /**
     * Extend the expiry time for the activation token for
     * a user and re-send the email.
     *
     * @param string $tokenString The user's activation token.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reSend($tokenString)
    {
        $token = (new Token)->findByToken($tokenString);
        $token->extend();

        $token->notifyUser();

        return redirect(route('auth.activation.sent'));
    }

    /**
     * Activate the user's account and log them in.
     *
     * @param string $tokenString The user's activation token.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function activate($tokenString)
    {
        $token = (new Token)->findByToken($tokenString);

        // If we did not find a token or it is expired, let them know.
        if (is_null($token) || $token->isExpired()) {
            return redirect(route('auth.activation.failed', $tokenString));
        }

        // Activate the user and log them in.
        $token->user->activate();

        auth()->login($token->user);

        return redirect(route('home'))
            ->with('message', 'Your account has been activated.');
    }

    /**
     * Display the sent email message.
     *
     * @return mixed
     */
    public function sent()
    {
        $layout = view()->exists('layouts.default')
            ? 'layouts.default'
            : 'layout';

        $pageTitle = 'Email sent';

        return view('auth.activation.sent', compact('layout', 'pageTitle'));
    }

    /**
     * Display the page saying that activation failed.
     *
     * @param string $tokenString The user's activation token.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function failed($tokenString)
    {
        $layout = view()->exists('layouts.default')
            ? 'layouts.default'
            : 'layout';

        $pageTitle = 'Activation failed';

        $token = (new Token)->findByToken($tokenString);

        return view('auth.activation.failed', compact('layout', 'pageTitle', 'token'));
    }
}
