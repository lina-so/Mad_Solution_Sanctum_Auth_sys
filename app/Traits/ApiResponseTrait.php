<?php
namespace App\Traits;

use Throwable;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Exceptions\Otp\InvalidCodeException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use App\Exceptions\userNotFound\UserNotFoundException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

trait ApiResponseTrait
{
    public function handleException(Throwable $exception, Request $request): JsonResponse
    {
        if ($exception instanceof UserNotFoundException) {
            return $this->handleUserNotFoundException($exception);
        } elseif ($exception instanceof ValidationException) {
            return $this->handleValidationException($exception);
        } elseif ($exception instanceof AuthenticationException) {
            return $this->handleAuthenticationException($exception);
        } elseif ($exception instanceof AuthorizationException) {
            return $this->handleAuthorizationException($exception);
        } elseif ($exception instanceof ModelNotFoundException) {
            return $this->handleModelNotFoundException($exception);
        }
        elseif($exception instanceof InvalidCodeException)
        {
            return $this->handleInvalidCodeException($exception);
        }elseif($exception instanceof NotFoundHttpException)
        {
            return $this->handleFileNotFoundException($exception);

        }

        // Handle other unhandled exceptions
        return $this->handleUnhandledException($exception);
    }



    protected function handleUserNotFoundException(UserNotFoundException $exception): JsonResponse
    {
        return response()->json([
            'message' => 'user not found',
        ], 404);
    }

    protected function handleFileNotFoundException(NotFoundHttpException $exception): JsonResponse
    {
        return response()->json([
            'message' => 'file not found',
        ], 404);
    }

    protected function handleInvalidCodeException(InvalidCodeException $exception): JsonResponse
    {
        return response()->json([
            'message' => 'invalid code',
        ], 404);
    }




    protected function handleValidationException(ValidationException $exception): JsonResponse
    {
        return response()->json([
            'message' => 'The given data was invalid.',
            'errors' => $exception->errors(),
        ], 422);
    }

    protected function handleAuthenticationException(AuthenticationException $exception): JsonResponse
    {
        return response()->json([
            'message' => 'You are not authenticated.',
        ], 401);
    }

    protected function handleAuthorizationException(AuthorizationException $exception): JsonResponse
    {
        return response()->json([
            'message' => 'You are not authorized to perform this action.',
        ], 403);
    }

    protected function handleModelNotFoundException(ModelNotFoundException $exception): JsonResponse
    {
        return response()->json([
            'message' => 'Record not found.',
        ], 404);
    }

    protected function handleUnhandledException(Throwable $exception): JsonResponse
    {
        \Log::error($exception->getMessage(), ['exception' => $exception]);
        return response()->json(['message' => 'An internal server error occurred.'], 500);
    }
}
