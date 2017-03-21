<?php

namespace JumpGate\Users\Http\Controllers;

use JumpGate\Core\Http\Controllers\BaseController;
use Illuminate\Support\Facades\DB;
use JumpGate\Users\Http\Requests\Login;
use JumpGate\Users\Http\Requests\Registration;
use JumpGate\Users\Models\User\Status;
use JumpGate\Users\Services\Login as LoginService;
use JumpGate\Users\Services\Registration as RegistrationService;

class Authentication extends BaseController
{
    /**
     * Display the login form.
     */
    public function login()
    {
        $layout = view()->exists('layouts.default')
            ? 'layouts.default'
            : 'layout';

        $pageTitle = 'Login';

        return view('auth.login', compact('layout', 'pageTitle'));
    }

    /**
     * Handle validating the login details.
     *
     * @param \JumpGate\Users\Http\Requests\Login $request
     * @param \JumpGate\Users\Services\Login      $loginService
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleLogin(Login $request, LoginService $loginService)
    {
        // Set up the auth data
        $userData = [
            'email'    => $request->get('email'),
            'password' => $request->get('password'),
        ];

        // Let the login service handle the checks.
        list($success, $message, $route) = $loginService->handle($userData);

        // If the checks passed, redirect them where they were going.
        if ($success) {
            return redirect()
                ->intended(route($route))
                ->with('message', $message);
        }

        // If the checks failed, redirect and let them know why it failed.
        return redirect(route($route))
            ->with('error', $message);
    }

    /**
     * Display the registration form.
     */
    public function register()
    {
        $layout = view()->exists('layouts.default')
            ? 'layouts.default'
            : 'layout';

        $pageTitle = 'Register';

        return view('auth.register', compact('layout', 'pageTitle'));
    }

    /**
     * Handle validating the registration.
     *
     * @param \JumpGate\Users\Http\Requests\Registration $request
     * @param \JumpGate\Users\Services\Registration      $registrationService
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleRegister(Registration $request, RegistrationService $registrationService)
    {
        DB::beginTransaction();

        // Try to register the user.
        try {
            $user = $registrationService->handle();
        } catch (\Exception $exception) {
            DB::rollBack();

            logger()->error($exception);

            return redirect(route('auth.register'))
                ->with('errors', $exception->getMessage());
        }

        DB::commit();

        // If the app requires activation, generate a token and email them.
        if (config('jumpgate.users.require_email_activation')) {
            return redirect(route('auth.activation.generate', $user->id));
        }

        // Log the user in.
        auth()->login($user);

        return redirect(route('home'))
            ->with('message', 'Your account has been created.');
    }

    /**
     * Log the user out.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        auth()->logout();

        return redirect(route('home'))
            ->with('message', 'You have been logged out.');
    }
}
