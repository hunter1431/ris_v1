<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        // keep the default behaviour
    }

    public function render($request, Throwable $e)
    {
        if ($request->is('api/*') || $request->expectsJson()) {
            report($e);

            $status = 500;
            $message = config('app.debug') ? $e->getMessage() : 'Internal server error';

            if ($e instanceof ValidationException) {
                $status = 422;
                $message = 'Validation failed.';
                return response()->json([
                    'success' => false,
                    'message' => $message,
                    'errors' => $e->errors(),
                ], $status);
            }

            if ($e instanceof ModelNotFoundException) {
                $status = 404;
                $message = 'Resource not found.';
            } elseif ($e instanceof HttpException) {
                $status = $e->getStatusCode();
                $message = $e->getMessage() ?: $message;
            } elseif ($e instanceof QueryException) {
                $status = 500;
                $message = config('app.debug') ? $e->getMessage() : 'Database error.';
            }

            return response()->json([
                'success' => false,
                'message' => $message,
            ], $status);
        }

        return parent::render($request, $e);
    }
}
