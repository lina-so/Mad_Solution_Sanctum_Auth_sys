<?php
namespace App\Traits;

use Throwable;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

trait ApiResponseTrait
{

    public function apiError($message='',$errors=null ,$code=400){

        if($message=='')
        {
            if (!is_null($errors)) {
                $response['errors'] = $errors;
            }

        }else{
            $response = [
                'message' => $message,
            ];
        }

        return response()->json($response,$code);

    }

/*****************************************************************************/
    public function apiSuccess($message='',$data = null ,$code=200){

        $response = [];

    if ($message !== '') {
        $response['message'] = $message;
    }

    if (!is_null($data)) {
        $response['token'] = $data;
    }

    return response()->json($response, $code);

    }
}
