<?php
namespace App\Exceptions\userNotFound;

use Exception;

class UserNotFoundException extends Exception
{
    protected $data;

    // public function __construct($message = "", $data = [], $code = 404)
    // {
    //     parent::__construct($message, $code);
    //     $this->data = $data;
    // }

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
                'message'=>'user not found',
            ]
            ],404);
    }
}
