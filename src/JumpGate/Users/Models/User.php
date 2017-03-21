<?php

namespace JumpGate\Users\Models;

use App\Models\BaseModel;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;
use JumpGate\Users\Models\User\Detail;
use JumpGate\Users\Traits\CanActivate;
use JumpGate\Users\Traits\CanAuthenticate;
use JumpGate\Users\Traits\CanResetPassword;
use JumpGate\Users\Traits\HasTokens;
use Kodeine\Acl\Traits\HasRole;

abstract class User extends BaseModel implements AuthenticatableContract, AuthorizableContract
{
    /**
     * Use this model for authentication.
     *
     * @see \Illuminate\Auth\Authenticatable
     */
    use Authenticatable;

    /**
     * Allow this model to use authorization.
     *
     * @see \Illuminate\Foundation\Auth\Access\Authorizable
     */
    use Authorizable;

    /**
     * Allow this model to handle being activated.
     *
     * @see \JumpGate\Users\Traits\CanActivate
     */
    use CanActivate;

    /**
     * Allow this model to handle logging in.
     *
     * @see \JumpGate\Users\Traits\CanAuthenticate
     */
    use CanAuthenticate;

    /**
     * Allow this model to reset it's password.
     *
     * @see \JumpGate\Users\Traits\CanResetPassword
     */
    use CanResetPassword;

    /**
     * Allow this model to have roles and permissions.
     *
     * @see \Kodeine\Acl\Traits\HasRole
     */
    use HasRole;

    /**
     * Allow this model to generate and use tokens.
     *
     * @see \JumpGate\Users\Traits\HasTokens
     */
    use HasTokens;

    /**
     * Allow this model to receive notifications.
     *
     * @see \Illuminate\Notifications\Notifiable
     */
    use Notifiable;

    /**
     * Make model use soft deletes.
     *
     * @see \Illuminate\Database\Eloquent\SoftDeletes
     */
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that can be safely filled.
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'password',
    ];

    /**
     * Tell eloquent to set deleted_at as a carbon date.
     *
     * @var array
     */
    protected $dates = [
        'deleted_at',
    ];

    /**
     * Order by name ascending scope
     *
     * @param Builder $query The current query to append to
     *
     * @return Builder
     */
    public function scopeOrderByNameAsc($query)
    {
        return $query->orderBy('email', 'asc');
    }

    /**
     * Make sure to hash the user's password on save
     *
     * @param string $value The value of the attribute (Auto Set)
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    /**
     * Get the most logical display name for the user.
     *
     * @return string
     */
    public function getDisplayNameAttribute()
    {
        if (! is_null($this->details->display_name)) {
            return $this->details->display_name;
        }

        if (! is_null($this->details->full_name)) {
            return $this->details->full_name;
        }

        return $this->email;
    }

    /**
     * Set the user's status.
     *
     * @param int $status The ID of the status being set.
     */
    public function setStatus($status)
    {
        $this->status_id = $status;
        $this->save();
    }

    /**
     * Track when something important happened.
     *
     * @param string $column The column being updated.
     */
    public function trackTime($column)
    {
        $this->{$column} = setTime('now');
        $this->save();
    }

    /**
     * Extra details for a user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function details()
    {
        return $this->hasOne(Detail::class, 'user_id');
    }
}
