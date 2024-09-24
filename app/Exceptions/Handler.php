<?php

namespace App\Exceptions;


use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Auth;
use BadMethodCallException;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Session\HttpResponseException;
use stdClass;
use \Symfony\Component\HttpKernel\Exception\NotFoundException;
use \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class, //sulata comment
        \Illuminate\Validation\ValidationException::class,
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
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->renderable(function (MethodNotAllowedHttpException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => 'Record not found.'
                ], 404);
            }
        });

        $this->renderable(function (AuthenticationException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json(
                    [
                        'status' => 401,
                        'message' => $e->getMessage(),
                        'data' => new stdClass(),
                        'error' => [
                            [
                                'field' => 'generic',
                                'errors' => [
                                    config('app.env') == 'production'
                                        ? __('User is not authenticated')
                                        : $e->getMessage(),
                                ],
                            ],
                        ],
                        'meta' => config('app.env') == 'production'
                            ? []
                            : $e->getTrace(),
                        'info' => [],
                    ],
                    401

                );
            }
        });

        $this->renderable(function (BadMethodCallException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json(
                    [
                        'status' => 500,
                        'message' => $e->getMessage(),
                        'data' => new stdClass(),
                        'error' => [
                            [
                                'field' => 'generic',
                                'errors' => [
                                    config('app.env') == 'production'
                                        ? __('Bad request')
                                        : $e->getMessage(),
                                ],
                            ],
                        ],
                        'meta' => config('app.env') == 'production'
                            ? []
                            : $e->getTrace(),
                        'info' => [],
                    ],
                    500
                );
            }
        });

    }

     /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Throwable
     */
    // public function report(Throwable $exception)
    // {
    //     // $ignoreable_exception_messages = ['Unauthenticated or Session Expired, Please Login'];
    //     // $ignoreable_exception_messages[] = 'The resource owner or authorization server denied the request.';
    //     // if (app()->bound('sentry') && $this->shouldReport($exception)) {
    //     //     if (!in_array($exception->getMessage(), $ignoreable_exception_messages)) {
    //     //         app('sentry')->captureException($exception);
    //     //     }
    //     // } //other

    //     parent::report($exception);
    // }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
 
}
