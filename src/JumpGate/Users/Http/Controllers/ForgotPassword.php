<?php

namespace JumpGate\Users\Http\Controllers;

use App\Http\Controllers\BaseController;
use JumpGate\Users\Services\ForgotPassword as ForgotPasswordService;

class ForgotPassword extends BaseController
{
    /**
     * @var \JumpGate\Users\Services\ForgotPassword
     */
    private $forgotPassword;

    /**
     * @param \JumpGate\Users\Services\ForgotPassword $forgotPassword
     */
    public function __construct(ForgotPasswordService $forgotPassword)
    {
        $this->forgotPassword = $forgotPassword;
    }

    /**
     * Display the form to request a password reset link.
     *
     * @return \Illuminate\Http\Response
     */
    public function reset()
    {
        $pageTitle = 'Password Reset';

        return $this->response(
            compact('pageTitle'),
            'auth.password.email'
        );
    }

    /**
     * Generate the token for the user to reset their password.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendEmail()
    {
        $this->validate(request(), ['email' => 'required|email']);

        // Attempt to send the email.
        $this->forgotPassword->sendEmail(request('email'));

        return redirect()->route('auth.password.sent');
    }

    /**
     * Display the sent email message.
     *
     * @return \Illuminate\View\View
     */
    public function sent()
    {
        $pageTitle = 'Email sent';

        return $this->response(
            compact('pageTitle'),
            'auth.password.sent'
        );
    }

    /**
     * Display the form to input a new password.
     *
     * @param string $tokenString The password reset token for the user
     *
     * @return \Illuminate\View\View
     */
    public function confirm($tokenString)
    {
        $pageTitle = 'Set your new password';

        return $this->response(
            compact('pageTitle', 'tokenString'),
            'auth.password.reset'
        );
    }

    /**
     * Update the user's password.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handle()
    {
        // Make sure we have everything we need from the form.
        $this->validate(request(), $this->rules());

        // Try to update the user's password.
        return $this->forgotPassword
            ->updatePassword(request('token'), request('email'), request('password'))
            ->redirect();
    }

    /**
     * Get the password reset validation rules.
     *
     * @return array
     */
    protected function rules()
    {
        return [
            'token'    => 'required',
            'email'    => 'required|email',
            'password' => 'required|confirmed|min:6',
        ];
    }
}
