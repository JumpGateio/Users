<?php

namespace JumpGate\Users\Models\User;

use App\Models\BaseModel;
use App\Models\User;
use Carbon\Carbon;
use JumpGate\Users\Notifications\PasswordReset;
use JumpGate\Users\Notifications\UserActivation;
use JumpGate\Users\Notifications\UserInvitation;

/**
 * Class Token
 *
 * @package JumpGate\Users\Models\User
 *
 * @property int              $user_id
 * @property string           $type
 * @property string           $token
 * @property \Carbon\Carbon   $created_at
 * @property \Carbon\Carbon   $updated_at
 * @property \Carbon\Carbon   $expires_at
 * @property \App\Models\User $user
 */
class Token extends BaseModel
{
    /**
     * Activation type token.
     *
     * @var string
     */
    const TYPE_ACTIVATION = 'activation';

    /**
     * Invitation type token.
     *
     * @var string
     */
    const TYPE_INVITATION = 'invitation';

    /**
     * Password reset type token.
     *
     * @var string
     */
    const TYPE_PASSWORD_RESET = 'password_reset';

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_tokens';

    /**
     * The attributes that can be safely filled.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'type',
        'token',
        'expires_at',
    ];

    /**
     * The attributes that should be mutated to Carbon dates.
     *
     * @var array
     */
    protected $dates = [
        'expires_at',
    ];

    /**
     * Generate a new token for the type.
     *
     * @param string           $type
     * @param \App\Models\User $user
     * @param null|int         $hours
     *
     * @return $this
     */
    public function generate($type, User $user, $hours = null)
    {
        $hours = is_null($hours) ? 24 : $hours;

        $user_id    = $user->id;
        $email      = $user->getAuthIdentifier();
        $value      = str_shuffle(sha1($email . $type . spl_object_hash($this) . microtime(true)));
        $token      = hash_hmac('sha256', $value, config('app.key'));
        $expires_at = Carbon::now()->addHours($hours);

        return $this->create(compact('user_id', 'type', 'token', 'expires_at'));
    }

    /**
     * Returns true if token is expired.
     *
     * @return boolean
     */
    public function getIsExpiredAttribute()
    {
        return $this->isExpired();
    }

    /**
     * Returns true if token is expired.
     *
     * @return boolean
     */
    public function isExpired()
    {
        return $this->expires_at <= Carbon::now();
    }

    /**
     * Notify the user of this token.
     */
    public function notifyUser()
    {
        switch ($this->type) {
            case self::TYPE_PASSWORD_RESET:
                $event = new PasswordReset;
                break;
            case self::TYPE_ACTIVATION:
                $event = new UserActivation;
                break;
            case self::TYPE_INVITATION:
                $event = new UserInvitation;
                break;
        }

        $this->user->notify($event);
    }

    /**
     * Extend the expiration of the token.
     *
     * @param null|int $hours
     */
    public function extend($hours = null)
    {
        $hours = is_null($hours) ? 24 : $hours;

        $this->update([
            'expires_at' => Carbon::now()->addHours($hours),
        ]);
    }

    /**
     * Expire the token immediately.
     */
    public function expire()
    {
        $this->update([
            'expires_at' => Carbon::now(),
        ]);
    }

    /**
     * Return a token object that contains the given token.
     *
     * @param string $token
     *
     * @return null|\JumpGate\Users\Models\User\Token
     */
    public function findByToken($token)
    {
        return $this->where('token', $token)->first();
    }

    /**
     * Delete a token object that contains the given token.
     *
     * @param string $token
     *
     * @return null|\JumpGate\Users\Models\User\Token
     * @throws \Exception
     */
    public function deleteByToken($token)
    {
        return $this->where('token', $token)->delete();
    }

    /**
     * Extend the expiration of a token.
     *
     * @param string $token
     * @param null   $hours
     */
    public function extendByToken($token, $hours = null)
    {
        $token = $this->findByToken($token);

        if (! is_null($token)) {
            return $token->extend($hours);
        }
    }

    /**
     * Find an object by its token and immediately expire it.
     *
     * @param string $token
     */
    public function expireByToken($token)
    {
        $token = $this->findByToken($token);

        if (! is_null($token)) {
            return $token->expire();
        }
    }

    /**
     * Builds a query scope to return tokens of a certain type.
     *
     * @param \Illuminate\Database\Query\Builder $query
     * @param string|array                       $types of tokens
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopeOfType($query, $types)
    {
        // Convert types string to array
        $types = (array)$types;

        // Query the tokens table for matching types
        return $query->whereIn('type', $types);
    }

    /**
     * Every token belongs to a user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
