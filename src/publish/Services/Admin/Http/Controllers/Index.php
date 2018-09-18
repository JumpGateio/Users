<?php

namespace App\Services\Admin\Http\Controllers;

use App\Models\User;

class Index extends Base
{
    public function __invoke()
    {
        $users = User::count();

        $this->setViewData(compact('users'));

        return $this->view();
    }
}
