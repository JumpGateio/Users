<?php

namespace JumpGate\Users\Http\Controllers;

use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\DB;
use JumpGate\Users\Http\Requests\Registration as RegistrationRequest;
use JumpGate\Users\Services\Registration as RegistrationService;

class Registration extends BaseController
{
    /**
     * @var \JumpGate\Users\Services\Registration
     */
    private $registration;

    /**
     * @param \JumpGate\Users\Services\Registration $registration
     */
    public function __construct(RegistrationService $registration)
    {
        $this->registration = $registration;
    }

    /**
     * Display the registration form.
     */
    public function index()
    {
        $pageTitle = 'Register';

        return $this->response(
            compact('pageTitle'),
            'auth.register'
        );
    }

    /**
     * Handle validating the registration.
     *
     * @param \JumpGate\Users\Http\Requests\Registration $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handle(RegistrationRequest $request)
    {
        DB::beginTransaction();

        // Try to register the user.
        try {
            $user = $this->registration->registerUser();
        } catch (\Exception $exception) {
            DB::rollBack();

            logger()->error($exception);

            return redirect()->route('auth.register')
                ->with('errors', $exception->getMessage());
        }

        DB::commit();

        // If the app requires activation, generate a token and email them.
        if (config('jumpgate.users.require_email_activation')) {
            return redirect()->route('auth.activation.generate', $user->id);
        }

        // Log the user in.
        auth()->login($user);

        return redirect()
            ->route('home')
            ->with('message', 'Your account has been created.');
    }
}
