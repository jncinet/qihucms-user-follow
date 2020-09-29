<?php

namespace Qihucms\UserFollow\Events;

use Qihucms\UserFollow\Models\UserFollow;

class Event
{
    protected $follow;

    public function __construct(UserFollow $follow)
    {
        $this->follow = $follow;
    }
}