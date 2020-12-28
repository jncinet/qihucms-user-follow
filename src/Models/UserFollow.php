<?php

namespace Qihucms\UserFollow\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Qihucms\UserFollow\Events\Followed;
use Qihucms\UserFollow\Events\UnFollowed;

class UserFollow extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'user_id', 'to_user_id', 'status'
    ];

    /**
     * @var array
     */
    protected $dispatchesEvents = [
        'created' => Followed::class,
        'deleted' => UnFollowed::class,
    ];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * @return BelongsTo
     */
    public function to_user(): BelongsTo
    {
        return $this->belongsTo('App\Models\User');
    }
}
