<?php

namespace JumpGate\Users\Http\Controllers;

use App\Http\Controllers\BaseController;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use JumpGate\Users\Events\UserLoggedIn;
use JumpGate\Users\Events\UserRegistered;
use JumpGate\Users\Http\Requests\Login;
use JumpGate\Users\Http\Requests\Registration;
use JumpGate\Users\Services\Registration as RegistrationService;

class AuthController extends BaseController
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
     * Log the user in
     *
     * @param Login $request
     *
     * @return mixed
     */
    public function handleLogin(Login $request)
    {
        // Set the auth data
        $userData = [
            'email'    => $request->get('email'),
            'password' => $request->get('password'),
        ];

        // Log in successful
        if (auth()->attempt($userData, $request->get('remember', false))) {
            event(new UserLoggedIn(auth()->user(), null));

            return redirect()
                ->intended(route('home'))
                ->with('message', 'You have been logged in.');
        }

        // Login failed
        return redirect(route('auth.login'))
            ->with('errors', ['Your email or password was incorrect.']);
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
     * Register a user
     *
     * @param \JumpGate\Users\Http\Requests\Registration $request
     * @param \JumpGate\Users\Services\Registration      $registrationService
     *
     * @return mixed
     */
    public function handleRegister(Registration $request, RegistrationService $registrationService)
    {
        DB::beginTransaction();

        try {
            $user = $registrationService->handle();

            auth()->login($user);

            event(new UserRegistered(auth()->user()));
        } catch (\Exception $exception) {
            DB::rollBack();

            logger()->error($exception);

            return redirect(route('auth.register'))
                ->with('errors', $exception->getMessage());
        }

        DB::commit();

        return redirect(route('home'))
            ->with('message', 'Your account has been created.');
    }

    /**
     * Log the user out.
     *
     * @return mixed
     */
    public function logout()
    {
        auth()->logout();

        return redirect(route('home'))
            ->with('message', 'You have been logged out.');
    }
}
