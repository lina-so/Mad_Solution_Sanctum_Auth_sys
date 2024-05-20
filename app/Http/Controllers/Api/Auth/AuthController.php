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
    use FileOperationsTrait;
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
        try {
            $user = $this->logoutService->logout();
            return response()->json(['message' => 'Logged out successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
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
            throw new UserNotFoundException();
        }
        if ($type === 'image' && $user->profile_photo !== $path) {
            throw new AuthorizationException();
        }
        if ($type === 'pdf' && $user->certificate !== $path) {
            throw new AuthorizationException();
        }

        $file = $this->delete($fullPath,'public');

        return response()->json(['message' => 'file deleted successfully', 'file' => $file]);
    }


}
