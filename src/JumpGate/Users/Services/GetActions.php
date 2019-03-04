<?php

namespace JumpGate\Users\Services;

use App\Models\User;
use JumpGate\Users\Models\User\Status;

class GetActions
{
    /**
     * @var \App\Models\User
     */
    public $user;

    /**
     * @var \JumpGate\Database\Collections\SupportCollection
     */
    public $actions;

    public function __construct(User $user)
    {
        $this->user    = $user;
        $this->actions = supportCollector();
    }

    /**
     * Get the list of available actions.
     *
     * @return \JumpGate\Database\Collections\SupportCollection
     */
    public function build()
    {
        if ($this->user->trashed()) {
            return $this->actions;
        }

        $this->checkBlocked();
        $this->checkInactive();
        $this->checkInvites();
        $this->checkPasswordReset();

        return $this->actions;
    }

    /**
     * Actions for a blocked user.
     */
    protected function checkBlocked()
    {
        if ($this->user->status_id === Status::BLOCKED) {
            return $this->addAction('Un-Block', 'unlock', $this->makeRoute('block', 0));
        }

        return $this->addAction('Block', 'lock', $this->makeRoute('block', 1));
    }

    /**
     * Actions for an inactive user.
     *
     * @return bool|void
     */
    protected function checkInactive()
    {
        if ($this->user->status_id !== Status::INACTIVE) {
            return true;
        }

        return $this->addAction('Activate', 'check-square-o', $this->makeRoute('activate', 0));
    }

    /**
     * Actions for an invited user.
     *
     * @return bool|void
     */
    protected function checkInvites()
    {
        if (is_null($this->user->actionTimestamps->invited_at)) {
            return true;
        }

        return $this->addAction('Re-Send Invite', 'envelope-o', $this->makeRoute('resendInvite'));

        return $this->addAction('Revoke Invite', 'times-circle-o', $this->makeRoute('revokeInvite'));
    }

    /**
     * Possible reset password action.
     *
     * @return bool|void
     */
    protected function checkPasswordReset()
    {
        if (config('jumpgate.users.social_auth_only')) {
            return true;
        }

        return $this->addAction('Reset Password', 'refresh', $this->makeRoute('resetPassword'));
    }

    /**
     * A helper to make routes easily.
     *
     * @param string   $status
     * @param null|int $action
     *
     * @return string
     */
    protected function makeRoute($status, $action = null)
    {
        $route = 'admin.users.confirm';

        return route($route, [$this->user->id, $status, $action]);
    }

    /**
     * Adds the action to the collection.
     *
     * @param string $label
     * @param string $icon
     * @param string $route
     */
    protected function addAction($label, $icon, $route)
    {
        $action = (object)[
            'route' => $route,
            'icon'  => 'fa-' . $icon,
            'text'  => $label,
        ];

        $this->actions->add($action);
    }
}
