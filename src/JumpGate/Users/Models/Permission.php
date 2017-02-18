<?php

namespace JumpGate\Users\Models;

use App\Models\BaseModel;

class Permission extends BaseModel
{
    public $table = 'rbac_permissions';

    /**
     * A permission belongs to multiple groups.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function groups()
    {
        return $this->belongsToMany(Group::class, 'rbac_group_permission');
    }
}
