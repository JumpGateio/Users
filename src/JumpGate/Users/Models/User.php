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
use JumpGate\Users\Models\User\Status;
use JumpGate\Users\Models\User\Timestamp;
use JumpGate\Users\Services\GetActions;
use JumpGate\Users\Traits\CanActivate;
use JumpGate\Users\Traits\CanAuthenticate;
use JumpGate\Users\Traits\CanBlock;
use JumpGate\Users\Traits\CanInvite;
use JumpGate\Users\Traits\CanResetPassword;
use JumpGate\Users\Traits\HasGravatar;
use JumpGate\Users\Traits\HasTokens;
use Laratrust\Traits\LaratrustUserTrait;

/**
 * Class User
 *
 * @package JumpGate\Users\Models
 *
 * @property int                                   $id
 * @property string                                $email
 * @property string                                $password
 * @property int                                   $status_id
 * @property int                                   $failed_login_attempts
 * @property string                                $authenticated_as
 * @property \Carbon\Carbon                        $authenticated_at
 * @property \Carbon\Carbon                        $activated_at
 * @property \Carbon\Carbon                        $blocked_at
 * @property \Carbon\Carbon                        $password_updated_at
 * @property string                                $remember_token
 * @property \Carbon\Carbon                        $created_at
 * @property \Carbon\Carbon                        $updated_at
 * @property \Carbon\Carbon                        $deleted_at
 *
 * @property \JumpGate\Users\Models\Role[]         $roles
 * @property \JumpGate\Users\Models\Permission[]   $permissions
 * @property \JumpGate\Users\Models\User\Detail    $details
 * @property \JumpGate\Users\Models\User\Timestamp $actionTimestamps
 */
class User extends BaseModel implements AuthenticatableContract, AuthorizableContract
{
    /**
     * Use this model for authentication.
     *
     * @see \Illuminate\Auth\Authenticatable
     */
    use Authenticatable;

    /**
     * Allow this model to handle being activated.
     *
     * @see \JumpGate\Users\Traits\CanActivate
     */
    use CanActivate;

    /**
     * Allow this model to handle invites.
     *
     * @see \JumpGate\Users\Traits\CanInvite
     */
    use CanInvite;

    /**
     * Allow this model to handle logging in.
     *
     * @see \JumpGate\Users\Traits\CanAuthenticate
     */
    use CanAuthenticate;

    /**
     * Allow this model to be blocked or un-blocked from authenticating.
     *
     * @see \JumpGate\Users\Traits\CanBlock
     */
    use CanBlock;

    /**
     * Allow this model to reset it's password.
     *
     * @see \JumpGate\Users\Traits\CanResetPassword
     */
    use CanResetPassword;

    /**
     * Allow this model to have roles and permissions.
     *
     * @see \Laratrust\Traits\LaratrustUserTrait
     */
    use LaratrustUserTrait;

    /**
     * Allow this model to generate and use tokens.
     *
     * @see \JumpGate\Users\Traits\HasTokens
     */
    use HasTokens;

    /**
     * Allow this model to display a gravatar avatar.
     *
     * @see \JumpGate\Users\Traits\HasGravatar
     */
    use HasGravatar;

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
        'authenticated_at',
        'deleted_at',
        'password_updated_at',
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
     * Returns a list of objects that will go in a users
     * drop down menu for actions that can be taken.
     *
     * @return \JumpGate\Database\Collections\SupportCollection
     */
    public function getAdminActionsAttribute()
    {
        return (new GetActions($this))->build();
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
     * Set the user's failed login attempts.
     *
     * @param int $attempts The number of attempts to set it to.
     */
    public function setFailedLoginAttempts($attempts)
    {
        $this->failed_login_attempts = $attempts;
        $this->save();
    }

    /**
     * Track when something important happened.
     *
     * @param string              $column The column being updated.
     * @param null|\Carbon\Carbon $time   The time to set the token for.
     */
    public function trackTime($column, $time = null)
    {
        $search = [
            'user_id' => $this->id,
        ];

        $attributes = [
            $column => setTime('now'),
        ];

        Timestamp::updateOrCreate($search, $attributes);
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

    /**
     * Important timestamps for a user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function actionTimestamps()
    {
        return $this->hasOne(Timestamp::class, 'user_id');
    }

    /**
     * Status for a user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }
}
