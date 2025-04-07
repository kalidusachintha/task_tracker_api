<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\TaskStatusResource;
use App\Services\Api\V1\Interfaces\TaskStatusServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;


class TaskStatusController extends Controller
{
    /**
     * @param TaskStatusServiceInterface $taskStatusServiceInterface
     */
    public function __construct(
        private readonly TaskStatusServiceInterface $taskStatusServiceInterface
    ) {}

    /**
     * Get all the Tasks status.
     *
     * @return JsonResponse|AnonymousResourceCollection
     *
     * @OA\Get(
     *      path="/api/v1/statuses",
     *      tags={"Tasks Status"},
     *      summary="Get list of Status",
     *
     *      @OA\Response(
     *           response=200,
     *           description="Successful operation",
     *
     *           @OA\JsonContent(
     *               type="object",
     *
     *               @OA\Property(
     *                   property="data",
     *                   type="array",
     *
     *                   @OA\Items(ref="#/components/schemas/TaskStatus")
     *               ),
     *           )
     *      ),
     *
     *      @OA\Response(
     *           response=500,
     *           description="Internal server Error",
     *
     *           @OA\JsonContent(
     *
     *               @OA\Property(property="error", type="string", example="Something went wrong")
     *           )
     *      )
     *  )
     */

    public function index(): JsonResponse|AnonymousResourceCollection
    {
        try {
            $statuses = $this->taskStatusServiceInterface->getAllStatuses();

            return TaskStatusResource::collection($statuses);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Something went wrong',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
