<?php

namespace App\Services\Admin\Http\Controllers;

use JumpGate\Users\Services\UserActions;
use App\Models\User;
use JumpGate\Users\Models\Role;
use JumpGate\Users\Models\User\Status;

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
        $users = $this->users->orderByNameAsc()->withTrashed()->paginate(15);

        $this->setViewData(compact('users'));

        return $this->view();
    }

    public function create()
    {
        $roles = Role::orderBy('name', 'asc')->get()->pluck('name', 'id');

        $invitationOptions = supportCollector([0 => 'Select one']);
        $settings          = config('jumpgate.users.settings');

        if (array_get($settings, 'allow_invitations')) {
            $invitationOptions->put('inviteNewUser', 'Send invite');
        }
        if (array_get($settings, 'require_email_activation')) {
            $invitationOptions->put('sendNewUserActivation', 'Send email activation');
        }
        if (config('jumpgate.users.social_auth_only')) {
            $invitationOptions->put('generateSocialOnlyUser', 'Just add user to database');
        }

        $selected = null;
        if ($invitationOptions->count() === 2) {
            $selected = $invitationOptions->keys()->last();
        }

        $this->setViewData(compact('roles', 'invitationOptions', 'selected'));

        return $this->view();
    }

    public function store()
    {
        $email        = request('email');
        $roles        = request('roles');
        $inviteMethod = request('inviteMethod');

        if (empty($roles)) {
            return redirect()
                ->route('admin.users.create')
                ->withInput()
                ->with('message', 'You must select at least one role.');
        }

        $this->users->{$inviteMethod}($email, $roles);

        return redirect()
            ->route('admin.users.index')
            ->with('message', 'User added!');
    }

    public function edit($id)
    {
        $user     = $this->users->find($id);
        $statuses = Status::orderByNameAsc()->get()->pluck('label', 'id');
        $roles    = Role::orderBy('name', 'asc')->get()->pluck('name', 'id');

        $this->setViewData(compact('user', 'statuses', 'roles'));

        return $this->view();
    }

    public function update($id)
    {
        $user = $this->users->find($id);

        $user->update(request('user'));
        $user->setStatus(request('status_id'));
        $user->setFailedLoginAttempts(request('failed_login_attempts'));
        $user->details->update(request('details'));
        $user->roles()->sync(request('roles'));

        return redirect()
            ->route('admin.users.index')
            ->with('message', 'User updated!');
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
