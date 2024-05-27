<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\login\LoginRequest;
use App\Services\Auth\Login\LoginService;
use App\Traits\Files\FileOperationsTrait;
use App\Services\Auth\Logout\LogoutService;
use App\Http\Requests\Register\RegisterRequest;
use App\Services\Auth\Register\RegisterService;
use Illuminate\Auth\Access\AuthorizationException;
use App\Exceptions\userNotFound\UserNotFoundException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AuthController extends Controller
{
    use FileOperationsTrait,ApiResponseTrait;
    public  $registerService , $logoutService ,$loginService;

    public function __construct(RegisterService $registerService,LogoutService $logoutService,LoginService $loginService)
    {
        $this->registerService = $registerService;
        $this->logoutService = $logoutService;
        $this->loginService = $loginService;


    }
    /*--------------------- signUp----------------------------*/
    /************************************************************************/
    public function signUp(RegisterRequest $request)
    {
        $data = $this->registerService->signUp($request);
        return $this->apiSuccess('Registration successful',$data, 201);

    }
    /**********************************************************************/
    public function resendVerifyCode()
    {
        $newCode = $this->registerService->resendVerifyCode();
        return $this->apiSuccess('verification code re-send successfully ',null, 200);
    }
    /************************************************************************/

    public function confirmVerifyCode(Request $request)
    {
        $confirmCode = $this->registerService->confirmVerifyCode($request);

        if($confirmCode == false)
        {
            $message = "Invalid code";
            return $this->apiError(message: $message, code: 404);
        }
        return $this->apiSuccess('you are verification your email ',null, 200);



    }
    /******************************************************************************************************/
    public function refreshToken()
    {
        $refreshToken = $this->registerService->refreshToken();
        return response()->json(['access_token' => $refreshToken],200);


    }
    /*-----------------------login------------------*/
    public function login(LoginRequest $request)
    {
        $data = $this->loginService->login($request);
        return $data;
    }
    /******************************************************************************************************/
    /*-----------------------logout------------------*/
    public function logout(Request $request)
    {
        $user = $this->logoutService->logout();
        return $this->apiSuccess('Logged out successfully',null, 200);
    }
    /******************************************************************************************************/


    public function deleteFile(Request $request)
    {
        $path =$request->path;
        $fullPath = $request->fullPath;
        $type = $request->type;
        $user_id = $request->user_id;
        $user = User::find($user_id);

        if(!$user)
        {
            $message = "User not found";
            return $this->apiError(message: $message, code: 404);
        }
        if ($type === 'image' && $user->profile_photo !== $path) {
            $message = "You are not authorized to perform this action";
            return $this->apiError(message: $message, code: 403);
        }
        if ($type === 'pdf' && $user->certificate !== $path) {
            $message = "You are not authorized to perform this action";
            return $this->apiError(message: $message, code: 403);
        }

        $file = $this->delete($fullPath,'public');
        return $this->apiSuccess('file deleted successfully',null, 200);

    }


}
