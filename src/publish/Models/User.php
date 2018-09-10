<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use JumpGate\Users\Models\User as BaseUser;

class User extends BaseUser
{
    use Notifiable;
}
