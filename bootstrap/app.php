<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        apiPrefix: 'api/v1',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->renderable(function (TypeError $e) {
            return response()->json([
                'message' => 'Entity not found',
            ], Response::HTTP_BAD_REQUEST);
        });
        $exceptions->renderable(function (NotFoundHttpException $e) {
            return response()->json([
                'message' => 'Invalid URL',
            ], Response::HTTP_BAD_REQUEST);
        });
    })->create();
