<?php

namespace App\Services\Api\V1\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface TaskStatusServiceInterface
{
    /**
     * Get all the statuses.
     *
     * @return Collection
     */
    public function getAllStatuses(): Collection;
}
