<?php

namespace JumpGate\Users\Models;

use JumpGate\Users\Traits\ConvertsToCollection;
use Laratrust\Models\LaratrustRole;

class Role extends LaratrustRole
{
    use ConvertsToCollection;
}
