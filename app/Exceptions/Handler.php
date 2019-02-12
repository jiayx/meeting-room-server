<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
        if ($exception instanceof ValidationException) {
            return $this->validateFailed($request, $exception);
        } elseif ($exception instanceof ModelNotFoundException) {
            return $this->modelNotFound($request, $exception);
        } elseif ($exception instanceof NotFoundHttpException) {
            return $this->notFound($request, $exception);
        } elseif ($exception instanceof MethodNotAllowedHttpException) {
            return $this->methodMotAllow($request, $exception);
        }

        if ($request->expectsJson()) {
            $code = (string) $exception->getCode();
            return response()->json([
                'code' => $code ?: '500',
                'message' => $exception->getMessage(),
            ]);
        }

        return parent::render($request, $exception);
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

    protected function notFound($request, NotFoundHttpException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'code' => '404',
                'message' => 'Sorry, the page you are looking for could not be found.',
            ]);
        }

        return parent::render($request, $exception);
    }

    protected function modelNotFound($request, ModelNotFoundException $exception)
    {
        $modelName = $exception->getModel();
        $modelName = trim(substr($modelName, strripos($modelName, '\\')), '\\');

        if ($request->expectsJson()) {
            return response()->json([
                'code' => '4041',
                'message' => $modelName.' not found',
            ]);
        } else {
            return parent::render($request, $exception);
        }
    }

    protected function methodMotAllow($request, MethodNotAllowedHttpException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'code' => '405',
                'message' => 'method not allow',
            ]);
        } else {
            return parent::render($request, $exception);
        }
    }

    protected function validateFailed($request, ValidationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'code' => '400',
                'message' => $this->convertValidationErrorMessage($exception->getResponse())
            ]);
        } else {
            return response($exception->getMessage());
        }
    }

    protected function convertValidationErrorMessage(Response $response)
    {
        $errorMessage = $response->getOriginalContent();

        if (count($errorMessage) > 0) {
            $error = array_shift($errorMessage);

            if (isset($error[0])) {
                return $error[0];
            }
        }

        return 'The given data failed to pass validation';
    }
}
