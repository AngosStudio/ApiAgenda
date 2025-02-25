<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
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

    public function render($request, Throwable $e)
    {
        if ($request->wantsJson()) {   //add Accept: application/json in request
            return $this->handleApiException($request, $e);
        } else {
            $retval = parent::render($request, $e);
        }

        return $retval;
    }

    private function handleApiException($request, \Exception $exception)
    {
        $exception = $this->prepareException($exception);

        if ($exception instanceof \Illuminate\Http\Exception\HttpResponseException) {
            $exception = $exception->getResponse();
        }

        if ($exception instanceof \Illuminate\Auth\AuthenticationException) {
            $exception = $this->unauthenticated($request, $exception);
        }

        if ($exception instanceof \Illuminate\Validation\ValidationException) {
            $exception = $this->convertValidationExceptionToResponse($exception, $request);
        }

        return $this->customApiResponse($exception);
    }

    private function customApiResponse($exception)
    {
        if (method_exists($exception, 'getStatusCode')) {
            $statusCode = $exception->getStatusCode();
        } else {
            $statusCode = 500;
        }

        if ($statusCode === 401) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $response = [
            'success' => false,
            'code' => $statusCode,
        ];

        switch ($statusCode) {
            // case 401:
            //     $response['message'] = 'Unauthorized';
            //     break;
            case 403:
                $response['message'] = 'Forbidden';
                break;
            case 404:
                $response['message'] = 'Not Found';
                break;
            case 405:
                $response['message'] = 'Method Not Allowed';
                break;
            case 422:
                $response['message'] = $exception->original['message'];
                break;
            default:
                $response['message'] = $statusCode === 500 ? 'Whoops, looks like something went wrong' : $exception->getMessage();
                break;
        }

        if (!empty($exception->original['errors'])) {
            $response['errors'] = $exception->original['errors'];
        }

        if (config('app.debug')) {
            $response['debug']['status'] = $statusCode;
            $response['debug']['code'] = $exception->getCode();
            $response['debug']['trace'] = $exception->getTrace();
        }

        return response()->json($response, $statusCode);
    }
}
