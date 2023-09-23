<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserRoleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'role_id' => $this->role_id,
            'user_id' => $this->user_id,
            'users' => UserCollection::make($this->whenLoaded('users')),
        ];
    }
}
