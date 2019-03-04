<?php

namespace App\Services\Admin\Http\Controllers;

use App\Models\User;
use JumpGate\Users\Services\UserActions;

class Users extends Base
{
    /**
     * @var \App\Models\User
     */
    public $users;

    public function __construct(User $users)
    {
        parent::__construct();
        
        $this->users = $users;
    }

    public function index()
    {
        $users = $this->users->orderByNameAsc()->paginate(15);

        $this->setViewData(compact('users'));

        return $this->view();
    }

    public function confirm($id, $status, $action = null)
    {
        $user = $this->users->withTrashed()->find($id);

        switch ($status) {
            case 'resendInvite':
                $event = 'resend an invite to';
                break;
            case 'revokeInvite':
                $event = 'revoke the invite to';
                break;
            case 'resetPassword':
                $event = 'reset the password of ';
                break;
            default:
                $event = $status;
                break;
        }

        if (! is_null($action) && (int)$action === 0) {
            $event = 'un-' . $event;
        }

        $message = 'You are about to ' . $event . ' ' . $user->email . '.';

        $this->setViewData(compact('message'));

        return $this->view();
    }

    public function confirmed($id, $status, $action = null)
    {
        $user = $this->users->withTrashed()->find($id);

        return (new UserActions($user, $status, $action))
            ->execute()
            ->redirect();
    }
}
