<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskActivityResource extends JsonResource
{
    public static $wrap = 'activity';
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'progress_percentage' => $this->progress_percentage,
            'task_id' => $this->task_id,
            'created_at' => $this->created_at?->format(config('app.default_time_format')),
            'finished_at' => $this->finished_at?->format(config('app.default_time_format')),
            'related' => $this->whenLoaded('assign' ),
            'task' => TaskResource::make(
                $this->whenLoaded('task')
            ),
        ];
    }
}
