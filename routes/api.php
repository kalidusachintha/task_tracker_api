<?php

use App\Http\Controllers\Api\V1\TaskController;
use App\Http\Controllers\Api\V1\TaskStatusController;
use Illuminate\Support\Facades\Route;

Route::middleware('throttle:api')->group(function () {
    Route::apiResource('/tasks', TaskController::class);
    Route::apiResource('/statuses', TaskStatusController::class)->only(['index']);
});
