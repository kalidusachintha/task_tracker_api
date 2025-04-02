<?php

namespace App\Services\Api\V1\Interfaces;

use App\DTO\TaskDTO;
use App\Models\Task;
use Illuminate\Pagination\LengthAwarePaginator;

interface TaskServiceInterface
{
    /**
     * Get all the tasks.
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getAllTasks(int $perPage = 15): LengthAwarePaginator;

    /**
     * Get a task by ID.
     *
     * @param int $id
     * @return Task
     */
    public function getTaskById(int $id): Task;

    /**
     * Create a new task.
     *
     * @param TaskDTO $data
     * @return Task
     */
    public function createTask(TaskDTO $data): Task;

    /**
     * Update an existing task.
     *
     * @param int $id
     * @param TaskDTO $data
     * @return int
     */
    public function updateTask(int $id, TaskDTO $data): int;

    /**
     * Delete a task.
     *
     * @param int $id
     * @return int
     */
    public function deleteTask(int $id): int;
}
