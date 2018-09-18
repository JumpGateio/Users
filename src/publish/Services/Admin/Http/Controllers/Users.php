<?php

namespace App\Services\Admin\Http\Controllers;

use App\Models\User;

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

    public function create()
    {
        return $this->view();
    }

    public function store()
    {
        $this->users->inviteNewUser(request('email'), 'guest');

        return redirect()
            ->route('admin.users.index')
            ->with('message', 'User added!');
    }

    public function delete($id)
    {
        $this->users->destroy($id);

        return redirect()
            ->route('admin.users.index')
            ->with('message', 'User deleted!');
    }
}
