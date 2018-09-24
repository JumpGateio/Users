<?php

namespace JumpGate\Users\Traits;

use App\Models\User;

trait HasUser
{
    /**
     * Limit our results to those belonging to a user.
     *
     * @param \Illuminate\Database\Query\Builder $query
     * @param int                                $userId
     */
    public function scopeForUser($query, $userId)
    {
        $query->where('user_id', $userId);
    }

    /**
     * Get the user this record is associated with.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
