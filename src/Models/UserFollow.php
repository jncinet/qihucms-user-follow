<?php

namespace Qihucms\UserFollow\Models;

use Illuminate\Database\Eloquent\Model;
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function to_user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
