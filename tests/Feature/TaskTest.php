<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\TaskStatus;
use Tests\TestCase;

class TaskTest extends TestCase
{
    /**
     * Get all the tasks
     */
    public function test_get_all_tasks(): void
    {
        Task::factory()->count(15)->create();
        $response = $this->getJson('/api/v1/tasks');

        $response->assertStatus(200);
        $response->assertJsonCount(15, 'data');
    }

    /**
     * Can create task
     * @return void
     */
    public function test_can_create_task(): void
    {
        $taskStatus = TaskStatus::factory()->create();
        $response = $this->postJson('/api/v1/tasks', [
            'title' => 'Test Task',
            'description' => 'Test Description',
            'task_status_id' => $taskStatus->id,
        ]);
        $response->assertStatus(200);
        $this->assertEquals($taskStatus->id, $response['data']['task_status_id']);
    }

    /**
     * Can get a task
     * @return void
     */
    public function test_can_get_single_task(): void
    {
        $task = Task::factory()->create();

        $response = $this->getJson("/api/v1/tasks/{$task->id}");

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $task->id,
                    'title' => $task->title,
                    'description' => $task->description,
                    'created_date' => $task->created_at->format('Y-m-d'),
                ]
            ]);
    }

    /**
     * Can update the task
     * @return void
     */
    public function test_can_update_task(): void
    {
        $task = Task::factory()->create();

        $updatedData = [
            'title' => 'Updated Task',
            'description' => 'This task has been updated',
        ];

        $response = $this->putJson("/api/v1/tasks/{$task->id}", $updatedData);

        $this->assertNotEquals($task->title, $updatedData['title']);
        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Task updated successfully'
            ]);
    }

    /**
     * Can delete a task
     * @return void
     */
    public function test_can_delete_task(): void
    {
        $task = Task::factory()->create();

        $response = $this->deleteJson("/api/v1/tasks/{$task->id}");
        $response->assertStatus(200);
        $this->assertSoftDeleted('tasks', ['id' => $task->id]);
    }

    /**
     * Create a task without title
     * @return void
     */
    public function test_cannot_create_task_without_title(): void
    {
        $response = $this->postJson('/api/v1/tasks', [
            'description' => 'Description without title'
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title']);
    }
}
