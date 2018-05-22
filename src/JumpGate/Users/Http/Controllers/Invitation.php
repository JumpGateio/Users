<?php

namespace JumpGate\Users\Http\Controllers;

use JumpGate\Core\Http\Controllers\BaseController;
use JumpGate\Users\Services\Invitation as InvitationService;

class Invitation extends BaseController
{
    /**
     * @var \JumpGate\Users\Services\Invitation
     */
    private $invitation;

    /**
     * @param \JumpGate\Users\Services\Invitation $invitation
     */
    public function __construct(InvitationService $invitation)
    {
        $this->invitation = $invitation;
    }

    /**
     * Generate an invitation token for a user.
     *
     * @param int $userId The ID of the user getting the token.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function generate($userId)
    {
        $this->invitation->generateToken($userId);

        return redirect(route('auth.invitation.sent'));
    }

    /**
     * Extend the expiry time for the invitation token for
     * a user and re-send the email.
     *
     * @param string $tokenString The user's invitation token.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function resend($tokenString)
    {
        $this->invitation->resend($tokenString);

        return redirect(route('auth.invitation.sent'));
    }

    /**
     * Activate the user's account and log them in.
     *
     * @param string $tokenString The user's invitation token.
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function activate($tokenString)
    {
        return $this->invitation
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

        return view('auth.invitation.sent', compact('layout', 'pageTitle'));
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

        $token = $this->invitation->findTokenByEmail(session('inactive_email'));

        return view('auth.invitation.inactive', compact('layout', 'pageTitle', 'token'));
    }

    /**
     * Display the page saying that invitation failed.
     *
     * @param string $tokenString The user's invitation token.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function failed($tokenString)
    {
        $layout = view()->exists('layouts.default')
            ? 'layouts.default'
            : 'layout';

        $pageTitle = 'Invite failed';

        $token = $this->invitation->findToken($tokenString);

        return view('auth.invitation.failed', compact('layout', 'pageTitle', 'token'));
    }
}
