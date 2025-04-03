<?php

namespace App\Providers;

use App\Services\Api\V1\Interfaces\TaskServiceInterface;
use App\Services\Api\V1\TaskService;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(TaskServiceInterface::class, TaskService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(100)->response(function (Request $request, array $headers) {
                return response(
                    'You have exceeded the allowed request limit. Please try again after a minute.',
                    Response::HTTP_TOO_MANY_REQUESTS,
                    $headers
                );
            });
        });
    }
}
