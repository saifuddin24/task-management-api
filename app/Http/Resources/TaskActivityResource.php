<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskActivityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'progress_percentage' => $this->progress_percentage,
            'task_id' => $this->task_id,
            'created_at' => $this->created_at,
            'finished_at' => $this->finished_at,
        ];
    }
}
