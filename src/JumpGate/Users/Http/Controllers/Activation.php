<?php

namespace JumpGate\Users\Http\Controllers;

use App\Http\Controllers\BaseController;
use JumpGate\Users\Services\Activation as ActivationService;

class Activation extends BaseController
{
    /**
     * @var \JumpGate\Users\Services\Activation
     */
    private $activation;

    /**
     * @param \JumpGate\Users\Services\Activation $activation
     */
    public function __construct(ActivationService $activation)
    {
        $this->activation = $activation;
    }

    /**
     * Generate an activation token for a user.
     *
     * @param int $userId The ID of the user getting the token.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function generate($userId)
    {
        $this->activation->generateToken($userId);

        return redirect()->route('auth.activation.sent');
    }

    /**
     * Extend the expiry time for the activation token for
     * a user and re-send the email.
     *
     * @param string $tokenString The user's activation token.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function resend($tokenString)
    {
        $this->activation->resend($tokenString);

        return redirect()->route('auth.activation.sent');
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
        return $this->activation
            ->activate($tokenString)
            ->redirect();
    }

    /**
     * Display the sent email message.
     *
     * @return mixed
     */
    public function sent()
    {
        $pageTitle = 'Email sent';

        return $this->response(
            compact('pageTitle'),
            'auth.activation.sent'
        );
    }

    /**
     * Display the account inactive page.
     *
     * @return mixed
     */
    public function inactive()
    {
        $pageTitle = 'Inactive account';

        $token = $this->activation->findTokenByEmail(session('inactive_email'));

        return $this->response(
            compact('pageTitle', 'token'),
            'auth.activation.inactive'
        );
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
        $pageTitle = 'Activation failed';

        $token = $this->activation->findToken($tokenString);

        return $this->response(
            compact('pageTitle', 'token'),
            'auth.activation.failed'
        );
    }
}
