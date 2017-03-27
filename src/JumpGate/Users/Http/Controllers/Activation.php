<?php

namespace JumpGate\Users\Http\Controllers;

use JumpGate\Core\Http\Controllers\BaseController;
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
    public function resend($tokenString)
    {
        $this->activation->resend($tokenString);

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
        $layout = view()->exists('layouts.default')
            ? 'layouts.default'
            : 'layout';

        $pageTitle = 'Email sent';

        return view('auth.activation.sent', compact('layout', 'pageTitle'));
    }

    /**
     * Display the account inactive page.
     *
     * @return mixed
     */
    public function inactive()
    {
        $layout = view()->exists('layouts.default')
            ? 'layouts.default'
            : 'layout';

        $pageTitle = 'Inactive account';

        $token = $this->activation->findTokenByEmail(session('inactive_email'));

        return view('auth.activation.inactive', compact('layout', 'pageTitle', 'token'));
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

        $token = $this->activation->findToken($tokenString);

        return view('auth.activation.failed', compact('layout', 'pageTitle', 'token'));
    }
}
