<?php

namespace App\Services\Api\V1;

use App\DTO\TaskDTO;
use App\Models\Task;
use App\Services\Api\V1\Interfaces\TaskServiceInterface;
use Illuminate\Pagination\LengthAwarePaginator;

readonly class TaskService implements TaskServiceInterface
{
    /**
     * @param Task $task
     */
    public function __construct(
        private Task $task
    ) {}

    /**
     * Get all the tasks.
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getAllTasks(int $perPage): LengthAwarePaginator
    {
        return $this->task->with('task_status')->orderByDesc('id')->paginate($perPage);
    }

    /**
     * Get a task by ID.
     *
     * @param int $id
     * @return Task
     */
    public function getTaskById(int $id): Task
    {
        return $this->task->findOrFail($id);
    }

    /**
     * Create a new task.
     *
     * @param TaskDTO $data
     * @return Task
     */
    public function createTask(TaskDTO $data): Task
    {
        return $this->task->create((array) $data);
    }

    /**
     * Update an existing task.
     *
     * @param int $id
     * @param TaskDTO $data
     * @return int
     */
    public function updateTask(int $id, TaskDTO $data): int
    {
        $task = $this->task->findOrFail($id);

        return $task->update((array) $data);
    }

    /**
     * Delete a task.
     *
     * @param int $id
     * @return int
     */
    public function deleteTask(int $id): int
    {
        $task = $this->task->findOrFail($id);

        return $task->delete();
    }
}
