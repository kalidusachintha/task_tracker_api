<?php

namespace App\Http\Controllers\Api\V1;

use App\DTO\TaskDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\TaskStoreRequest;
use App\Http\Requests\Api\V1\TaskUpdateRequest;
use App\Http\Resources\V1\TaskResource;
use App\Services\Api\V1\Interfaces\TaskServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Config;
use Illuminate\Validation\ValidationException;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="Task Tracker",
 *     description="API Documentation",
 * )
 */
class TaskController extends Controller
{
    /**
     * @param TaskServiceInterface $taskServiceInterface
     */
    public function __construct(
        private readonly TaskServiceInterface $taskServiceInterface
    ) {}

    /**
     * Display a listing of tasks.
     *
     * @OA\Get(
     *     path="/api/v1/tasks",
     *     tags={"Tasks"},
     *     summary="Get list of tasks",
     *
     *     @OA\Parameter(
     *          name="per_page",
     *          description="Tasks per peage",
     *          required=false,
     *          in="query",
     *
     *          @OA\Schema(
     *              type="integer",
     *              format="int64"
     *          )
     *      ),
     *
     *     @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *
     *          @OA\JsonContent(
     *              type="object",
     *
     *              @OA\Property(
     *                  property="data",
     *                  type="array",
     *
     *                  @OA\Items(ref="#/components/schemas/Task")
     *              ),
     *
     *              @OA\Property(
     *                  property="links",
     *                  type="object",
     *                  @OA\Property(property="first", type="string", example="http://api.tasktraker.com:8080/api/v1/tasks?page=1"),
     *                  @OA\Property(property="last", type="string", example="http://api.tasktraker.com:8080/api/v1/tasks?page=3"),
     *                  @OA\Property(property="prev", type="string", example=null),
     *                  @OA\Property(property="next", type="string", example="http://api.tasktraker.com:8080/api/v1/tasks?page=2")
     *              ),
     *              @OA\Property(
     *                  property="meta",
     *                  type="object",
     *                  @OA\Property(property="current_page", type="integer", example=1),
     *                  @OA\Property(property="from", type="integer", example=1),
     *                  @OA\Property(property="last_page", type="integer", example=3),
     *                  @OA\Property(property="path", type="string", example="http://api.tasktraker.com:8080/api/v1/tasks"),
     *                  @OA\Property(property="per_page", type="integer", example=15),
     *                  @OA\Property(property="to", type="integer", example=15),
     *                  @OA\Property(property="total", type="integer", example=45)
     *              )
     *          )
     *     ),
     *
     *     @OA\Response(
     *          response=400,
     *          description="Bad request",
     *
     *          @OA\JsonContent(
     *
     *              @OA\Property(property="error", type="string", example="No tasks found")
     *          )
     *     )
     * )
     */
    public function index(Request $request): JsonResponse | AnonymousResourceCollection
    {
        try {
            $validated = $request->validate([
                'per_page' => ['nullable', 'integer', 'min:1', 'max:100']
            ]);
            $perPage = $validated['per_page'] ?? Config::get('pagination.per_page');
            $tasks = $this->taskServiceInterface->getAllTasks($perPage);

            return TaskResource::collection($tasks);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Invalid input. '.$e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Something went wrong',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Store a newly created task.
     *
     * @param TaskStoreRequest $request
     * @return TaskResource|JsonResponse
     *
     * @OA\Post(
     *     path="/api/v1/tasks",
     *     operationId="storeTask",
     *     tags={"Tasks"},
     *     summary="Create new task",
     *     description="Stores a new task and returns the task resource",
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"title","status"},
     *
     *             @OA\Property(property="title", type="string", example="New task title"),
     *             @OA\Property(property="description", type="string", example="Detailed task description"),
     *             @OA\Property(property="status", type="string", enum={"pending", "completed"}, example="pending"),
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Task created successfully",
     *
     *         @OA\JsonContent(ref="#/components/schemas/Task")
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="title",
     *                     type="array",
     *
     *                     @OA\Items(type="string", example="The title field is required.")
     *                 )
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="error", type="string", example="There was an issue processing the request")
     *         )
     *     )
     * )
     */
    public function store(TaskStoreRequest $request): TaskResource|JsonResponse
    {
        try {
            $task = $this->taskServiceInterface->createTask(TaskDTO::fromArray($request->validated()));

            return response()->json([
                'data' => new TaskResource($task),
                'message' => 'Task created successfully',
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'There was an issue processing the request',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     *  Display the specified task.
     *
     * @param int $id
     * @return TaskResource|JsonResponse
     *
     * @OA\Get(
     *     path="/api/v1/tasks/{id}",
     *     operationId="getTaskById",
     *     tags={"Tasks"},
     *     summary="Get task by ID",
     *     description="Returns a single task by its ID",
     *
     *     @OA\Parameter(
     *         name="id",
     *         description="Task ID",
     *         required=true,
     *         in="path",
     *
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *
     *         @OA\JsonContent(ref="#/components/schemas/Task")
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Task not found",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="error", type="string", example="Task not found")
     *         )
     *     )
     * )
     */
    public function show(int $id): TaskResource|JsonResponse
    {
        try {
            $task = $this->taskServiceInterface->getTaskById($id);

            return response()->json([
                'data' => new TaskResource($task),
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Task not found',
            ], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Update the specified task.
     *
     * @param TaskUpdateRequest $request
     * @param int $id
     * @return JsonResponse
     *
     * @OA\Put(
     *     path="/api/v1/tasks/{id}",
     *     operationId="updateTask",
     *     tags={"Tasks"},
     *     summary="Update existing task",
     *     description="Updates a task and returns success message",
     *
     *     @OA\Parameter(
     *         name="id",
     *         description="Task ID",
     *         required=true,
     *         in="path",
     *
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"title"},
     *
     *             @OA\Property(property="title", type="string", example="Updated task title"),
     *             @OA\Property(property="description", type="string", example="Updated task description"),
     *             @OA\Property(property="status", type="string", enum={"pending", "completed"}, example="pending"),
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Task updated successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Task updated successfully")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=400,
     *         description="Bad request",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="error", type="string", example="There was an issue with updating task")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="title",
     *                     type="array",
     *
     *                     @OA\Items(type="string", example="The title field is required.")
     *                 )
     *             )
     *         )
     *     )
     * )
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
     * Remove the specified task.
     *
     * @param int $id
     * @return JsonResponse
     *
     * @OA\Delete(
     *     path="/api/v1/tasks/{id}",
     *     operationId="deleteTask",
     *     tags={"Tasks"},
     *     summary="Delete existing task",
     *     description="Deletes a task and returns success message",
     *
     *     @OA\Parameter(
     *         name="id",
     *         description="Task ID",
     *         required=true,
     *         in="path",
     *
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Task deleted successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Task deleted successfully")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=400,
     *         description="Bad request",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="error", type="string", example="There was an issue with deleting task")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Task not found",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="error", type="string", example="Task not found")
     *         )
     *     )
     * )
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
