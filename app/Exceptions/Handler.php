<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


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
        if ($exception instanceof ValidationException) {
            return $this->convertValidationExceptionToResponse($exception, $request);
        }

        if ($exception instanceof ModelNotFoundException) {
            $modelName = class_basename($exception->getModel());
            return $this->errorResponse("Model {$modelName} not found with the specified identifier", 404);
        }

        if ($exception instanceof NotFoundHttpException) {
            return $this->errorResponse("The specified URL cannot be found", 404);
        }

        if ($exception instanceof MethodNotAllowedHttpException) {
            return $this->errorResponse("The specified Http method is invalid", 405);
        }

        if ($exception instanceof HttpException) {
            return $this->errorResponse($exception.getMessage(), $exception.getStatusCode());
        }

        if ($exception instanceof QueryException) {
            $errorCode = $exception->errorInfo[1];

            if ($errorCode = 1451) {
                return $this->errorResponse("Query is not valid.", 409);
            }
        }

        if (config('app.debug')){
            return parent::render($request, $exception);
        }

        return response()->json(['error' => 'Unexpected Exception. Try later.', 'code' => 500], 500);

    }

    protected function convertValidationExceptionToResponse(ValidationException $e, $request)
    {
        $errors = $e->validator->errors()->getMessages();

        if ($this->isFrontend($request)) {
            return $request->ajax() ? response()->json($errors, 422) : redirect()
                ->back()
                ->withInput($request->input())
                ->withErrors($errors);
        }

        return $this->errorResponse($errors, 422);
    }

    private function isFrontend(Request $request) {
        return $request->acceptsHtml() && collect($request->route()->middleware())->contains('web');
    }

    protected function errorResponse($message, $code){
        return response()->json(['error' => $message, 'code' => $code], $code);
    }
}
