<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use JumpGate\Users\Models\User as BaseUser;
use JumpGate\Users\Traits\HasSocials;

class User extends BaseUser
{
    use Notifiable;

    use HasSocials;
}
