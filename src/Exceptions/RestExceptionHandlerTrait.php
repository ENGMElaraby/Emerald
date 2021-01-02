<?php

namespace MElaraby\Emerald\Exceptions;

use MElaraby\Emerald\Responses\GeneralResponse;
use Throwable;
use Illuminate\{Auth\AuthenticationException,
    Contracts\Foundation\Application,
    Validation\ValidationException,
    Http\Request,
    Database\Eloquent\ModelNotFoundException};
use Symfony\{Component\HttpKernel\Exception\MethodNotAllowedHttpException,
    Component\HttpKernel\Exception\NotFoundHttpException};

trait RestExceptionHandlerTrait
{

    protected $errors = [];

    /**
     * @param Request $request
     * @param Throwable $e
     * @return GeneralResponse|Application|mixed
     */
    protected function getJsonResponseForException(Request $request, Throwable $e)
    {
        switch (true) {
            case $this->isModelNotFoundException($e):
                $retrieve = $this->returnMessage('Invalid Data Or Not Found (isModelNotFoundException)', 404);
                break;
            case $this->NotFoundException($e):
                $retrieve = $this->returnMessage('404 Not Found (NotFoundException)', 404);
                break;
            case $this->isValidationException($e):
                $this->errors = $e->errors();
                $retrieve = $this->returnMessage($e->getMessage(), 422);
                break;
            case $this->AuthenticationException($e):
                $retrieve = $this->returnMessage($e->getMessage(), 401);
                break;
            case $this->isMethodNotAllowedException($e):
                $retrieve = $this->returnMessage($e->getMessage(), 405);
                break;
            default:
                $retrieve = $this->returnMessage($e->getMessage(), 400);
        }

        return $retrieve;
    }

    /**
     * @param $message
     * @param $statusCode
     * @return GeneralResponse|Application|mixed
     */
    protected function returnMessage($message, $statusCode)
    {
        return response()->json(['status' => $statusCode, 'message' =>  $message ?: '', 'errors' => $this->errors], $statusCode, ['Access-Control-Allow-Origin' => '*'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    /**
     * @param Throwable $e
     * @return bool
     */
    protected function isModelNotFoundException(Throwable $e)
    {
        return $e instanceof ModelNotFoundException;
    }

    /**
     * @param Throwable $e
     * @return bool
     */
    protected function NotFoundException(Throwable $e)
    {
        return $e instanceof NotFoundHttpException;
    }

    /**
     * @param Throwable $e
     * @return bool
     */
    protected function isValidationException(Throwable $e)
    {
        return $e instanceof ValidationException;
    }

    /**
     * @param Throwable $e
     * @return bool
     */
    protected function isMethodNotAllowedException(Throwable $e)
    {
        return $e instanceof MethodNotAllowedHttpException;
    }

    /**
     * @param Throwable $e
     * @return bool
     */
    protected function AuthenticationException(Throwable $e)
    {
        return $e instanceof AuthenticationException;
    }

}
