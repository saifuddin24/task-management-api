<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    public static $wrap = 'task';

    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        $progress_percentage = 0;

        if( $this->resource->relationLoaded('task_activities') ) {
            $progress_percentage = (int) $this->resource->task_activities
                                            ->max( 'progress_percentage' );
        } else {
            $progress_percentage = $this->progress_percentage ?? 0;
        }

        return [
            'id' => $this->id,
            'title' => $this->title,
            'deadline' => $this->deadline?->format(config('app.default_time_format')),
            'description' => $this->description,
            'employee_id' => $this->employee_id,
            'project_id' => $this->project_id,
            'created_at' => $this->created_at?->format(config('app.default_time_format')),
            'updated_at' => $this->updated_at?->format(config('app.default_time_format')),
            'progress_percentage' => $progress_percentage,
            'activities' => TaskActivityCollection::make(
                $this->whenLoaded('task_activities')
            ),
            'project' => ProjectResource::make(
                $this->whenLoaded('project')
            ),
            'employees' => UserCollection::make(
                $this->whenLoaded('employees')
            ),
            'manager' => UserResource::make(
                $this->whenLoaded('manager')
            ),

        ];
    }
}
