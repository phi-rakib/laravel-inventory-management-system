<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Http\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
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
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {

        if ($exception instanceof ModelNotFoundException) {
            return response()->json(
                [
                    'error' => 'Resource not found',
                ],
                Response::HTTP_NOT_FOUND
            );
        }
        if ($exception instanceof MethodNotAllowedHttpException) {
            return response()->json(
                [
                    'error' => 'The specified method for the request is invalid',
                ],
                Response::HTTP_METHOD_NOT_ALLOWED
            );
        }

        if ($exception instanceof NotFoundHttpException) {
            return response()->json(
                [
                    'error' => 'The specified URL cannot be found',
                ],
                Response::HTTP_NOT_FOUND
            );
        }

        if ($exception instanceof HttpException) {
            return response()->json(
                [
                    'error' => $exception->getMessage(),
                ],
                $exception->getStatusCode()
            );
        }

        return parent::render($request, $exception);
    }
}
