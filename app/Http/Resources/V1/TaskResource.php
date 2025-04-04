<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->task_status?->name,
            'code' => $this->task_status?->code,
            'task_status_id' => $this->task_status_id,
            'created_date' => $this->created_at->format('Y-m-d'),
            'user_id' => $this->when($this->user_id, $this->user_id),
        ];
    }
}
