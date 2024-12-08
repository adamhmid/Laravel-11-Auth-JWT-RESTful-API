<?php

use App\Http\Middleware\EnsureUserIsAuthenticated;
use App\Http\Middleware\ProductOwnershipMiddleware;
use App\Traits\ApiResponseFormatTrait;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

return Application::configure(basePath: dirname(__DIR__))
  ->withRouting(
    api: __DIR__ . '/../routes/api.php',
    commands: __DIR__ . '/../routes/console.php',
    health: '/up',
  )
  ->withMiddleware(function (Middleware $middleware) {
    $middleware->prependToGroup('auth', EnsureUserIsAuthenticated::class);

    $middleware->alias([
      'product' => ProductOwnershipMiddleware::class,
      'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
      'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
      'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
    ]);
  })
  ->withExceptions(function (Exceptions $exceptions) {

    # Handles cases where the user is not authenticated.
    $exceptions->render(function (AuthenticationException $e, Request $request) {
      if ($request->is('api/*')) {
        return ApiResponseFormatTrait::unauthorizedResponseException();
      }
    });

    # Handles cases where the user is not authenticated.
    $exceptions->render(function (UnauthorizedHttpException $e, Request $request) {
      if ($request->is('api/*')) {
        return ApiResponseFormatTrait::unauthorizedResponseException();
      }
    });

    # Handles cases where the user does not have the required authorization.
    $exceptions->render(function (\Spatie\Permission\Exceptions\UnauthorizedException $e, Request $request) {
      if ($request->is('api/*')) {
        return ApiResponseFormatTrait::forbiddenResponseException();
      }
    });

    # Handles cases where a requested resource is not found.
    $exceptions->render(function (NotFoundHttpException $e, Request $request) {
      if ($request->is('api/*')) {
        return ApiResponseFormatTrait::notFoundResponse();
      }
    });

    # Handles instances where the request method is not allowed for the specified route.
    $exceptions->render(function (MethodNotAllowedHttpException $e, Request $request) {
      if ($request->is('api/*')) {
        return ApiResponseFormatTrait::methodNotAllowedResponse();
      }
    });

    # Custom handling for NotAcceptableHttpException rendering.
    $exceptions->render(function (NotAcceptableHttpException  $e, Request $request) {
      if ($request->is('api/*')) {
        return ApiResponseFormatTrait::notAcceptableResponse();
      }
    });

    # Customizes the rendering of TooManyRequestsHttpException.
    $exceptions->render(function (TooManyRequestsHttpException  $e, Request $request) {
      if ($request->is('api/*')) {
        return ApiResponseFormatTrait::tooManyRequestsResponse();
      }
    });

    # Catches general HTTP-related exceptions
    $exceptions->render(function (HttpException $e, Request $request) {
      if ($request->is('api/*')) {
        ApiResponseFormatTrait::recordException($e);
        return ApiResponseFormatTrait::internalServerErrorResponse();
      }
    });

    # Captures exceptions related to database queries
    $exceptions->render(function (QueryException $e, Request $request) {
      if ($request->is('api/*')) {
        ApiResponseFormatTrait::recordException($e);
        return ApiResponseFormatTrait::internalServerErrorResponse();
      }
    });
  })->create();
