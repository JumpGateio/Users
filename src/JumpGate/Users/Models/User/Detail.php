<?php

namespace JumpGate\Users\Models\User;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class Detail extends BaseModel
{
    use SoftDeletes;

    protected $table = 'user_details';

    protected $fillable = [
        'user_id',
        'first_name',
        'middle_name',
        'last_name',
        'display_name',
        'timezone',
        'location',
        'url',
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
     * Get the user's full name based on the available names.
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        return implode(' ', array_filter([
            $this->first_name,
            $this->middle_name,
            $this->last_name,
        ]));
    }

    /**
     * @return mixed
     */
    public function user()
    {
        return $this->belongsTo(config('auth.providers.users.model'), 'user_id');
    }
}
