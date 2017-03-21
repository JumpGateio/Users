<?php

namespace JumpGate\Users\Models;

use JumpGate\Database\Collections\EloquentCollection;
use Kodeine\Acl\Models\Eloquent\Role as BaseRole;

class Role extends BaseRole
{
    /**
     * Use the custom collection that allows tapping.
     *
     * @param array $models An array of models to turn into a collection.
     *
     * @return \Illuminate\Database\Eloquent\Collection OR \JumpGate\Database\Collections\EloquentCollection
     */
    public function newCollection(array $models = [])
    {
        if (app(config('auth.providers.users.model'))->jumpGateCollections) {
            return new EloquentCollection($models);
        }

        return parent::newCollection($models);
    }
}
