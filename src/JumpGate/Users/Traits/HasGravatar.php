<?php

namespace JumpGate\Users\Traits;

trait HasGravatar
{
    /**
     * Generate a gravatar from a user's email.
     *
     * @return string
     */
    public function getGravatarAttribute()
    {
        $emailHash = md5(strtolower(trim($this->email)));

        return 'https://www.gravatar.com/avatar/' . $emailHash;
    }
}
