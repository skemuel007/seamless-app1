<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Translation\Exception\NotFoundResourceException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

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
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        // this will replace 404 response from mvc to a json response
        if ( $exception instanceof QueryException) {
            // if the exception is a Database query exeception
            return response()->json([
                'title' => 'Query Error',
                'message' => $exception->getMessage(),
                'data' => []
            ], 500);
        }

        // This will replace 404 response from the MVC to a JSON response
        if($exception instanceof ModelNotFoundException && $request->wantsJson())
        {
            return response()->json(

                [
                    'title' => 'Model Exception Error',
                    'message' =>  $exception->getMessage(),
                    'data' => []
                ]
                , 500);
        }
        // https://stackoverflow.com/questions/53279247/laravel-how-to-show-json-when-api-route-is-wrong-or-not-found
        if($exception instanceof NotFoundHttpException ) {
            return response()->json([
                'title' => 'Http Not Found Error',
                'message' => 'Invalid Http Url - resource locator not found.',
                'data' => []
            ], 500);
        }

        if($exception instanceof NotFoundResourceException){
            return response()->json([
                'title' => 'Resource Not Found Error',
                'message' => 'Resource error',
                'data' => []
            ], 500);
        }

        if($exception instanceof ModelNotFoundException)
        {
            return response()->json(
                [
                    'title' => 'Model Exception Error',
                    'message' =>  $exception->getMessage(),
                    'data' => []
                ]
                , 404);
        }

        if( $exception instanceof MethodNotAllowedHttpException) {
            return response()->json([
                'title' => 'Http Method Error',
                'message' => 'Http method not valid for this url',
                'data' => []], Response::HTTP_METHOD_NOT_ALLOWED);
        }

        if ( $exception instanceof BindingResolutionException) {
            return response()->json([
                'title' => 'Target Class not found',
                'message' => $exception->getMessage(),
                'data' => []
            ], 500);
        }

        if( $exception instanceof UnauthorizedHttpException) {

            return response()->json([
                'title' => 'Authorization Error',
                'message' => $exception->getMessage(),
                'data' => ['Either token is expired or not provided']], 401);
        }

        if( $exception instanceof TokenInvalidException) {
            return response()->json([
                'title' => 'Invalid Token Error',
                'message' => 'Token supplied is invalid, please login with appropriate credentials',
                'data' => []], 500);
        }

        if($exception instanceof TokenExpiredException) {
            return response()->json([
                'title' => 'Token Expired Error',
                'message' => 'Login again to get fresh token',
                'data' => 'Token expired'], 500);
        }

        // JWT Auth related errors
        if( $exception instanceof  JWTException) {
            return response()->json([
                'title' => 'JWT Exception',
                'message' => $exception->getMessage(),
                'data' => []], 500);
        }
        return parent::render($request, $exception);
    }
}
