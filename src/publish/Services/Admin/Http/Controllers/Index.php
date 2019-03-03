<?php

namespace App\Services\Admin\Http\Controllers;

use App\Models\User;

class Index extends Base
{
    public function __invoke()
    {
        $users     = User::orderByNameAsc()->get();
        $userCount = $users->count();
        $userCount .= ' ' . Str::plural('user', $userCount);

        $this->setViewData(compact('users', 'userCount'));

        return $this->view();
    }
}
