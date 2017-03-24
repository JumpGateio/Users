<?php

namespace JumpGate\Users\Http\Controllers;

use App\Http\Controllers\BaseController;
use App\Models\User;
use JumpGate\Users\Models\User\Token;
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
        $layout = view()->exists('layouts.default')
            ? 'layouts.default'
            : 'layout';

        $pageTitle = 'Password Reset';

        return view('auth.password.email', compact('layout', 'pageTitle'));
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

        return redirect(route('auth.password.sent'));
    }

    /**
     * Display the sent email message.
     *
     * @return \Illuminate\View\View
     */
    public function sent()
    {
        $layout = view()->exists('layouts.default')
            ? 'layouts.default'
            : 'layout';

        $pageTitle = 'Email sent';

        return view('auth.password.sent', compact('layout', 'pageTitle'));
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
        $layout = view()->exists('layouts.default')
            ? 'layouts.default'
            : 'layout';

        $pageTitle = 'Set your new password';

        return view('auth.password.reset', compact('layout', 'pageTitle', 'tokenString'));
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
