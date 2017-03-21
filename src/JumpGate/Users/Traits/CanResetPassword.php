<?php

namespace JumpGate\Users\Traits;

trait CanResetPassword
{
    public function resetPassword($password)
    {
        // Remove the activation token.
        $this->getPasswordResetToken()->delete();

        // Update the user table with the needed details.
        $this->password            = $password;
        $this->password_updated_at = setTime('now');
        $this->save();
    }
}
