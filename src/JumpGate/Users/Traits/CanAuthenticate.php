<?php

namespace JumpGate\Users\Traits;

trait CanAuthenticate
{
    /**
     * Track the log in details for this user.
     *
     * @param null|string $type The way they tried to log in.
     */
    public function updateLogin($type = null)
    {
        $this->authenticated_as      = $type;
        $this->authenticated_at      = setTime('now');
        $this->failed_login_attempts = 0;
        $this->save();
    }

    /**
     * When a log in fails, increment the failed count.
     *
     * @param string $email The email the user tried to log in as.
     */
    public static function failedLogin($email)
    {
        $user = self::where('email', $email)->first();

        if (! is_null($user)) {
            $user->increment('failed_login_attempts');
        }
    }
}
