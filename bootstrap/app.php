<?php

declare(strict_types=1);

use App\Http\Middleware\AdminMiddlewear;
use App\Http\Middleware\UserOwnershipMiddlewear;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\Log;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'User.Ownership' => UserOwnershipMiddlewear::class,
            'admin.permissions' => AdminMiddlewear::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->reportable(function (NotFoundHttpException $e) {
            Log::error('Recurso no encontrado: ' . $e->getMessage());
        });
        $exceptions->renderable(function (NotFoundHttpException $e) {
            return response()->json(['error' => 'Recurso no encontrado'], 404);
        });

        $exceptions->reportable(function (ValidationException $e) {
            Log::error('Error de validación: ' . $e->getMessage());
        });
        $exceptions->renderable(function (ValidationException $e) {
            return response()->json(['error' => 'Datos de entrada inválidos'], 422);
        });

        $exceptions->reportable(function (AuthenticationException $e) {
            Log::error('Error de autenticación: ' . $e->getMessage());
        });
        $exceptions->renderable(function (AuthenticationException $e) {
            return response()->json(['error' => 'No autenticado'], 401);
        });


        $exceptions->reportable(function (AuthorizationException $e) {
            Log::error('Error de autorización: ' . $e->getMessage());
        });
        $exceptions->renderable(function (AuthorizationException $e) {
            return response()->json(['error' => 'No autorizado'], 403);
        });

        $exceptions->reportable(function (Throwable $e) {
            Log::error('Error interno del servidor: ' . $e->getMessage());
        });
        $exceptions->renderable(function (Throwable $e) {
            return response()->json(['error' => 'Error interno del servidor'], 500);
        });
    })
    ->create();
