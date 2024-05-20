<?php

namespace App\Exceptions\Otp;

use Exception;

class InvalidCodeException extends Exception
{
    public function report(): void
    {
        // ...
    }

    /**
     * Render the exception into an HTTP response.
     */
    public function render(Request $request): Response
    {
        return new JsonResponse([
            'errors'=>[
                'message'=>'invalid code',
            ]
            ],404);
    }
}
