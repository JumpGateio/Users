<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use JumpGate\Users\Models\User as BaseUser;

class User extends BaseUser
{
    use Notifiable;

    /**
     * first_name column should be used for sorting when name column is selected in Datatables.
     *
     * @return string
     */
    public static function laratablesOrderName()
    {
        return 'email';
    }
}
