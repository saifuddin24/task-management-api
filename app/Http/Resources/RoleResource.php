<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class RoleResource extends JsonResource
{
    public static $wrap = 'role';

    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'key' => Str::kebab($this->name),
            'has_all_permission' =>  $this->is_super_admin,
            'title' => $this->name,
            'users' => UserCollection::make($this->whenLoaded('users')),
        ];
    }
}
