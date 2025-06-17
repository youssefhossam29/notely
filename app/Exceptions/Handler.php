<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Illuminate\Http\Request;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        if ($request->expectsJson()) {

            if ($exception instanceof \Illuminate\Validation\ValidationException) {
                return response()->json([
                    'success' => false,
                    'error' => 'Validation Error',
                    'message' => $exception->getMessage(),
                    'errors' => $exception->errors()
                ], 422);
            }

            if ($exception instanceof \Symfony\Component\HttpKernel\Exception\BadRequestHttpException) {
                return response()->json([
                    'success' => false,
                    'error' => 'Bad Request',
                    'message' => 'Oops! Something went wrong with your request. Please try again.'
                ], 400);
            }

            if ($exception instanceof \Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException ||
                $exception instanceof \Illuminate\Auth\AuthenticationException) {
                return response()->json([
                    'success' => false,
                    'error' => 'Unauthorized',
                    'message' => "Oops! you're not authorized to view this page. Please log in or check your permissions."
                ], 401);
            }

            if ($exception instanceof \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException ||
                $exception instanceof \Illuminate\Auth\Access\AuthorizationException) {
                return response()->json([
                    'success' => false,
                    'error' => 'Forbidden',
                    'message' => "Oops! you don't have permission to access this page."
                ], 403);
            }

            if ($exception instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
                return response()->json([
                    'success' => false,
                    'error' => 'Page Not Found',
                    'message' => "Oops! The page you're looking for doesn't exist or has been moved."
                ], 404);
            }

            if ($exception instanceof \Symfony\Component\HttpKernel\Exception\HttpException && $exception->getStatusCode() == 408) {
                return response()->json([
                    'success' => false,
                    'error' => 'Forbidden',
                    'message' => 'Your request timed out. Please try again.'
                ], 408);
            }

            if ($exception instanceof \Illuminate\Session\TokenMismatchException) {
                return response()->json([
                    'success' => false,
                    'error' => 'Page Expired',
                    'message' => 'The page has expired due to inactivity. Please refresh and try again.'
                ], 419);
            }

            if ($exception instanceof \Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException) {
                return response()->json([
                    'success' => false,
                    'error' => 'Too Many Requests',
                    'message' => "You've sent too many requests. Please wait a moment and try again."
                ], 429);
            }

            if ($exception instanceof \Symfony\Component\HttpKernel\Exception\HttpException && $exception->getStatusCode() == 500) {
                return response()->json([
                    'success' => false,
                    'error' => 'Internal Server Error',
                    'message' => 'Oops! Something went wrong on our end. Please try again later.'
                ], 500);
            }

            if ($exception instanceof \Symfony\Component\HttpKernel\Exception\HttpException && $exception->getStatusCode() == 502) {
                return response()->json([
                    'success' => false,
                    'error' => 'Bad Gateway',
                    'message' => 'Bad Gateway. Please try again in a few minutes.'
                ], 502);
            }

            if ($exception instanceof \Symfony\Component\HttpKernel\Exception\HttpException && $exception->getStatusCode() == 503) {
                return response()->json([
                    'success' => false,
                    'error' => 'Service Unavailable',
                    'message' => 'Service temporarily unavailable. Please check back soon.'
                ], 503);
            }

            if ($exception instanceof \Symfony\Component\HttpKernel\Exception\HttpException && $exception->getStatusCode() == 504) {
                return response()->json([
                    'success' => false,
                    'error' => 'Gateway Timeout',
                    'message' => 'The server took too long to respond. Please try again later.'
                ], 504);
            }

            return response()->json([
                'success' => false,
                'error' => class_basename($exception),
                'message' => $exception->getMessage() ?: 'Unexpected error occurred.'
            ], method_exists($exception, 'getStatusCode') ? $exception->getStatusCode() : 500);
        }

        return parent::render($request, $exception);
    }

}
