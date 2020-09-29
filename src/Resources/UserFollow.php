<?php

namespace Qihucms\UserFollow\Resources;

use App\Http\Resources\User\User;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class UserFollow extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user' => new User($this->user),
            'status' => $this->status,
            'created_at' => Carbon::parse($this->created_at)->diffForHumans()
        ];
    }
}
