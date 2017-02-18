<?php

namespace JumpGate\Users\Models;

use App\Models\BaseModel;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\Access\Authorizable;
use JumpGate\Users\Models\User\Detail;
use JumpGate\Users\Traits\HasGroups;

abstract class User extends BaseModel implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword, HasGroups, SoftDeletes;

    /**
     * Define the SQL table for this model
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

    public function details()
    {
        return $this->hasOne(Detail::class, 'user_id');
    }
}
