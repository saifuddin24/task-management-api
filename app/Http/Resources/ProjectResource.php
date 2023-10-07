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

        $data = [
            'id' => $this->id,
            'title' => $this->title,
            'slogan' => $this->slogan,
            'description' => $this->description,
            'working_days_needed' => $this->working_days_needed,
            'manager_id' => $this->manager_id,
            'deadline' => $this->deadline ? $this->deadline->format('D d M Y'):'',
            'created_at' => $this->created_at->format($time_format),
            'updated_at' => $this->updated_at->format($time_format),
            'deleted_at' => $this->deleted_at?->format($time_format),
            'tasks' => TaskCollection::make($this->whenLoaded('tasks')),
            'manager' => UserResource::make($this->whenLoaded('manager')),
            'assigned_teams' => TaskCollection::make($this->whenLoaded('assigned_teams')),
            'assigned_persons' => UserCollection::make($this->whenLoaded('assigned_persons')),
        ];


        if( $request->has('with-raw-dates') ) {
            $data['deadline_raw'] = $this->deadline;
            $data['created_at_raw'] = $this->created_at;
            $data['updated_at_raw'] = $this->updated_at;
            $data['deleted_at_raw'] = $this->deleted_at;
        }

        return $data;
    }

}
