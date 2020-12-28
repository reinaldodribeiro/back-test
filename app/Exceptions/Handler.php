<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        $data = [
            'status' => false,
            'status_code' => 500,
            'message' => $exception->getMessage()
        ];

        if($exception instanceof AuthenticationException){
            $data['status_code'] = 401;
        }

        if($exception instanceof HttpException){
            $data['status_code'] = $exception->getStatusCode();
        }

        if($exception instanceof ModelNotFoundException){
            $data['status_code'] = 404;
        }

        if($exception instanceof ValidationException){
            $data['status_code'] = $exception->status;
            $data['errors'] = $exception->errors();
        }

        $data['stacktrace'] = $exception->getTraceAsString();

        return response()->json($data, $data['status_code']);
    }
}
