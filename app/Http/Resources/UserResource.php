<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public static $wrap = 'user';
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        $time_format = config('app.default_time_format');

        return [
            'id' => $this->id,
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
            'created_at' => $this->created_at->format($time_format),
            'updated_at' => $this->updated_at ? $this->updated_at->format($time_format):null,
            'email_verified_at' => $this->email_verified_at ? $this->email_verified_at->format($time_format):null,
            'primary_role' => RoleResource::make($this->whenLoaded('primary_role')),
            'roles' => RoleCollection::make($this->whenLoaded('roles')),
            'permissions' => PermissionCollection::make($this->whenLoaded('permissions')),
            'projects' => ProjectCollection::make($this->whenLoaded('projects')),
        ];
    }
}