<?php

namespace JumpGate\Users\Services;

use App\Models\User;
use JumpGate\Core\Services\Response;

class UserActions
{
    /**
     * @var \App\Models\User
     */
    public $user;

    /**
     * @var string
     */
    public $status;

    /**
     * @var int
     */
    public $action;

    /**
     * @var string
     */
    public $method;

    public function __construct(User $user, $status, $action)
    {
        $this->user   = $user;
        $this->status = $status;
        $this->action = (int)$action;
    }

    /**
     * Determine what to do on the user.
     *
     * @return \JumpGate\Core\Services\Response
     */
    public function execute()
    {
        $this->determineMethod();

        $this->user->{$this->method}();

        return Response::passed($this->getMessage())
            ->route('admin.users.index');
    }

    /**
     * Based on the status and action, determine the method
     * to call on the user object.
     */
    protected function determineMethod()
    {
        $methods = [
            'block'         => ['unblock', 'block'],
            'delete'        => ['restore', 'delete'],
            'activate'      => ['activate'],
            'resetPassword' => ['resetPassword'],
            'resendInvite'  => ['resendInvite'],
            'revokeInvite'  => ['revokeInvite'],
        ];

        $this->method = array_get($methods, $this->status . '.' . $this->action);
    }

    /**
     * Based on the method, determine a logic message
     * to display when redirecting.
     *
     * @return string
     */
    protected function getMessage()
    {
        switch ($this->method) {
            case 'resendInvite':
                return 'Invite resent.';
            case 'revokeInvite':
                return 'Invite revoked.';
            case 'resetPassword':
                return 'Password reset.';
            case 'block':
            case 'unblock':
                return 'User '. $this->method .'ed.';
            default:
                return 'User '. $this->method .'d.';
        }
    }
}
