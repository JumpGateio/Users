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

    public function block($id)
    {
        $this->users->find($id)->block();

        return redirect()
            ->route('admin.users.index')
            ->with('message', 'User blocked!');
    }

    public function unblock($id)
    {
        $this->users->find($id)->unblock();

        return redirect()
            ->route('admin.users.index')
            ->with('message', 'User un-blocked!');
    }

    public function delete($id)
    {
        $this->users->destroy($id);

        return redirect()
            ->route('admin.users.index')
            ->with('message', 'User deleted!');
    }
}
