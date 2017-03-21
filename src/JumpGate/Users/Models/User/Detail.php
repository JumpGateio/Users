<?php

namespace JumpGate\Users\Models\User;

use App\Models\BaseModel;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;

class Detail extends BaseModel
{
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_details';

    /**
     * The attributes that can be safely filled.
     *
     * @var array
     */
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
     * The attributes that should be mutated to Carbon dates.
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
        $names = array_filter([
            $this->first_name,
            $this->middle_name,
            $this->last_name,
        ]);

        if (empty($names)) {
            return null;
        }

        return implode(' ', $names);
    }

    /**
     * All details belong to a user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
