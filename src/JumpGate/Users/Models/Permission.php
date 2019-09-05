<?php

namespace JumpGate\Users\Models;

use JumpGate\Users\Traits\ConvertsToCollection;
use Laratrust\Models\LaratrustPermission;

class Permission extends LaratrustPermission
{
    use ConvertsToCollection;
}
