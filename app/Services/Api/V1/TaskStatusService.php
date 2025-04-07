<?php

namespace App\Services\Api\V1;

use App\Models\TaskStatus;
use App\Services\Api\V1\Interfaces\TaskStatusServiceInterface;
use Illuminate\Database\Eloquent\Collection;

readonly class TaskStatusService implements TaskStatusServiceInterface
{
    /**
     * @param TaskStatus $taskStatus
     */
    public function __construct(
        private TaskStatus $taskStatus
    ) {}

    /**
     * Get all statuses.
     *
     * @return Collection
     */
    public function getAllStatuses(): Collection
    {
        return $this->taskStatus->orderByDesc('id')->get();
    }
}
