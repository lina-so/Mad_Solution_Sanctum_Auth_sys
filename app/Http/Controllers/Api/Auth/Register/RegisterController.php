<?php

namespace App\Http\Controllers\Api\Auth\Register;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Register\RegisterRequest;
use App\Services\Auth\Register\RegisterService;

class RegisterController extends Controller
{
    protected $registerService;
    public function __construct(RegisterService $registerService)
    {
        $this->registerService = $registerService;
    }
    /************************************************************************/
    public function signUp(RegisterRequest $request)
    {
        $data = $this->registerService->signUp($request);
        return response()->json($data);
    }
    /**********************************************************************/
    public function resendVerifyCode()
    {
        $newCode = $this->registerService->resendVerifyCode();
        return response()->json(['message' => 'verification code re-send successfully', 'verification-code' => $newCode]);
    }
    /************************************************************************/

    public function confirmVerifyCode(Request $request)
    {
        $confirmCode = $this->registerService->confirmVerifyCode($request);

        if($confirmCode == false)
        {
            return response()->json(['message' => 'invalid code','code'=>$confirmCode],401);
        }
        return response()->json(['message' => 'you are verification your email '],200);


    }
    /******************************************************************************************************/
    public function refreshToken()
    {
        $refreshToken = $this->registerService->refreshToken();
        return response()->json(['access_token' => $refreshToken],200);


    }
}
