<?php

namespace JumpGate\Users\Traits;

use JumpGate\Users\Models\Permission;
use JumpGate\Users\Models\Group;

trait HasGroups
{
    /**
     * A user may have multiple groups.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function groups()
    {
        return $this->belongsToMany(Group::class, 'rbac_group_user');
    }

    /**
     * Assign the given group to the user.
     *
     * @param  string $group
     *
     * @return mixed
     * @throws \Exception
     */
    public function assignGroup($group)
    {
        if (Group::count() === 0) {
            throw new \Exception('No groups have been created.');
        }

        return $this->groups()->save(
            Group::whereName($group)->firstOrFail()
        );
    }

    /**
     * Determine if the user is inthe given group.
     *
     * @param  mixed $group
     * @return bool
     */
    public function hasGroup($group)
    {
        if (is_string($group)) {
            return $this->groups->contains('name', $group);
        }

        return ! ! $group->intersect($this->groups)->count();
    }

    /**
     * Determine if the user may perform the given permission.
     *
     * @param  Permission $permission
     * @return bool
     */
    public function hasPermission(Permission $permission)
    {
        return $this->hasGroup($permission->roles);
    }
}
