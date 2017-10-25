<?php

namespace App\Exceptions;

use App\Http\Response\ErrorResponse;
use App\Http\Response\ResponseTemplate;
use Exception;
use Firebase\JWT\ExpiredException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;

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
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if($exception instanceof ValidationException){
            return (new ResponseTemplate(100,$exception->errors(),$exception->getMessage()))
                ->status($exception->status)
                ->toResponse($request);
        }

        if($exception instanceof AuthenticationException ||
            $exception instanceof ExpiredException ){
            return (new ResponseTemplate(401,null,'未通过认证！'))
                ->status(401)
                ->toResponse($request);
        }

        return parent::render($request, $exception);
    }
}
