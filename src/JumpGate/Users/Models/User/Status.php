<?php

namespace JumpGate\Users\Models\User;

use App\Models\BaseModel;

class Status extends BaseModel
{
    const ACTIVE = 1;

    const INACTIVE = 2;

    const BLOCKED = 3;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_statuses';

    /**
     * Determines whether Laravel tries to updated created/updated times.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that can be safely filled.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'label',
    ];

    /**
     * A scope to help easily search statuses by a term.
     *
     * @param Builder $query The current query to append to
     * @param array   $filters
     */
    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('label', 'like', '%' . $search . '%');
        });
    }
}
