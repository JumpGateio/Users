<?php

namespace JumpGate\Users\Http\Controllers;

use App\Http\Controllers\BaseController;
use App\Models\User;
use JumpGate\Users\Models\User\Token;

class ForgotPassword extends BaseController
{
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

        $user = User::where('email', request('email'))->first();

        // If the email/user exists, trigger the reset.
        if (! is_null($user)) {
            $user->generatePasswordResetToken();
        }

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

        // Get the token being used.
        $token = (new Token)->findByToken(request('token'));

        // Make sure the correct email was supplied.
        if ($token->user->email !== request('email')) {
            return redirect(route('auth.password.reset'))
                ->with('message', 'The email does not match this token.');
        }

        // Reset the user's password and let them log in.
        $token->user->resetPassword(request('password'));

        return redirect(route('auth.login'))
            ->with('message', 'Password updated.');
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
