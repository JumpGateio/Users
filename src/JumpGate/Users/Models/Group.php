<?php

namespace JumpGate\Users\Models;

use App\Models\BaseModel;

class Group extends BaseModel
{
    public $table = 'rbac_groups';

    /**
     * A group may be given various permissions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'rbac_group_permission');
    }

    /**
     * A group contains many users.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(config('auth.providers.users.model'), 'rbac_group_user');
    }

    /**
     * Grant the given permission to a group.
     *
     * @param  Permission $permission
     * @return mixed
     */
    public function givePermissionTo(Permission $permission)
    {
        return $this->permissions()->save($permission);
    }
}
