<?php

namespace JumpGate\Users\Models\User;

use App\Models\BaseModel;

class Status extends BaseModel
{
    const ACTIVE = 1;

    const INACTIVE = 2;

    const BLOCKED = 3;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_statuses';

    /**
     * The attributes that can be safely filled.
     *
     * @var array
     */
    protected $fillable = [];
}
