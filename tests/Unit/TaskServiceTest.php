<?php

namespace Tests\Unit;

use App\DTO\TaskDTO;
use App\Models\Task;
use App\Models\TaskStatus;
use App\Services\Api\V1\TaskService;
use Tests\TestCase;


class TaskServiceTest extends TestCase
{
    public function test_can_create_task(): void
    {
        $taskStatus = TaskStatus::factory()->create();
        $taskData = new TaskDTO(
            id: null,
            title: 'Test Task',
            description: 'Task description',
            task_status_id : $taskStatus->id
        );

        $expectedTaskDTO = new TaskDTO(
            id: 1,
            title: $taskData->title,
            description: $taskData->description,
            task_status_id: $taskData->task_status_id
        );

        $taskService = new TaskService(new Task());
        $result = $taskService->createTask($taskData);
        $this->assertEquals($expectedTaskDTO->task_status_id, $result->task_status_id);
    }

    public function test_can_edit_task(): void
    {
        $taskStatus = TaskStatus::factory()->create();
        $taskData = Task::factory()->create([
            'description' => 'Task description',
            'task_status_id' => $taskStatus->id
        ]);
        $updatedTask = new TaskDTO(
            id: $taskData->id,
            title: 'Test Task',
            description: 'Task description updated',
            task_status_id : $taskStatus->id
        );

        $taskService = new TaskService(new Task());
        $taskService->updateTask($taskData->id, $updatedTask);
        $this->assertDatabaseHas('tasks', [
            'id' => $updatedTask->id,
            'title' => $updatedTask->title,
            'description' => $updatedTask->description,
            'task_status_id' => $updatedTask->task_status_id,
        ]);
    }

    public function test_can_find_task(): void
    {
        $taskData = Task::factory()->create();
        $taskService = new TaskService(new Task());
        $result = $taskService->getTaskById($taskData->id);
        $this->assertDatabaseHas('tasks', [
            'id' => $taskData->id,
        ]);
        $this->assertEquals($result->id, $taskData->id);
        $this->assertInstanceOf(Task::class, $result);
    }

    public function test_can_delete_task(): void
    {
        $taskData = Task::factory()->create();
        $taskService = new TaskService(new Task());
        $taskService->deleteTask($taskData->id);
        $this->assertSoftDeleted($taskData);
    }
}
