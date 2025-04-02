<?php

namespace App\Http\Controllers\Api\V1;

use App\DTO\TaskDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\TaskStoreRequest;
use App\Http\Requests\Api\V1\TaskUpdateRequest;
use App\Http\Resources\V1\TaskResource;
use App\Services\Api\V1\Interfaces\TaskServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class TaskController extends Controller
{
    public function __construct(
        private readonly TaskServiceInterface $taskServiceInterface
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(): AnonymousResourceCollection
    {
        $tasks = $this->taskServiceInterface->getAllTasks();

        return TaskResource::collection($tasks);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param TaskStoreRequest $request
     * @return TaskResource|JsonResponse
     */
    public function store(TaskStoreRequest $request): TaskResource|JsonResponse
    {
        try {
            $task = $this->taskServiceInterface->createTask(TaskDTO::fromArray($request->validated()));

            return response()->json([new TaskResource($task), Response::HTTP_OK]);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'There was an issue processing the request'.$th->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return TaskResource|JsonResponse
     */
    public function show(int $id): TaskResource|JsonResponse
    {
        try {
            $task = $this->taskServiceInterface->getTaskById($id);

            return new TaskResource($task);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Task not found',
            ], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param TaskUpdateRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(TaskUpdateRequest $request, int $id): JsonResponse
    {
        try {
            $this->taskServiceInterface->updateTask($id, TaskDTO::fromArray($request->validated()));

            return response()->json([
                'message' => 'Task updated successfully',
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'There was an issue with updating task',
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $this->taskServiceInterface->deleteTask($id);

            return response()->json([
                'message' => 'Task deleted successfully',
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'There was an issue with deleting task',
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
