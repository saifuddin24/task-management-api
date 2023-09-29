<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    public static $wrap = 'project';

    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {

        $time_format = config('app.default_time_format');

        return [
            'id' => $this->id,
            'title' => $this->title,
            'slogan' => $this->slogan,
            'description' => $this->description,
            'deadline' => $this->deadline,
            'working_days_needed' => $this->working_days_needed,
            'manager_id' => $this->manager_id,
            'created_at' => $this->created_at->format($time_format),
            'updated_at' => $this->updated_at->format($time_format),
            'deleted_at' => $this->deleted_at?->format($time_format),
            'tasks' => TaskCollection::make($this->whenLoaded('tasks')),
            'manager' => UserResource::make($this->whenLoaded('manager')),
            'assigned_teams' => TaskCollection::make($this->whenLoaded('assigned_teams')),
            'assigned_persons' => UserCollection::make($this->whenLoaded('assigned_persons')),
        ];
    }

}
