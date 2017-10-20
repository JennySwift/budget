<?php

namespace App\Exceptions;

use App\Http\Requests\Request;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
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
        if ($exception instanceof NotLoggedInException) {
            return response([
                'error' => "Not logged in.",
                'status' => Response::HTTP_UNAUTHORIZED
            ], Response::HTTP_UNAUTHORIZED);
        }

        if ($exception instanceof ModelAlreadyExistsException) {
            return response([
                'error' => "{$exception->model} already exists.",
                'status' => Response::HTTP_BAD_REQUEST
            ], Response::HTTP_BAD_REQUEST);
        }

        if ($exception instanceof \InvalidArgumentException) {
            return response([
                'error' => $exception->getMessage(),
                'status' => Response::HTTP_BAD_REQUEST,
                'request' => $this->getRequestData($request)
            ], Response::HTTP_BAD_REQUEST);
        }

        // Model not found exception handler (app-wide)
        if ($exception instanceof ModelNotFoundException) {

            // Build a "fake" instance of the model which was not found
            // and fetch the shortname of the class
            // Ex.: If we have a App\Models\Projects\Project model
            // Then we would return Project
            $model = (new \ReflectionClass($exception->getModel()))->getShortName();

            return response([
                'error' => "{$model} not found.",
                'status' => Response::HTTP_NOT_FOUND
            ], Response::HTTP_NOT_FOUND);
        }

        else if ($exception instanceof ValidationException) {
            return parent::render($request, $exception);
        }

        else if ($exception instanceof AuthenticationException) {
            return parent::render($request, $exception);
        }

		else if ($exception->getMessage()) {
            return response([
                'error' => $exception->getMessage(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'request' => $this->getRequestData($request)
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }


        return parent::render($request, $exception);
    }

    /**
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    private function getRequestData(\Illuminate\Http\Request $request)
    {
        return [
            'queryString' => $request->getQueryString(),
            'requestUri' => $request->getRequestUri(),
            'toArray' => $request->toArray()
        ];
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        return redirect()->guest(route('login'));
    }
}
